################################################################################
#                                                                              #
#    Baseline Tech Bench build does not have Tech Bench App, only              #
#          basic install point in which to build app off of                    #
#        all versions of Tech Bench start with this OS build                   #
#                 scripts and app copied later                                 #
#                                                                              #
################################################################################

FROM serversideup/php:8.5-fpm

# ENV TB_URL=localhost
# ENV ENABLE_HTTPS=true
# ENV SERVICE=master

# # Make sure image is up to date
USER root

RUN apt update && apt upgrade -y --no-install-recommends

#  Install php modules
RUN install-php-extensions gd

RUN apt update

RUN apt install -yq  \
    nano \
    mariadb-client

#  All Tech Bench related data other than the app itself will be stored here
RUN mkdir /tb_data
RUN chown www-data:www-data /tb_data
RUN chmod 770 /tb_data

#  Update npm and composer
RUN apt install -y nodejs npm
RUN npm install -g npm@latest

USER www-data

#  Update Composer
RUN composer self-update

#  Basic scripts for Starting, Initializing and Updating Tech Bench App
RUN mkdir /tb_data/scripts

# TODO - Test each script
COPY ./download_tb.sh /scripts/
COPY ./startup.sh /tb_data/scripts/
COPY ./update.sh /tb_data/scripts/
COPY ./setup.sh /tb_data/scripts/
COPY ./scheduler.sh /tb_data/scripts/

#  Set permissions for the scripts to allow them to run
RUN chmod 775 /tb_data/scripts/*
# RUN chmod 775 /app
# RUN chmod 775 /opt/bitnami/php -R



# WORKDIR /app

# ENTRYPOINT [ "/scripts/startup.sh" ]
