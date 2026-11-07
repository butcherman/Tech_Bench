
################################################################################
# Tech Bench - Production PHP-FPM Image
################################################################################

# ------------------------------------------------------------------------------
# PHP base image
# ------------------------------------------------------------------------------

# MySQL client binaries
FROM mysql:9.4 AS mysql-client

# PHP base
FROM php:8.5-fpm-bookworm AS php-base

# Install OS Dependencies
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    libncurses6 \
    libtinfo6 \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extension installer
ADD --chmod=0755 \
    https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    /usr/local/bin/install-php-extensions

# Install required PHP extensions
RUN install-php-extensions \
    bcmath \
    exif \
    gd \
    gmp \
    intl \
    pcntl \
    pdo_mysql \
    sockets \
    zip

# Production PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Create Tech Bench user and group
RUN groupadd --gid 1024 tbgroup \
    && useradd --uid 1024 --gid tbgroup --create-home --shell /usr/sbin/nologin tbuser

# PHP-FPM configuration
COPY docker/tech_bench/www.conf /usr/local/etc/php-fpm.d/zz-tech-bench.conf

# Copy official MySQL 9.4 client binaries
COPY --from=mysql-client /usr/bin/mysql /usr/bin/mysql
COPY --from=mysql-client /usr/bin/mysqldump /usr/bin/mysqldump
COPY --from=mysql-client /usr/bin/mysqladmin /usr/bin/mysqladmin

# Validate the client binaries exist
RUN set -eux; \
    for binary in mysql mysqldump mysqladmin; do \
    missing="$(ldd "/usr/bin/${binary}" | grep 'not found' || true)"; \
    if [ -n "$missing" ]; then \
    echo "Missing libraries for ${binary}:"; \
    echo "$missing"; \
    exit 1; \
    fi; \
    done

# ------------------------------------------------------------------------------
# Composer dependencies
# ------------------------------------------------------------------------------

FROM php-base AS composer-build

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy Composer files first for better layer caching
COPY src/composer.json src/composer.lock ./

# Install production dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# Copy application source
COPY src/ ./

# Remove locally generated Laravel cache files
RUN rm -f bootstrap/cache/*.php

# Run only the safe Composer autoload discovery step
RUN composer dump-autoload \
    --no-dev \
    --optimize \
    --no-scripts \
    && php artisan package:discover --ansi

# ------------------------------------------------------------------------------
# Frontend build
# ------------------------------------------------------------------------------

FROM node:22-bookworm-slim AS frontend-build

WORKDIR /app

COPY src/package.json src/package-lock.json ./

RUN npm ci

COPY src/ ./

RUN npm run build

# ------------------------------------------------------------------------------
# Production image
# ------------------------------------------------------------------------------

FROM php-base AS production

WORKDIR /app

# Copy application source and Composer dependencies
COPY --from=composer-build --chown=tbuser:tbgroup /app/ /app/

# Copy compiled frontend assets
COPY --from=frontend-build --chown=tbuser:tbgroup /app/public/build/ /app/public/build/

# Create writable Laravel directories
RUN mkdir -p \
    /app/storage/app \
    /app/storage/framework/cache \
    /app/storage/framework/sessions \
    /app/storage/framework/views \
    /app/storage/logs \
    /app/bootstrap/cache \
    /app/keystore \
    && chown -R tbuser:tbgroup \
    /app/storage \
    /app/bootstrap/cache \
    /app/keystore \
    && chmod -R 775 \
    /app/storage \
    /app/bootstrap/cache \
    /app/keystore

# Application files are readable by NGINX and PHP-FPM
RUN find /app -type d -exec chmod 755 {} \; \
    && find /app -type f -exec chmod 644 {} \; \
    && chmod -R 775 /app/storage /app/bootstrap/cache /app/keystore

# Store the image version
RUN php artisan version --format=compact \
    | sed -e 's/Tech Bench //' > /app/version

# PHP-FPM runs as the configured service
EXPOSE 9000

# Runtime scripts
COPY docker/tech_bench/startup.sh /scripts/startup.sh
COPY docker/tech_bench/setup.sh /scripts/setup.sh

RUN chmod 755 \
    /scripts/startup.sh \
    /scripts/setup.sh

ENTRYPOINT ["/scripts/startup.sh"]
