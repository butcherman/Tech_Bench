################################################################################
#                                                                              #
#    Baseline Tech Bench build does not have Tech Bench App, only              #
#          basic install point in which to build app off of                    #
#        all versions of Tech Bench start with this OS build                   #
#                 scripts and app copied later                                 #
#                                                                              #
################################################################################

FROM mysql:9.4 AS mysql
FROM php:8.5-fpm

# Create the tbuser and tbgroup
RUN addgroup --gid 1024 tbgroup
RUN adduser --disabled-password --gecos "" --force-badname --ingroup tbgroup tbuser
RUN usermod -a -G www-data tbuser

# Add the docker-php-extension-installer script
ADD --chmod=0755 \
    https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    /usr/local/bin/

# Install dependency packages
RUN apt update && apt install -yq \
    curl \
    nano \
    nodejs \
    npm \
    openssl \
    unzip \
    yq \
    zip

# Install PHP Extensions
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

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Bring the docker file in to allow for internal reboot
COPY ./docker /usr/local/bin/docker
RUN chmod 775 /usr/local/bin/docker

# Create a group and user to run the Docker file permissions
RUN addgroup --gid 999 docker
RUN usermod -a -G docker tbuser

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Build Healthcheck
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD php artisan health:check

# Copy the official MySQL client binaries
COPY --from=mysql /usr/bin/mysql /usr/bin/mysql
COPY --from=mysql /usr/bin/mysqldump /usr/bin/mysqldump
COPY --from=mysql /usr/bin/mysqladmin /usr/bin/mysqladmin

# Change the owner of the /var/html folder to allow tbuser to write to it
RUN chown tbuser:www-data -R /var/www

# Finalize Image
USER tbuser
WORKDIR /var/www/html
