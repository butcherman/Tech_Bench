################################################################################
#                                                                              #
#              Tech Bench build for main Tech Bench Application                #
#                                                                              #
################################################################################

FROM butcherman/tech_bench_baseline:2.0

ARG BRANCH=false
ARG VERSION=latest

ENV SERVICE=worker

USER root

# Copy the entrypoint script in to allow udpates and initial config
COPY ./entrypoint.sh /tb_data/entrypoint.d/entrypoint.sh
RUN chown tbuser:www-data /tb_data/entrypoint.d/entrypoint.sh
RUN chmod 775 /tb_data/entrypoint.d/entrypoint.sh

# Bring the baseline scripts into the image
COPY ./download_tb.sh /tb_data/scripts/
COPY ./install_tb.sh /tb_data/scripts/
COPY ./setup_tb.sh /tb_data/scripts/
COPY ./update_tb.sh /tb_data/scripts/
RUN chmod 775 /tb_data/scripts/*
RUN chown tbuser:www-data -R /tb_data

# Download application files
RUN /tb_data/scripts/download_tb.sh ${BRANCH} ${VERSION}
RUN /tb_data/scripts/install_tb.sh

# Set file and directory permissions
RUN chown -R tbuser:www-data /var/www/html/*
RUN find /var/www/html/ -type f -exec chmod 644 {} \;
RUN find /var/www/html/ -type d -exec chmod 755 {} \;
RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/keystore

# Finalize Image
WORKDIR /var/www/html
USER tbuser

# Set entrypoint
ENTRYPOINT [ "/tb_data/entrypoint.d/entrypoint.sh" ]
CMD ["php-fpm"]
