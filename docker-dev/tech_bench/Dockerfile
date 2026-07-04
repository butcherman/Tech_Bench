################################################################################
#                                                                              #
#              Tech Bench build for Dev env Tech Bench Application             #
#                                                                              #
################################################################################

FROM butcherman/tech_bench_baseline:2.0

ARG BRANCH=false
ARG VERSION=latest

# COPY ./php.ini /opt/bitnami/php/lib/
# COPY ./startup.dev.sh /scripts/
# COPY ./setup.dev.sh /scripts/

USER root

# Make sure image is up to date
RUN apt update && apt upgrade -y --no-install-recommends

# Bring the docker file in to allow for internal reboot
COPY ./docker /usr/local/bin/docker
RUN chmod 775 /usr/local/bin/docker

# Create a group and user to run the Docker file permissions
RUN addgroup --gid 999 docker
RUN usermod -a -G docker www-data

#################### DEV SPECIFIC ##############################################

#  Install troubleshooting php modules
RUN install-php-extensions x-debug

################################################################################

# Update npm and composer
RUN npm install -g npm@latest
RUN composer self-update

# Set directory permissions
RUN find /var/www/html/ -type f -exec chmod 644 {} \;
RUN find /var/www/html/ -type d -exec chmod 755 {} \;
RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/keystore

USER www-data
