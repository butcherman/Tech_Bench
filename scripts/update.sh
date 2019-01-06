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
LOGFILE=$LOGFILE/update.log

#  Verify the script is being run as root
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root" | tee $LOGFILE
   exit 1
fi

printf 'Upgrade in progress.....' | tee $LOGFILE
spin & 
SPIN_PID=$!
trap "kill -9 $SPIN_PID" `seq 0 15`

#  Put the application into Maintenance mode
cd $PROD_DIR
php artisan down --message="Cool Things Are Happening Behind the Scenes - Check Back Soon" | tee -a $LOGFILE

# Copy the .env file into the staging directory to make sure all settings stay the same
cp $PROD_DIR/.env $STAGE_DIR

# Go back to the staging directory and prepare the site
cd $STAGE_DIR

#  Update all dependencies, cache and database
echo 'Downloading Dependencies' | tee -a $LOGFILE
echo 'Running Command - npm install --only=prod' >> $LOGFILE
su -c "npm install --only=prod --no-progress" $SUDO_USER >> $LOGFILE
echo 'Running Command composer install --optimize-autoloader --no-dev' >> $LOGFILE
su -c "composer install --optimize-autoloader --no-dev" $SUDO_USER &>> $LOGFILE
echo 'Updating Database'
echo 'Running Command - php artisan migrate --force' >> $LOGFILE
su -c 'php artisan migrate --force' $SUDO_USER &>> $LOGFILE
echo 'Updating version' >> $LOGFILE
su -c 'php artisan version:refresh; php artisan version:absorb' $SUDO_USER &>> $LOGFILE

#  Copy files to web directory
echo 'Setting up Tech Bench Files'
echo 'Copying files to '$PROD_DIR >> $LOGFILE
rsync -av --delete-after --force --exclude='tests' --exclude='scripts' --exclude='webpack.mix.js' --exclude='composer.*' --exclude='.editorconfig' --exclude='.env.example' --exclude='.gi*' --exclude='.*.yml' --exclude="storage/app" --exclude="storage/logs" $STAGE_DIR/ $PROD_DIR >> $LOGFILE

#  Change the owner of the files to the web user and set permissions
chown -R $APUSR:$APUSR $PROD_DIR
chmod -R 755 $PROD_DIR

#  Change to the production directory and cache the settings
cd $PROD_DIR
#php artisan config:cache
#php artisan route:cache

#  Bring the application back online
#  Create the symbolic link for public storage
php artisan storage:link >> $LOGFILE
php artisan up | tee -a $LOGFILE

tput setaf 4
echo '##################################################################' | tee -a $LOGFILE
echo '#                                                                #' | tee -a $LOGFILE
echo '#               The Tech Bench Has Been Updated!                 #' | tee -a $LOGFILE
echo '#                                                                #' | tee -a $LOGFILE
echo '##################################################################' | tee -a $LOGFILE
tput sgr0

#  Copyt the log file so it can be viewed by the website
cp $LOGFILE $PROD_DIR/storage/logs/

exit 1
