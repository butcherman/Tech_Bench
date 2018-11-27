#!/bin/bash
################################################################################
#                                                                              #
#  This bash script is for updating the Tech Bench on the web server.          #
#  The script will copy files from the staging directory, to live directory    #
#  followed by setting the proper file permissions to the correct folders.     #
#                                                                              #
#  Recommended upgrade process:                                                #
#  Copy all files into a staging directory - example: /home/%USER%/upgrade     #
#  Verify that the Production Directory is correct - default: /var/www/html    #
#  Navigate to the upgrade directory                                           #
#  Run the following command:  scripts/upgrade.sh                              #
#                                                                              #
#  Note:  the script must be run as Sudo in order to properly set permissions  #
#                                                                              #
################################################################################

#  Pull in the variable file
source _config.sh

#  Verify the script is being run as root
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root" 
   exit 1
fi

echo 'Upgrade in progress.....'

#  Put the application into Maintenance mode
cd $PROD_DIR
php artisan down --message="Cool Things Are Happening Behind the Scenes - Check Back Soon"

#  Copy files to web directory
cp -R $STAGE_DIR/* $PROD_DIR
#  Change the owner of the files to the web user
chown -R www-data:www-data $PROD_DIR
#  Allow write permissions to the 'storage' directory
chmod -R 777 $PROD_DIR/storage

# Make sure that all dependencies are up to date
composer update

#  Upgrade the database if necessary
php artisan migrate

#  Bring the application back online
php artisan up

echo 'Done'

#  More stuff to come.....
