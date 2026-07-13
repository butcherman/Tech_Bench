#!/bin/bash

################################################################################
#                                                                              #
#                               Update Script                                  #
#          Complete the update process by copying files in the staging         #
#                       directory to the app directory                         #
#                                                                              #
################################################################################

echo "Starting Update"

#  Move the existing application files to a tmp directory that will be deleted later
mkdir /app/tmp
mv /app/app /app/tmp/
mv /app/config /app/tmp/
mv /app/database /app/tmp/
mv /app/resources /app/tmp/
mv /app/routes /app/tmp/

#  Copy all staged files to /app directory
cp -R /staging/* /app/

#  Delete the temporary directory
rm -rf /app/tmp/

#  Update and compile all dependencies
cd /app
composer install --no-dev --no-interaction --optimize-autoloader  --no-ansi >> /dev/null 2>&1
npm install >> /dev/null 2>&1

# Verify .env file is up to date
php /app/artisan app:validate-env --force

#  Update the database
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

# Resync Scout and restart child containers
php artisan scout:sync-index-settings
php artisan scout:import "App\Models\TechTip"
php artisan scout:import "App\Models\Customer"
php artisan app:reboot --force

echo 'Update Completed'
