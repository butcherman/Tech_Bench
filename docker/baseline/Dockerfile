################################################################################
#                                                                              #
#    Baseline Tech Bench build does not have Tech Bench App, only              #
#          basic install point in which to build app off of                    #
#        all versions of Tech Bench start with this OS build                   #
#                 scripts and app copied later                                 #
#                                                                              #
################################################################################

FROM serversideup/php:8.5-fpm

USER root

# Make sure image is up to date
RUN apt update && apt upgrade -y --no-install-recommends

#  Install php modules
RUN install-php-extensions gd intl

RUN apt update
RUN apt install -yq  \
    yq \
    nano \
    mariadb-client

# Bring the docker file in to allow for internal reboot
COPY ./docker /usr/local/bin/docker
RUN chmod 775 /usr/local/bin/docker

# Create a group and user to run the Docker file permissions
RUN addgroup --gid 999 docker
RUN usermod -a -G docker www-data

#  All Tech Bench related data other than the app itself will be stored here
RUN mkdir /tb_data

#  Update npm and composer
RUN apt install -y nodejs npm
RUN npm config delete globalignorefile --global

#  Update Composer
RUN composer self-update

# Copy the entrypoint script in to allow udpates and initial config
COPY ./entrypoint.sh /etc/entrypoint.d/entrypoint.sh

#  Basic scripts for Starting, Initializing and Updating Tech Bench App
RUN mkdir /tb_data/scripts

COPY ./download_tb.sh /tb_data/scripts/
COPY ./install.sh /tb_data/scripts/
COPY ./setup.sh /tb_data/scripts/
COPY ./update.sh /tb_data/scripts/

#  Set permissions for the scripts to allow them to run
RUN chown www-data:www-data -R /tb_data
RUN chmod 775 /tb_data/scripts/*

# Allow the composer cache file to be writable
RUN chmod 777 -R /composer/cache

USER www-data

WORKDIR /var/www/html
