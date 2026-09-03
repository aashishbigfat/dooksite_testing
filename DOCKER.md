# Dockerizing dookwebsite for Google Cloud Platform

A container image for the existing Laravel app, deployed to a **Compute
Engine VM**.

> **For the actual deployment, follow
> [`explainers/gcp_hosting_guide.md`](explainers/gcp_hosting_guide.md), not
> the VM/network steps in this file.**
>
> This document was written assuming the VM could sit in the same VPC as
> Cloud SQL and reach `192.168.4.7` by a private route. That turned out to be
> wrong: `192.168.4.7` is reachable only through an **OpenVPN tunnel** to
> `34.100.210.26`, in a GCP project this account cannot administer. The
> deployment therefore runs an OpenVPN client on the VM. The hosting guide
> has the corrected architecture and evidence.
>
> Everything else here — the image, entrypoint, php-fpm pool, GCS
> credentials, the `route:cache` warning — is unaffected and still accurate.

**No application source file was changed to containerize this.** Every file
listed below is new (`Dockerfile`, `docker-compose.yml`,
`docker-compose.prod.yml`, `.dockerignore`, `.env.docker`, `docker/*`). The
Google Cloud Storage integration in `app/Http/Helper/Helper.php` is
untouched.

## Why a VM and not Cloud Run

Cloud Run is the usual default for a single container, but three properties
of this app point the other way:

| Constraint | On a VM | On Cloud Run |
|---|---|---|
| Cloud SQL at `192.168.4.7` (behind a VPN) | A host OpenVPN client serves every container | A VPN client per instance — impractical |
| `SESSION_DRIVER=file` | Works — one instance | Users logged out at random as requests hit different instances |
| Redis plan caps at **30 client connections** | One bounded php-fpm pool fits easily | Autoscaling multiplies connections past the cap |

A VM is also the closest thing to the current server, so there is less that
can behave differently in production than it did in testing. If the site
later outgrows one VM, the blockers to fix first are the session driver and
the Redis plan — not the container.

## Google Cloud Storage authentication

There is **no key file in production.** The VM's attached service account
supplies credentials through the metadata server (Application Default
Credentials), so no long-lived private key exists on the VM or in any image
layer.

This works without touching `Helper.php`. `generateSignedUrl()` runs:

```php
putenv("GOOGLE_APPLICATION_CREDENTIALS=" . env('GOOGLE_CLOUD_KEY_FILE'));
```

With `GOOGLE_CLOUD_KEY_FILE` unset that writes an *empty* value, which
`Google\Auth\CredentialsLoader::fromEnv()` treats as "not set" and falls
through to ADC. Verified against the pinned `google/auth v1.46.0`.

> **The one thing that will silently break every image on the site:**
> signing a URL without a private key goes through the IAM Credentials API,
> so the service account needs **`roles/iam.serviceAccountTokenCreator` on
> itself**. Without it `signedUrl()` throws, `generateSignedUrl()` catches
> the exception and returns `null`, and every image renders blank with
> nothing in the logs. Grant it (step 2) and verify it (step 8).

## What was built

| File | Purpose |
|---|---|
| `Dockerfile` | Multi-stage: `vendor` (composer install), `runtime` (nginx+php-fpm, what ships), `test` (adds phpunit, local only) |
| `docker/nginx.conf` | Serves `public/`, the Laravel front controller and the legacy `/book` CodeIgniter sub-app. Does **not** read `public/.htaccess` — nginx never does, same as `php artisan serve`. The `.htaccess` "Force HTTPS + WWW" rule is deliberately not reproduced; TLS terminates in front of the container |
| `docker/php.ini` | Mirrors the memory/upload/execution limits the old cPanel host set via `.htaccess` |
| `docker/www.conf` | php-fpm pool sized against the Redis 30-connection budget. `clear_env = no` here is load-bearing — see below |
| `docker/supervisord.conf` | Runs nginx + php-fpm in one container, both logging to stdout/stderr |
| `docker/entrypoint.sh` | Startup: clears stale `bootstrap/cache/*.php`, `package:discover`, checks GCS credentials, then `config:cache` + `view:cache` |
| `docker/dookwebsite.service` | systemd unit — starts the container at boot, ordered after the VPN tunnel |
| `docker/openvpn-client-override.conf` | Drop-in giving the tunnel `Restart=always` |
| `docker/vpn-healthcheck.sh` | Proves the database is reachable *through* the tunnel; restarts it if not |
| `docker/dookwebsite-vpn-healthcheck.{service,timer}` | Runs that check every minute |
| `docker/env.gcp.example` | Template for `/etc/dookwebsite/app.env` on the VM. Safe to commit; the filled-in copy is not |
| `docker-compose.yml` | Local Docker Desktop run: builds the image, publishes 8080, bind-mounts the GCS key |
| `docker-compose.prod.yml` | On the VM: builds locally, or pulls a tagged image when `IMAGE` is set; port 80, no key mounted, logs to Cloud Logging |
| `.dockerignore` | Keeps `.env`, credential JSON, `vendor/`, `node_modules/`, `.git` out of image layers |

### Two settings that look cosmetic and are not

**`route:cache` is deliberately absent from the entrypoint.** `routes/web.php`
queries the database to decide which controller `/{slug}` maps to. Route
caching serialises that answer once, and during an artisan command there is
no HTTP request — so `url()->current()` returns `APP_URL` and the fallback
branch wins, freezing `/{slug}` to `VisaController` for every request the
container serves. Verified:

```
$ php artisan route:cache && php artisan route:list --path="{slug}"
GET|HEAD  {slug} ... frontend.get_visa_details › Frontend\VisaController@getVisaDetails
```

That breaks all 324 slugs that should route elsewhere, and
`bootstrap/app.php` redirects every exception to the homepage, so it fails
as a silent 302. Do not add the line back until `/{slug}` is collapsed into
one dispatcher route. See `explainers/redis_performance_report.md` issue 11.

**`clear_env = no` in `docker/www.conf`.** The entrypoint runs
`config:cache`, after which Laravel stops reading `.env`. The bare `env()`
calls in `Helper.php` (`GOOGLE_CLOUD_STORAGE_BUCKET`,
`GOOGLE_CLOUD_KEY_FILE`) then resolve only from the worker's real process
environment. Setting `clear_env = yes` would strip them and every Cloud
Storage URL would come back `null`.

## Running it locally (Docker Desktop)

Locally there is no metadata server, so GCS still uses the bind-mounted JSON
key. Everything else matches production.

```bash
docker compose up --build -d
curl http://localhost:8080/up      # health check
curl http://localhost:8080/        # homepage
docker compose logs -f app
docker compose down
```

## Running the test suite

The shipped `runtime` image has no dev dependencies. A separate `test`
target adds them:

```bash
docker compose --profile test run --rm test php artisan test
```

---

# Deploying to Compute Engine

Replace `<PROJECT>`, `<REGION>`, `<ZONE>`, `<VPC>`, `<SUBNET>` throughout.
Use the region your Cloud SQL instance and Redis instance are in — a
cross-region hop would undo the query optimisation work.

### 1. Enable APIs

```bash
gcloud services enable compute.googleapis.com \
    artifactregistry.googleapis.com \
    iamcredentials.googleapis.com \
    logging.googleapis.com \
    --project <PROJECT>
```

`iamcredentials` is not optional — it is what signs Cloud Storage URLs.

### 2. Service account for the VM

```bash
gcloud iam service-accounts create dookwebsite-vm \
    --display-name "dookwebsite VM" --project <PROJECT>

SA=dookwebsite-vm@<PROJECT>.iam.gserviceaccount.com

# Read the bucket.
#
# Note the name. GOOGLE_CLOUD_STORAGE_BUCKET is set to "dooktravels/com",
# which is not a legal bucket name - a bucket cannot contain '/'. It works
# because the client concatenates it into the object path, so the real
# bucket is "dooktravels" and "com" is a top-level folder inside it.
# Confirmed by inspecting a generated signed URL:
#   https://storage.googleapis.com/dooktravels/com/poi/sample.jpg
# Bind IAM to gs://dooktravels, and leave the env value as it is.
gcloud storage buckets add-iam-policy-binding gs://dooktravels \
    --member "serviceAccount:$SA" --role roles/storage.objectViewer

# Sign URLs as itself  <-- without this, every image on the site is blank
gcloud iam service-accounts add-iam-policy-binding $SA \
    --member "serviceAccount:$SA" \
    --role roles/iam.serviceAccountTokenCreator --project <PROJECT>

# Pull images, write logs
gcloud projects add-iam-policy-binding <PROJECT> \
    --member "serviceAccount:$SA" --role roles/artifactregistry.reader
gcloud projects add-iam-policy-binding <PROJECT> \
    --member "serviceAccount:$SA" --role roles/logging.logWriter
```

### 3. Artifact Registry

```bash
gcloud artifacts repositories create dookwebsite \
    --repository-format docker --location <REGION> --project <PROJECT>
```

### 4. Build and push

Tag with something you can identify and roll back to. Never `:latest`.

```bash
TAG=$(date +%Y-%m-%d-%H%M)
IMAGE=<REGION>-docker.pkg.dev/<PROJECT>/dookwebsite/app:$TAG

gcloud auth configure-docker <REGION>-docker.pkg.dev

# --platform matters: Compute Engine is amd64. Building on an ARM Mac
# without this produces an image the VM cannot run.
docker build --platform linux/amd64 -t $IMAGE .
docker push $IMAGE
```

### 5. Create the VM

> **Superseded — see `explainers/gcp_hosting_guide.md` Phase 2.** The VPC
> choice below assumes a private route to Cloud SQL that does not exist.
> The VM needs no particular network, needs `--can-ip-forward`, and reaches
> the database over an OpenVPN tunnel it runs itself.

```bash
gcloud compute instances create dookwebsite \
    --project <PROJECT> --zone <ZONE> \
    --machine-type e2-standard-2 \
    --image-family debian-12 --image-project debian-cloud \
    --boot-disk-size 50GB --boot-disk-type pd-balanced \
    --network <VPC> --subnet <SUBNET> \
    --service-account dookwebsite-vm@<PROJECT>.iam.gserviceaccount.com \
    --scopes https://www.googleapis.com/auth/cloud-platform \
    --tags dookwebsite-http
```

`--scopes cloud-platform` is required for ADC to reach Storage and IAM;
the default scopes are too narrow.

### 6. Firewall

```bash
# Google Cloud Load Balancer + health check ranges only.
gcloud compute firewall-rules create allow-lb-to-dookwebsite \
    --network <VPC> --allow tcp:80 \
    --source-ranges 130.211.0.0/22,35.191.0.0/16 \
    --target-tags dookwebsite-http --project <PROJECT>
```

If you expose the VM directly instead of through a load balancer, use
`--source-ranges 0.0.0.0/0` — and terminate TLS on the VM (see below).

### 7. Set up the VM

```bash
gcloud compute ssh dookwebsite --zone <ZONE> --project <PROJECT>
```

```bash
# Docker Engine + compose plugin
curl -fsSL https://get.docker.com | sudo sh

sudo mkdir -p /opt/dookwebsite /etc/dookwebsite /var/lib/dookwebsite/storage-logs
sudo chown -R 33:33 /var/lib/dookwebsite/storage-logs   # www-data in the image

# Copy docker-compose.prod.yml, docker/dookwebsite.service and
# docker/env.gcp.example up from the repo (scp, git clone, or paste).

sudo cp docker/env.gcp.example /etc/dookwebsite/app.env
sudo chmod 600 /etc/dookwebsite/app.env
sudo nano /etc/dookwebsite/app.env          # fill in every REPLACE_ME

echo "IMAGE=<REGION>-docker.pkg.dev/<PROJECT>/dookwebsite/app:<TAG>" \
    | sudo tee /etc/dookwebsite/deploy.env

sudo cp docker-compose.prod.yml /opt/dookwebsite/
gcloud auth configure-docker <REGION>-docker.pkg.dev --quiet

sudo cp docker/dookwebsite.service /etc/systemd/system/
sudo systemctl daemon-reload
sudo systemctl enable --now dookwebsite
```

### 8. Verify — do not skip this

```bash
sudo systemctl status dookwebsite
docker compose -f /opt/dookwebsite/docker-compose.prod.yml logs -f app
```

The entrypoint prints which credential path it took. Expect:

```
[entrypoint] GCS auth: Application Default Credentials as dookwebsite-vm@<PROJECT>...
```

A `WARNING: no GOOGLE_CLOUD_KEY_FILE and no GCE metadata server reachable`
means images will not render.

```bash
curl -fsS http://localhost/up                      # 200

# Cloud SQL over the private IP
docker compose -f /opt/dookwebsite/docker-compose.prod.yml exec app \
    php artisan tinker --execute="echo DB::table('destinations')->count();"

# Redis
docker compose -f /opt/dookwebsite/docker-compose.prod.yml exec app \
    php artisan tinker --execute="Cache::put('probe',1,10); var_dump(Cache::get('probe'));"

# Signed URLs — this is the IAM role check. A URL means it works; null
# means the serviceAccountTokenCreator binding from step 2 is missing.
docker compose -f /opt/dookwebsite/docker-compose.prod.yml exec app \
    php artisan tinker --execute="var_dump(generateSignedUrl('poi/nonexistent.jpg'));"

# /{slug} must NOT be VisaController for a real slug
docker compose -f /opt/dookwebsite/docker-compose.prod.yml exec app \
    php artisan route:list --path="{slug}"
curl -sI http://localhost/adventure-tours | head -1   # expect 200
```

### 9. TLS

The container serves plain HTTP on port 80 by design. Two options:

**Google Cloud HTTP(S) Load Balancer (recommended)** — managed certificate,
health checks, and a static anycast IP. The VM keeps no external IP.
Laravel must be told to trust the proxy's `X-Forwarded-Proto`, otherwise
`url()`/`asset()` emit `http://` links on an `https://` page and browsers
block them as mixed content. That means setting `TrustProxies` to `'*'` (or
the LB ranges) in `bootstrap/app.php` — **an application change, not made
here**, since this work was scoped to the Docker setup. Verify it before
switching DNS.

**Caddy or nginx on the VM** — simpler, no LB cost, terminates TLS with
Let's Encrypt and proxies to `127.0.0.1:80`. Same `X-Forwarded-Proto`
caveat applies.

### 10. Redeploy and roll back

```bash
# Deploy
TAG=$(date +%Y-%m-%d-%H%M)
IMAGE=<REGION>-docker.pkg.dev/<PROJECT>/dookwebsite/app:$TAG
docker build --platform linux/amd64 -t $IMAGE . && docker push $IMAGE

# On the VM
echo "IMAGE=$IMAGE" | sudo tee /etc/dookwebsite/deploy.env
sudo systemctl restart dookwebsite

# Roll back: point deploy.env at the previous tag and restart.
```

The systemd unit only starts what is already present — it does not build or
pull, so a reboot never silently changes the running version. Deploying is a
deliberate `pull`/`build` followed by a restart.

### Database migrations

Two index migrations are part of the current work. Run them once, against
Cloud SQL, from the VM — not from the entrypoint, which would race across
restarts:

```bash
docker compose -f /opt/dookwebsite/docker-compose.prod.yml exec app \
    php artisan migrate --path=database/migrations/2026_08_22_000001_add_indexes_to_poi_lookup_columns.php --force
docker compose -f /opt/dookwebsite/docker-compose.prod.yml exec app \
    php artisan migrate --path=database/migrations/2026_08_22_000003_add_indexes_to_departure_card_columns.php --force
```

Migration `2026_08_22_000002` (dedupe + unique constraint) is deliberately
**not** applied — it will make the external importer's plain `INSERT`s fail.
Verify the importer uses `INSERT IGNORE`/upsert first.

`php artisan migrate` with no `--path` will fail: this database records the
pre-Laravel-11 migration filenames, so a bare run tries to recreate existing
tables.

---

## Verification status

**Tested locally on 2026-08-19** against the image built from this codebase:

- clean `docker compose build`; container reaches `healthy`
- `GET /up` → 200; `GET /` → 200 with real content (~250 KB) including a
  successfully generated GCS signed URL
- static assets → 200 with correct `Cache-Control`
- unknown route → single clean 302, not a redirect loop
- full `down` → `up --build` round trip, same results
- `docker compose --profile test run --rm test php artisan test` runs

**Verified on this machine for the GCP migration (2026-08-22):**

- empty `GOOGLE_APPLICATION_CREDENTIALS` falls through to ADC discovery
  (`CredentialsLoader::fromEnv()` returns `null`) — google/auth v1.46.0
- `GCECredentials` implements `SignBlobInterface`, so signing without a
  private key is supported by the pinned library
- `route:cache` freezes `/{slug}` to `VisaController` (run, observed, then
  cleared)

**Not verified — no GCP project access from this machine.** The `gcloud`
commands in steps 1–10 are written from the documented behaviour of those
services, not executed. Treat step 8 as the real acceptance test.

## Known gaps (pre-existing, not caused by containerization)

- **`tests/Feature/ExampleTest.php` fails under `php artisan test`.**
  `resources/views/frontend/layouts/script.blade.php` reads
  `$_SERVER['SERVER_NAME']` directly, which Laravel's test client does not
  populate. Reproduces on the host outside Docker entirely.
- **`/book` (legacy CodeIgniter sub-app) returns 500 in the container.** Its
  `app/Config/Database.php` ships with blank credentials and
  `hostname => 'localhost'`. It is not linked from `routes/web.php` and is
  configured separately in production. The container has everything it needs
  (the missing `intl` extension was added); the 500 is that sub-app's own
  absent database.
- **Every exception redirects to the homepage** (`bootstrap/app.php`), so
  failures show as 302s rather than 500s and never reach monitoring. This
  will hide deployment problems too — fix it early. See
  `explainers/redis_performance_report.md` issue 2.
