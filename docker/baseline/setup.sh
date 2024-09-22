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

# Pause to allow other containers to start
sleep 30

#  Create Encryption Key
echo "Creating Encryption Key"
php /app/artisan key:generate --force

# Generate new Reverb Credentials
echo "Generating Broadcasting Credentials"
php /app/artisan reverb:generate --force

#  Create symbolic link for public directory
php /app/artisan storage:link -q

#  Create the database
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

echo "Tech Bench Setup Complete"
