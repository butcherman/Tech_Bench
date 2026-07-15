#!/bin/bash

################################################################################
#                                                                              #
#                              Setup Script                                    #
#          If Tech Bench is not initialized, first time setup will occur       #
#                                                                              #
################################################################################

APP_ROOT=/app

source /scripts/_functions.sh

echo "New installation of Tech Bench detected"
echo "Setting up the application for the first time"
echo "Please wait...."

# Install Composer and NPM dependencies
run cd $APP_ROOT
run composer install --no-dev --no-interaction --optimize-autoloader
run npm install

#  Create Encryption Key
echo "Creating Encryption Key"
run php $APP_ROOT/artisan key:generate --force

# Generate new Reverb Credentials
echo "Generating Broadcasting Credentials"
run php $APP_ROOT/artisan reverb:generate --force

#  Create symbolic link for public directory
run php $APP_ROOT/artisan storage:link -q

#  Create the database
run php $APP_ROOT/artisan migrate --force

#  Cache configuration files
run php $APP_ROOT/artisan optimize:clear
run php $APP_ROOT/artisan breadcrumbs:cache
run php $APP_ROOT/artisan optimize

# Install Composer and NPM dependencies
echo "Building Application"
cd $APP_ROOT
run npm run build

# Store the version information in the keystore volume
echo $(php $APP_ROOT/artisan version --format=compact | sed -e 's/Tech Bench //g') > $APP_ROOT/keystore/version

echo "Tech Bench Setup Complete"
