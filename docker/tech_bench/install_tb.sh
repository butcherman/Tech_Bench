#!/bin/bash

################################################################################
#                                                                              #
#                              Install Script                                  #
#               Install the necessary files for Tech Bench and                 #
#                    place them in the correct location                        #
#                                                                              #
################################################################################

source /tb_data/scripts/_functions.sh

echo "Installing Tech Bench Files"

# Check to make sure that the staging directory exists and is populated
if [ ! -f "/tb_data/staging/version" ]
then
    bash /tb_data/scripts/download_tb.sh false latest
fi

# See if there are already files in the application directory
if [ -f "/var/www/html/version" ]
then
    echo -e "${RED}A version of Tech Bench is already installed.${NC}" 1>&2
    echo -e "${RED}Please run the Update script to update the current version.${NC}" 1>&2
    exit 1
fi

# Copy the application files to the application root directory
run cp -R /tb_data/staging/* /var/www/html/

run cd /var/www/html

# Add the .env file
if [ ! -f ".env" ]
then
    run cp /tb_data/staging/.env.example .env
    run chown tbuser:www-data .env
fi

# Create the Keystore directory for Certificates
run mkdir keystore
run chown tbuser:www-data keystore
run chmod 775 keystore

echo -e "${GREEN}Tech Bench Installation Complete${NC}"
