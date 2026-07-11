#!/bin/sh

################################################################################
#                                                                              #
#                              Install Script                                  #
#               Install the necessary files for Tech Bench and                 #
#                    place them in the correct location                        #
#                                                                              #
################################################################################

echo "Installing Tech Bench"

# Check to make sure that the staging directory exists and is populated
if [ ! -f "/tb_data/staging/version" ]
then
    bash /tb_data/scripts/download_tb.sh false latest
fi

# See if there are already files in the application directory
if [ -f "/var/www/html/version" ]
then
    echo "A version of Tech Bench is already installed."
    echo "Please run the Update script to update the current version."
    exit 0
fi

# Copy the application files to the application root directory
cp -R /tb_data/staging/* /var/www/html/

cd /var/www/html

# Add the .env file
if [ ! -f ".env" ]
then
    cp /tb_data/staging/.env.example .env
    chown www-data:www-data .env
fi

# Create the Keystore directory for Certificates
mkdir keystore
chown www-data:www-data keystore
chmod 775 keystore

echo "Tech Bench Installation Complete"
