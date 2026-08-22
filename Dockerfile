# syntax=docker/dockerfile:1
#
# Single container: nginx + php-fpm (managed by supervisord), built to run
# on a Google Compute Engine VM inside the same VPC as Cloud SQL, and to be
# tested locally via Docker Desktop with docker-compose.yml.
#
# Why a VM rather than Cloud Run:
#   - Cloud SQL is reached over its private IP (192.168.4.7). A VM in the
#     VPC talks to it directly, with no connector or proxy in the path.
#   - SESSION_DRIVER=file works on a single instance. Under an autoscaled
#     service it would log users out at random as requests land on
#     different instances.
#   - The Redis plan allows 30 client connections in total. One VM with the
#     bounded php-fpm pool in docker/www.conf stays inside that budget;
#     autoscaling would not.
#
# No application source file is modified to produce this image - it only
# packages the existing codebase. Google Cloud Storage keeps working exactly
# as written in app/Http/Helper/Helper.php: on the VM the attached service
# account supplies Application Default Credentials, so GOOGLE_CLOUD_KEY_FILE
# is simply left unset and no key ever enters an image layer. See the notes
# in docker/entrypoint.sh.

# ---------------------------------------------------------------------
# Stage 1: install PHP dependencies with Composer (no dev packages)
# ---------------------------------------------------------------------
# --no-scripts is required: this app boots a DB query during Laravel's
# package-discovery bootstrap (a hostname->module lookup), and the
# composer:2 image has no pdo_mysql driver, so letting composer.json's
# post-autoload-dump hook run `artisan package:discover` here fails.
# Package discovery instead runs at container start in entrypoint.sh,
# once a real DB connection and driver are actually available.
FROM composer:2 AS vendor
WORKDIR /app
COPY . .
RUN composer install \
        --no-dev \
        --no-scripts \
        --optimize-autoloader \
        --classmap-authoritative \
        --ignore-platform-reqs

# ---------------------------------------------------------------------
# Stage 2: runtime image
# ---------------------------------------------------------------------
FROM php:8.2-fpm-bookworm AS runtime

RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
        curl \
        libzip-dev \
        libonig-dev \
        unzip \
    && curl -sSLf -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    && chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions pdo_mysql mysqli bcmath zip opcache pcntl intl \
    && apt-get purge -y --auto-remove libzip-dev libonig-dev \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

WORKDIR /var/www/html

COPY --from=vendor /app/vendor ./vendor
COPY . .

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini
# Replaces the base image's default pool: sizes the workers against the Redis
# plan's 30-client limit (see the comments in the file).
COPY docker/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/app/public \
        storage/app/private \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD curl -fsS http://127.0.0.1/up || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# ---------------------------------------------------------------------
# Stage 3: test image (adds dev deps + phpunit) - never pushed to Artifact
# Registry, only built locally by `docker compose run --rm test ...`
# ---------------------------------------------------------------------
FROM runtime AS test
COPY --from=vendor /usr/bin/composer /usr/bin/composer
RUN composer install --no-scripts --optimize-autoloader \
    && chown -R www-data:www-data storage bootstrap/cache
