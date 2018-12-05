#!/bin/bash
################################################################################
#                                                                              #
#  This bash script is for updating the Tech Bench on the web server.          #
#  The script will copy files from the staging directory, to live directory    #
#  followed by setting the proper file permissions to the correct folders.     #
#  A database update and depencency update is also performed                   #
#                                                                              #
#  Recommended upgrade process:                                                #
#  Copy all files into a staging directory - example: /home/%USER%/upgrade     #
#  Verify that the Production Directory is correct - default: /var/www/html    #
#  Navigate to the upgrade directory                                           #
#  Run the following command:  sudo ./upgrade.sh                               #
#                                                                              #
#  Note:  the script must be run as Sudo in order to properly set permissions  #
#                                                                              #
################################################################################

#  Pull in the variable file
STAGE_DIR="$(dirname "$(dirname "$(readlink -fm "$0")")")" 
source $STAGE_DIR/scripts/_config.sh

#  Verify the script is being run as root
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root" 
   exit 1
fi

echo 'Upgrade in progress.....'

#  Put the application into Maintenance mode
cd $PROD_DIR
php artisan down --message="Cool Things Are Happening Behind the Scenes - Check Back Soon"

# Copy the .env file into the staging directory to make sure all settings stay the same
cp $PROD_DIR/.env $STAGE_DIR

# Go back to the staging directory and prepare the site
cd $STAGE_DIR

#  Download all dependencies, cache and populate database
su -c "npm install --only=prod; composer install --optimize-autoloader --no-dev --no-script; php artisan migrate --force; php artisan version:refresh; php artisan version:absorb" $SUDO_USER

#  Copy files to web directory
rsync -av --delete-after --force --exclude='tests' --exclude='scripts' --exclude='webpack.mix.js' --exclude='composer.*' --exclude='.editorconfig' --exclude='.env.example' --exclude='.gi*' --exclude='.*.yml' --exclude="storage" $STAGE_DIR/ $PROD_DIR

#  Change the owner of the files to the web user and set permissions
chown -R $APUSR:$APUSR $PROD_DIR
chmod -R 755 $PROD_DIR

#  Change to the production directory and cache the settings
cd $PROD_DIR
#php artisan config:cache
#php artisan route:cache

#  Bring the application back online
php artisan up

tput setaf 4
echo '##################################################################'
echo '#                                                                #'
echo '#               The Tech Bench Has Been Updated!                 #'
echo '#                                                                #'
echo '##################################################################'
tput sgr0

exit 1
