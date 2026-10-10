#!/bin/bash

################################################################################
#                                                                              #
#                              Setup Script                                    #
#          If Tech Bench is not initialized, first time setup will occur       #
#                                                                              #
################################################################################

source /tb_data/scripts/_functions.sh

echo "New installation of Tech Bench detected"
echo "Setting up the application for the first time"
echo "Please wait...."

# Install Composer and NPM dependencies
run cd /var/www/html
run composer install --no-dev --no-interaction --optimize-autoloader
run npm install

#  Create Encryption Key
echo "Creating Encryption Key"
run php /var/www/html/artisan key:generate --force

# Generate new Reverb Credentials
echo "Generating Broadcasting Credentials"
run php /var/www/html/artisan reverb:generate --force

#  Create symbolic link for public directory
run php /var/www/html/artisan storage:link -q

#  Create the database
run php /var/www/html/artisan migrate --force

#  Cache configuration files
run php /var/www/html/artisan optimize:clear
run php /var/www/html/artisan breadcrumbs:cache
run php /var/www/html/artisan optimize

# Install Composer and NPM dependencies
echo "Building Application"
cd /var/www/html
run npm run build

# Store the version information in the keystore volume
echo $(php /var/www/html/artisan version --format=compact | sed -e 's/Tech Bench //g') > /var/www/html/keystore/version

echo "Tech Bench Setup Complete"
