#!/bin/bash

#################################################################################################
#                                                                                               #
#                                       Update Script                                           #
#                  Complete the update process by copying files in the staging                  #
#                               directory to the app directory                                  #
#                                                                                               #
#################################################################################################

echo "Starting Update"

#  Update the database
echo "Waiting for Database to finish Startup"
sleep 15
php /app/artisan migrate --force

#  Cache configuration files
php /app/artisan optimize:clear
php /app/artisan breadcrumbs:cache
php /app/artisan optimize

# Install Composer and NPM dependencies
echo "Building Application"
cd /app
npm run build

# Store the version information in the keystore volume
echo $(php /app/artisan version --format=compact | sed -e 's/Tech Bench //g') > /app/keystore/version

echo 'Update Completed'
