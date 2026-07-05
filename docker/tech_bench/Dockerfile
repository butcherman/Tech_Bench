################################################################################
#                                                                              #
#              Tech Bench build for main Tech Bench Application                #
#                                                                              #
################################################################################

FROM butcherman/tech_bench_baseline:2.0

ARG BRANCH=false
ARG VERSION=latest

USER root

# Make sure image is up to date
RUN apt update && apt upgrade -y --no-install-recommends

# Bring the docker file in to allow for internal reboot
COPY ./docker /usr/local/bin/docker
RUN chmod 775 /usr/local/bin/docker

# Create a group and user to run the Docker file permissions
RUN addgroup --gid 999 docker
RUN usermod -a -G docker www-data

# Update npm and composer
RUN npm install -g npm@latest
RUN composer self-update

# Download application files
RUN /tb_data/scripts/download_tb.sh ${BRANCH} ${VERSION}
RUN /tb_data/scripts/install.sh

# Set directory permissions
RUN chown -R www-data:www-data /var/www/html/*
RUN find /var/www/html/ -type f -exec chmod 644 {} \;
RUN find /var/www/html/ -type d -exec chmod 755 {} \;
RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/keystore

USER www-data
