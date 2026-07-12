################################################################################
#                                                                              #
#            NGINX Container is the main web service for Tech Bench            #
#                                                                              #
################################################################################

FROM nginx

# Make sure image is up to date
RUN apt update && apt upgrade -y --no-install-recommends

# Install OpenSSL so we can generate a Self Signed Certificate
RUN apt install openssl

# Copy configuration files
COPY ./default_http.conf /etc/nginx/conf.d/default.conf

# Copy the entrypoint script
COPY ./entrypoint.sh /docker-entrypoint.d/entrypoint.sh

#  All Tech Bench related data other than the app itself will be stored here
RUN mkdir /tb_data

# Basic scripts for maintaining NGINX Container
COPY ./generate_ssl.sh /tb_data/scripts/generate_ssl.sh

#  Set permissions for the scripts to allow them to run
RUN chmod 775 /tb_data/scripts/*

# Build Healthcheck
HEALTHCHECK  --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD curl --fail --silent http://localhost/healthcheck || exit 1

# Finalize Image
# USER www-data
WORKDIR /var/www/html
