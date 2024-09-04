#!/bin/bash

#################################################################################################
#                                                                                               #
#                                      Setup Script                                             #
#                  If Tech Bench is not initialized, first time setup will occur                #
#                                                                                               #
#################################################################################################

echo "New installation of Tech Bench detected"
echo "Setting up the application for the first time"
echo "Please wait...."

#  Create .env file
cp /app/.env.example /app/.env
#  Add App URL to the .env file
sed -i "s/localhost/$TB_URL/g" /app/.env
#  Create new Pusher Credentials
PUSHER_ID=`openssl rand -base64 8`
PUSHER_KEY=`openssl rand -base64 10`
PUSHER_SECRET=`openssl rand -base64 12`
sed -i "s/app-id/$PUSHER_ID/g" /app/.env
sed -i "s/app-key/$PUSHER_KEY/g" /app/.env
sed -i "s/app-secret/$PUSHER_SECRET/g" /app/.env

#  If the SSL file does not exist, create a self signed SSL cert
if [ ! -f "/app/keystore/server.crt" ]
then
    #  Generate self signed SSL Certificate
    echo "Creating Self Signed SSL Certificate"
    openssl rand -base64 48 > /tmp/passphrase.txt
    openssl genrsa -aes128 -passout file:/tmp/passphrase.txt -out /tmp/server.key 2048
    openssl req -new -passin file:/tmp/passphrase.txt -key /tmp/server.key -out /tmp/server.csr -subj "/C=FR/O=tb/OU=Domain Control Validated/CN=$TB_URL"       #  FIXME - copy host name from .env file
    cp /tmp/server.key /tmp/server.key.org
    openssl rsa -in /tmp/server.key.org -passin file:/tmp/passphrase.txt -out /tmp/server.key
    openssl x509 -req -days 36500 -in /tmp/server.csr -signkey /tmp/server.key -out /tmp/server.crt

    #  Move the new certificate and key to the Tech Bench directory
    mkdir -p /app/keystore/private
    mv /tmp/server.crt /app/keystore/server.crt
    mv /tmp/server.key /app/keystore/private/server.key
    chmod 755 -R /app/keystore

    #  Cleanup unneeded files
    rm -rf /tmp/server.csr /tmp/server.key.org /tmp/passphrase.txt
fi

# Install Composer and NPM dependencies
echo "Installing Dependencies"
cd /app
composer install
npm install
npm run build

#  Create Encryption Key
echo "Creating Encryption Key"
php /app/artisan key:generate --force

#  Create symbolic link for public directory
php /app/artisan storage:link -q

#  Create the database
php /app/artisan migrate --force

#  Cache configuration files
php /app/artisan breadcrumbs:cache
php /app/artisan optimize
