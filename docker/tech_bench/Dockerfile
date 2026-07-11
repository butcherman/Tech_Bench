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
RUN apk update --no-cache

# Copy the entrypoint script in to allow udpates and initial config
COPY ./entrypoint.sh /etc/entrypoint.d/entrypoint.sh
RUN chown root:www-data /etc/entrypoint.d/entrypoint.sh
RUN chmod 775 /etc/entrypoint.d/entrypoint.sh

# Bring the baseline scripts into the image
COPY ./download_tb.sh /tb_data/scripts/
COPY ./install_tb.sh /tb_data/scripts/
COPY ./setup_tb.sh /tb_data/scripts/
COPY ./update_tb.sh /tb_data/scripts/
RUN chmod 775 /tb_data/scripts/*
RUN chown www-data:www-data -R /tb_data

# Update npm and composer
RUN npm install -g npm@latest
RUN composer self-update

# Download application files
RUN /tb_data/scripts/download_tb.sh ${BRANCH} ${VERSION}
RUN /tb_data/scripts/install_tb.sh

# Set file and directory permissions
RUN chown -R root:www-data /var/www/html/*
RUN find /var/www/html/ -type f -exec chmod 644 {} \;
RUN find /var/www/html/ -type d -exec chmod 755 {} \;
RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/keystore

WORKDIR /var/www/html

USER www-data
