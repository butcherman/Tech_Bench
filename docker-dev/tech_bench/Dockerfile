
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
    xdebug \
    zip

# Production PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy the .ini file for additional debug configuration
COPY docker-dev/tech_bench/php-dev.ini $PHP_INI_DIR/conf.d/php-dev.ini

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



# ------------------------------------------------------------------------------
# Production image
# ------------------------------------------------------------------------------

FROM php-base AS production

WORKDIR /app

# PHP-FPM runs as the configured service
EXPOSE 9000

# Runtime scripts
COPY docker-dev/tech_bench/startup.dev.sh /scripts/startup.sh
COPY docker-dev/tech_bench/setup.dev.sh /scripts/setup.sh

RUN chmod 755 \
    /scripts/startup.sh \
    /scripts/setup.sh

ENTRYPOINT ["/scripts/startup.sh"]
