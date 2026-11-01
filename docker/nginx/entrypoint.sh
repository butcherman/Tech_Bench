#!/bin/bash

set -e

#################################################################################################
#                                                                                               #
#                                      Entrypoint Script                                        #
#                         Tech Bench NGINX Initialization                                      #
#                                                                                               #
#################################################################################################

echo "Starting Tech Bench NGINX Setup"

# Determine application URL
APP_URL="${APP_URL:-${TB_URL:-localhost}}"

# Create required directories
mkdir -p /app/keystore/private
mkdir -p /app/storage/logs/nginx

# Generate a self-signed SSL certificate if one does not exist
if [ ! -f "/app/keystore/server.crt" ] || [ ! -f "/app/keystore/private/server.key" ]
then
    echo "Generating self-signed SSL certificate for ${APP_URL}"

    openssl req -x509 \
        -nodes \
        -days 36500 \
        -newkey rsa:2048 \
        -keyout /app/keystore/private/server.key \
        -out /app/keystore/server.crt \
        -subj "/C=US/O=Tech Bench/CN=${APP_URL}"

    chmod 600 /app/keystore/private/server.key
    chmod 644 /app/keystore/server.crt

    echo "SSL certificate generated successfully"
else
    echo "Existing SSL certificate found"
fi

# Verify NGINX configuration
echo "Testing NGINX configuration"

nginx -t

echo "Starting NGINX"

exec "$@"
