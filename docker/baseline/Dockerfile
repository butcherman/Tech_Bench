################################################################################
#                                                                              #
#    Baseline Tech Bench build does not have Tech Bench App, only              #
#          basic install point in which to build app off of                    #
#        all versions of Tech Bench start with this OS build                   #
#                 scripts and app copied later                                 #
#                                                                              #
################################################################################

FROM php:8.5-fpm-alpine3.24

# Make sure image is up to date
RUN apk update --no-cache

# Add the docker-php-extension-installer script
ADD --chmod=0755 \
    https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    /usr/local/bin/

# Install dependency packages
RUN apk add \
    yq \
    curl \
    mysql-client \
    nano nodejs \
    npm \
    openssl \
    unzip \
    zip \
    --no-cache

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
RUN addgroup docker
RUN addgroup www-data docker

# Finalize Image
USER www-data
WORKDIR /var/www/html

