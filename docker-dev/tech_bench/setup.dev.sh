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

# Install Dependencies
composer install
npm install

#  Create Encryption Key
echo "Creating Encryption Key"
php /app/artisan key:generate --force

# Generate new Reverb Credentials
echo "Generating Broadcasting Credentials"
php /app/artisan reverb:generate --force

#  Create symbolic link for public directory
php /app/artisan storage:link -q

# #  Create the database
echo "Waiting for Database to finish Startup"
sleep 30
php /app/artisan migrate --force


# # Store the version information in the keystore volume
echo $(php /app/artisan version --format=compact | sed -e 's/Tech Bench //g') > /app/keystore/version

echo "Tech Bench Setup Complete"
