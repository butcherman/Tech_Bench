#!/bin/sh

################################################################################
#                                                                              #
#                           Entrypoint Script                                  #
#     If there is no SSL Cert, create one before running Bitnami entrypoint    #
#                                                                              #
################################################################################

echo "Starting NGINX"

# If the SSL file does not exist, create a self signed SSL cert
if [ ! -f "/var/www/html/keystore/server.crt" ]
then
    echo 'SSL Certificate Missing'
    /tb_data/scripts/generate_ssl.sh
fi
