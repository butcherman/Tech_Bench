#!/bin/bash

################################################################################
#                                                                              #
#                               Update Script                                  #
#          Complete the update process by copying files in the staging         #
#                       directory to the app directory                         #
#                                                                              #
################################################################################

echo "Starting Update"

# Check to make sure that the staging directory exists and is populated
if [ ! -f "/tb_data/staging/version" ]
then
    bash /tb_data/scripts/download_tb.sh false latest
fi

#  Move the existing application files to a tmp directory
mkdir -p /tb_data/tmp/old_version
mv /var/www/html/app /tb_data/tmp/old_version/
mv /var/www/html/config /tb_data/tmp/old_version/
mv /var/www/html/database /tb_data/tmp/old_version/
mv /var/www/html/resources /tb_data/tmp/old_version/
mv /var/www/html/routes /tb_data/tmp/old_version/

# Copy the application files to the application root directory
cp -R /tb_data/staging/* /var/www/html/

#  Delete the temporary directory
# rm -rf /app/tmp/

cd /var/www/html

#  Update and compile all dependencies
composer install --no-dev --no-interaction --optimize-autoloader  --no-ansi
npm install

# Verify .env file is up to date
php artisan app:validate-env --force

#  Update the database
php artisan migrate --force

#  Cache configuration files
php artisan optimize:clear
php artisan breadcrumbs:cache
php artisan optimize

# Install Composer and NPM dependencies
echo "Building Application"
npm run build

# Store the version information in the keystore volume
echo $(php /var/www/html/artisan version --format=compact | sed -e 's/Tech Bench //g') > /var/www/html/keystore/version

# Resync Scout and restart child containers
php artisan scout:sync-index-settings
php artisan scout:import "App\Models\TechTip"
php artisan scout:import "App\Models\Customer"
php artisan app:reboot --force

echo 'Update Completed'
