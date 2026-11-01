################################################################################
#                                                                              #
#    Baseline Tech Bench build does not have Tech Bench App, only              #
#          basic install point in which to build app off of                    #
#        all versions of Tech Bench start with this OS build                   #
#                 scripts and app copied later                                 #
#                                                                              #
################################################################################

FROM mysql:9.4 AS mysql
FROM php:8.5.12-fpm

# Add the docker-php-extension-installer script
ADD --chmod=0755 \
    https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    /usr/local/bin/

################################################################################
# System Dependencies
################################################################################

RUN apt update && apt install -yq \
    curl \
    libncurses6 \
    libtinfo6 \
    nano \
    openssl \
    unzip \
    yq \
    zip

################################################################################
# PHP Extensions
################################################################################

RUN install-php-extensions \
    bcmath \
    exif \
    fileinfo \
    gd \
    gmp \
    intl \
    pcntl \
    pdo_mysql \
    zip

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

################################################################################
# Node.js and npm
################################################################################

RUN apt-get update && apt-get install -y --no-install-recommends \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Update Node.js and npm
RUN npm install -g n \
    && n stable \
    && hash -r \
    && npm install -g npm@latest

################################################################################
# Composer
################################################################################

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer self-update --2

################################################################################
# Healthcheck
################################################################################

HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD php artisan health:check

################################################################################
# MySQL Binaries
################################################################################
COPY --from=mysql /usr/bin/mysql /usr/bin/mysql
COPY --from=mysql /usr/bin/mysqldump /usr/bin/mysqldump
COPY --from=mysql /usr/bin/mysqladmin /usr/bin/mysqladmin

################################################################################
# Finalize Image
################################################################################
WORKDIR /var/www/html
