#!/usr/bin/env bash
# Container startup: prepare the Laravel app, then hand off to whatever
# CMD was given (supervisord, running nginx + php-fpm).
set -euo pipefail

cd /var/www/html

if [ ! -f vendor/autoload.php ]; then
    echo "[entrypoint] ERROR: vendor/autoload.php is missing from the image." >&2
    exit 1
fi

if [ -z "${APP_KEY:-}" ]; then
    echo "[entrypoint] ERROR: APP_KEY is not set in the container environment." >&2
    exit 1
fi

# bootstrap/cache/*.php (packages.php, services.php, config.php, ...) is
# part of the existing repo and gets copied in as-is from whatever the
# host last ran `artisan` with (e.g. dev packages like Debugbar, which
# this --no-dev image doesn't have). Laravel loads these caches
# unconditionally if present, so a stale one crashes every artisan
# command including the ones below - clear them before anything else,
# they're all safely regenerated afterwards.
echo "[entrypoint] Clearing stale bootstrap caches copied in from the host..."
rm -f bootstrap/cache/*.php

echo "[entrypoint] Linking storage (idempotent)..."
if [ ! -e public/storage ]; then
    php artisan storage:link
fi

# Package discovery was skipped during the Composer build stage (the
# build image has no DB driver, and this app queries the DB during
# bootstrap) - do it now that a real DB connection is available.
echo "[entrypoint] Discovering packages..."
php artisan package:discover --ansi

# --- Google Cloud Storage credentials ---------------------------------
# On Compute Engine the VM's attached service account supplies credentials
# automatically through the metadata server (Application Default
# Credentials), so no key file ships in the image.
#
# generateSignedUrl() in app/Http/Helper/Helper.php runs
#   putenv("GOOGLE_APPLICATION_CREDENTIALS=" . env('GOOGLE_CLOUD_KEY_FILE'))
# on every call. With GOOGLE_CLOUD_KEY_FILE unset that writes an *empty*
# value, which Google\Auth\CredentialsLoader::fromEnv() treats as "not set"
# and falls through to ADC - verified against google/auth v1.46.0. So the
# application code needs no change; just leave GOOGLE_CLOUD_KEY_FILE unset.
#
# Signing a URL without a private key goes through the IAM Credentials API
# (GCECredentials implements SignBlobInterface). The service account must
# hold roles/iam.serviceAccountTokenCreator ON ITSELF or every call returns
# null and images silently disappear from the site - generateSignedUrl()
# swallows the exception. Warn loudly at boot rather than at render time.
if [ -n "${GOOGLE_CLOUD_KEY_FILE:-}" ]; then
    if [ -r "${GOOGLE_CLOUD_KEY_FILE}" ]; then
        echo "[entrypoint] GCS auth: key file at ${GOOGLE_CLOUD_KEY_FILE}"
    else
        echo "[entrypoint] WARNING: GOOGLE_CLOUD_KEY_FILE is set to '${GOOGLE_CLOUD_KEY_FILE}' but that path is not readable." >&2
        echo "[entrypoint]          Signed URLs will fail and images will not render." >&2
    fi
elif curl -sf -m 2 -H 'Metadata-Flavor: Google' \
        http://metadata.google.internal/computeMetadata/v1/instance/service-accounts/default/email \
        >/tmp/gcs-sa-email 2>/dev/null; then
    echo "[entrypoint] GCS auth: Application Default Credentials as $(cat /tmp/gcs-sa-email)"
    echo "[entrypoint]           (that account needs roles/iam.serviceAccountTokenCreator on itself to sign URLs)"
else
    echo "[entrypoint] WARNING: no GOOGLE_CLOUD_KEY_FILE and no GCE metadata server reachable." >&2
    echo "[entrypoint]          Google Cloud Storage signed URLs will return null - images will not render." >&2
fi

# --- Framework caches --------------------------------------------------
# Rebuilt on every start rather than baked into the image, because config
# values only exist as real container env vars at runtime (docker-compose
# env_file) - baking them in at build time would freeze in empty values.
#
# config:cache is safe here ONLY because those values arrive as genuine
# process environment variables and docker/www.conf sets `clear_env = no`.
# Once config is cached Laravel stops reading .env, so the bare env() calls
# in app/Http/Helper/Helper.php (GOOGLE_CLOUD_STORAGE_BUCKET,
# GOOGLE_CLOUD_KEY_FILE) resolve from the process environment or not at all.
echo "[entrypoint] Caching config/views..."
php artisan config:clear >/dev/null
php artisan config:cache
php artisan view:cache

# NOTE: `php artisan route:cache` is deliberately NOT run here.
#
# routes/web.php decides what routes to register by querying the database:
#
#     $slugmaster = SlugMaster::where('slug_name', basename(url()->current()))
#                       ->pluck('module_name')->toArray();
#
# and then points /{slug} at CountryController, ExperienceController,
# RegionController or DestinationController depending on the answer.
#
# Route caching serialises the route table exactly once. During an artisan
# command there is no HTTP request, so url()->current() returns APP_URL, its
# basename matches no slug, and the final `else` branch wins - freezing
# /{slug} to VisaController@getVisaDetails for every request the container
# ever serves. Verified: `route:cache && route:list --path="{slug}"` prints
#     GET|HEAD {slug} ... Frontend\VisaController@getVisaDetails
# That breaks all 324 slugs that should route elsewhere, and because
# bootstrap/app.php redirects every Throwable to the homepage it fails
# silently, as a 302.
#
# Re-enable this line only after /{slug} is collapsed into a single route
# backed by a dispatcher controller. See explainers/redis_performance_report.md
# issue 11.

echo "[entrypoint] Ready. Starting: $*"
exec "$@"
