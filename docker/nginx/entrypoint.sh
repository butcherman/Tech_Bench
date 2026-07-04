#!/bin/sh

################################################################################
#                                                                              #
#                           Entrypoint Script                                  #
#     If there is no SSL Cert, create one before running Bitnami entrypoint    #
#                                                                              #
################################################################################

echo "Starting NGINX Setup Script"

# If the SSL file does not exist, create a self signed SSL cert
if [ ! -f "/app/keystore/server.crt" ]
then
    /tb_data/scripts/generate_ssl.sh
fi

