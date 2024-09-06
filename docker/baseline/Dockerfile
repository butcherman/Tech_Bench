#################################################################################################
#                                                                                               #
#            Baseline Tech Bench build does not have Tech Bench App, only                       #
#                  basic install point in which to build app off of                             #
#                all versions of Tech Bench start with this OS build                            #
#                         scripts and app copied later                                          #
#                                                                                               #
#################################################################################################

FROM bitnami/php-fpm:8.3

ENV TB_URL=localhost
ENV ENABLE_HTTPS=true
ENV SERVICE=master

WORKDIR /tmp

#  Entrypoint and update scripts along with any other necessary shell scripts will be copied here
RUN mkdir /scripts

#  Basic scripts for Starting, Initializing and Updating Tech Bench App
COPY ./download_tb.sh /scripts/
COPY ./entrypoint.sh /scripts/
COPY ./update.sh /scripts/
COPY ./setup.sh /scripts/
COPY ./scheduler.sh /scripts/

#  Set permissions for the scripts to allow them to run
RUN chmod 775 /scripts/*
RUN chmod 775 /opt/bitnami/php -R

RUN apt update && apt upgrade -y --no-install-recommends

#  Install php modules
RUN apt install -yq php-common \
        php-curl \
        php-dom \
        php-gd \
        php-json \
        php-mbstring \
        php-mysql \
        php-pdo \
        php-redis \
        php-xml \
        php-zip \
        curl \
        nano \
        openssl \
        unzip \
        zip \
        mariadb-client

#  Update npm and composer
RUN apt install -y nodejs npm
RUN npm install -g n
RUN n stable
RUN npm install -g npm@latest

#  Update Composer
RUN composer self-update --2

WORKDIR /app

ENTRYPOINT [ "/scripts/entrypoint.sh" ]
