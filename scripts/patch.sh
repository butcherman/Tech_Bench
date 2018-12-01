#!/bin/bash
################################################################################
#                                                                              #
#  This bash script is for updating the Tech Bench on the web server.          #
#  The script will copy files from the staging directory, to live directory    #
#  followed by setting the proper file permissions to the correct folders.     #
#  Dependencies are not updated and the database is not updated.               #
#                                                                              #
#  This file is only for performing minor patch updates to the Tech Bench      #
#                                                                              #
#  Recommended upgrade process:                                                #
#  Copy all files into a staging directory - example: /home/%USER%/upgrade     #
#  Verify that the Production Directory is correct - default: /var/www/html    #
#  Navigate to the upgrade directory                                           #
#  Run the following command:  sudo ./patch.sh                                 #
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

echo 'System Patch in progress.....'

#  Put the application into Maintenance mode
cd $PROD_DIR
php artisan down --message="Cool Things Are Happening Behind the Scenes - Check Back Soon"

# Go back to the staging directory and prepare the site
cd $STAGE_DIR

#  Download all dependencies, cache and populate database
su -c "php artisan version:absorb" $SUDO_USER

#  Copy files to web directory
cp -R $STAGE_DIR/* $PROD_DIR
#  Change the owner of the files to the web user
chown -R www-data:www-data $PROD_DIR
#  Allow write permissions to the 'storage' directory
chmod -R 777 $PROD_DIR/storage

#  Change to the production directory and bring the application back online
cd $PROD_DIR
php artisan up

tput setaf 4
echo '##################################################################'
echo '#                                                                #'
echo '#               The Tech Bench Has Been Patched!                 #'
echo '#                                                                #'
echo '##################################################################'
tput sgr0

exit 1
