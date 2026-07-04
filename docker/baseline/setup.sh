#!/bin/bash

################################################################################
#                                                                              #
#                              Setup Script                                    #
#          If Tech Bench is not initialized, first time setup will occur       #
#                                                                              #
################################################################################

echo "New installation of Tech Bench detected"
echo "Setting up the application for the first time"
echo "Please wait...."

#  Create Encryption Key
echo "Creating Encryption Key"
php /var/www/html/artisan key:generate --force

# Generate new Reverb Credentials
echo "Generating Broadcasting Credentials"
php /var/www/html/artisan reverb:generate --force

#  Create symbolic link for public directory
php /var/www/html/artisan storage:link -q

#  Create the database
php /apvar/www/htmlp/artisan migrate --force

#  Cache configuration files
php /var/www/html/artisan optimize:clear
php /var/www/html/artisan breadcrumbs:cache
php /var/www/html/artisan optimize

# Install Composer and NPM dependencies
echo "Building Application"
cd /var/www/html
npm run build

# Store the version information in the keystore volume
echo $(php /var/www/html/artisan version --format=compact | sed -e 's/Tech Bench //g') > /var/www/html/keystore/version

echo "Tech Bench Setup Complete"
