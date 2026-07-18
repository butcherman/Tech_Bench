################################################################################
#                                                                              #
#    Baseline Tech Bench build does not have Tech Bench App, only              #
#          basic install point in which to build app off of                    #
#        all versions of Tech Bench start with this OS build                   #
#                 scripts and app copied later                                 #
#                                                                              #
################################################################################

FROM butcherman/tech_bench_baseline:1.0

ENV TB_URL=localhost
ENV ENABLE_HTTPS=true
ENV SERVICE=master

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
    openssl \
    unzip \
    yq \
    zip \
    mariadb-client

#  Entrypoint and update scripts along with any other necessary shell scripts will be copied here
RUN mkdir -p /scripts

#  Update npm and composer
RUN apt install -y nodejs npm
RUN npm install -g n
RUN n stable
RUN npm install -g npm@latest

#  Update Composer
RUN composer self-update --2

#  Basic scripts for Starting, Initializing and Updating Tech Bench App
COPY ./download_tb.sh /scripts/
COPY ./startup.sh /scripts/
COPY ./update.sh /scripts/
COPY ./setup.sh /scripts/
COPY ./scheduler.sh /scripts/
COPY ./_functions.sh /scripts/
COPY ./install.sh /scripts/

#  Set permissions for the scripts to allow them to run
RUN chmod 775 /scripts/*
RUN chmod 775 /app
RUN chmod 775 /opt/bitnami/php -R

# Create the staging directory
RUN mkdir /staging
RUN chmod 777 /staging

WORKDIR /app

ENTRYPOINT [ "/scripts/startup.sh" ]
