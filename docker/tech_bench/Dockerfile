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

# Download application files
RUN /tb_data/scripts/download_tb.sh ${BRANCH} ${VERSION}
RUN /tb_data/scripts/install.sh

# Set directory permissions
RUN chown -R www-data:www-data /var/www/html/*
RUN find /var/www/html/ -type f -exec chmod 644 {} \;
RUN find /var/www/html/ -type d -exec chmod 755 {} \;
RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/keystore

WORKDIR /var/www/html

USER www-data
