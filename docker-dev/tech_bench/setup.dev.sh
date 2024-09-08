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

#  Create new Pusher Credentials
PUSHER_ID=`openssl rand -base64 8`
PUSHER_KEY=`openssl rand -base64 10`
PUSHER_SECRET=`openssl rand -base64 12`
sed -i "s/app-id/$PUSHER_ID/g" /app/.env
sed -i "s/app-key/$PUSHER_KEY/g" /app/.env
sed -i "s/app-secret/$PUSHER_SECRET/g" /app/.env

#  Create Encryption Key
echo "Creating Encryption Key"
php /app/artisan key:generate --force

#  Create symbolic link for public directory
php /app/artisan storage:link -q

#  Create the database
echo "Waiting for Database to finish Startup"
sleep 30
php /app/artisan migrate --force


# Store the version information in the keystore volume
echo $(php /app/artisan version --format=compact | sed -e 's/Tech Bench //g') > /app/keystore/version

echo "Tech Bench Setup Complete"
