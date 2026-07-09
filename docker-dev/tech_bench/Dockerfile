################################################################################
#                                                                              #
#              Tech Bench build for Dev env Tech Bench Application             #
#                                                                              #
################################################################################

FROM butcherman/tech_bench_baseline:2.0

ARG BRANCH=false
ARG VERSION=latest

USER root

# Copy the entrypoint dev script in to allow udpates and initial config
COPY ./entrypoint.dev.sh /etc/entrypoint.d/entrypoint.sh

# Copy other dev scripts
COPY ./setup.dev.sh /tb_data/scripts/setup.dev.sh
RUN chown www-data:www-data /tb_data/scripts/*
RUN chmod 775 /tb_data/scripts/*

# Make sure image is up to date
RUN apt update && apt upgrade -y --no-install-recommends

#################### DEV SPECIFIC ##############################################

# Install troubleshooting php modules
RUN install-php-extensions xdebug

################################################################################

WORKDIR /var/www/html

USER www-data
