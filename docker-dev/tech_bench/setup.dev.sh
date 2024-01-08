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

# Install Composer and NPM dependencies
echo "Installing Dependencies"
composer install
npm install

#  Create .env file
cp /app/.env.example /app/.env
#  Add App URL to the .env file
sed -i "s/localhost/$TB_URL/g" /app/.env

#  Create Encryption Key
echo "Creating Encryption Key"
php /app/artisan key:generate --force

#  Create symbolic link for public directory
php /app/artisan storage:link -q

#  Create the database
php /app/artisan migrate --force
