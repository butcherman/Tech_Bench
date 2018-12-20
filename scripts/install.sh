#!/bin/bash
################################################################################
#                                                                              #
#  This bash script is for the initial installation of the Tech Bench          #
#                                                                              #
#  Recommended install process:                                                #
#  Copy all files into a staging directory - example: /home/%USER%/Tech_Bench  #
#  Verify that the Production Directory is correct - default: /var/www/html    #
#  Navigate to the install/scripts directory                                   #
#  Run the following command:  sudo ./install.sh                               #
#                                                                              #
#  Note:  the script must be run as Sudo in order to properly set permissions  #
#                                                                              #
################################################################################

#  Pull in the variable file
STAGE_DIR="$(dirname "$(dirname "$(readlink -fm "$0")")")" 
source $STAGE_DIR/scripts/_config.sh

########################  Variables for this script  ###########################
LOGFILE=$LOGFILE/install.log
PREREQ=true
IPADDR=$(ifconfig  | grep -E 'inet.[0-9]' | grep -v '127.0.0.1' | awk '{ print $2}')
COUNT=1

#  Verify the script is being run as root
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root"  | tee $LOGFILE
   exit 1
fi

#  Start installation process
clear
tput setaf 4
echo '##################################################################' | tee $LOGFILE
echo '#                                                                #' | tee -a $LOGFILE
echo '#                 Welcome to the Tech Bench Setup                #' 
echo '#                   Tech Bench Installation Log                  #' >> $LOGFILE
echo '#                                                                #' | tee -a $LOGFILE
echo '##################################################################' | tee -a $LOGFILE
echo '' | tee -a $LOGFILE
tput sgr0
printf 'Checking Dependencies...\n\n' | tee -a $LOGFILE

#  Check Apache is installed and running
printf 'Apache                                                      ' | tee -a $LOGFILE
if systemctl is-active --quiet apache2; then
	tput setaf 2
	echo '[PASS]' | tee -a $LOGFILE
else	
	tput setaf 1
	echo '[FAIL]' | tee -a $LOGFILE
	PREREQ=false
fi
tput sgr0

#  Check if MySQL is installed and running
printf 'MySQL                                                       ' | tee -a $LOGFILE
if systemctl is-active --quiet mysql; then
	tput setaf 2
	echo '[PASS]' | tee -a $LOGFILE
else	
	tput setaf 1
	echo '[FAIL]' | tee -a $LOGFILE
	PREREQ=false
fi
tput sgr0

#  Check if PHP is installed and running the proper version
printf 'PHP 7                                                       ' | tee -a $LOGFILE
if hash php 2>/dev/null; then
	PHPVersion=$(php --version | head -n 1 | cut -d " " -f 2 | cut -c 1,3)
	minimumRequiredVersion=71;
	if (($PHPVersion >= $minimumRequiredVersion)); then
		tput setaf 2
		echo '[PASS]' | tee -a $LOGFILE
	else
		tput setaf 1
		echo '[FAIL]' | tee -a $LOGFILE
		PREREQ=false
	fi
else
	tput setaf 1
	echo '[FAIL]' | tee -a $LOGFILE
	PREREQ=false
fi
tput sgr0

#  Check if the Apache Rewrite Module is installed
REWRITE=$(apachectl -M | grep 'rewrite_module' > /dev/null 2>&1)
printf 'Rewrite Module                                              ' | tee -a $LOGFILE
if $REWRITE; then
	tput setaf 2
	echo '[PASS]' | tee -a $LOGFILE
else	
	tput setaf 1
	echo '[FAIL]' | tee -a $LOGFILE
	PREREQ=false
fi
tput sgr0

# Check if Composer is installed
printf 'Composer                                                    ' | tee -a $LOGFILE
composer -v > /dev/null 2>&1
COMPOSER=$?
if [[ $COMPOSER -ne 0 ]]; then
    tput setaf 1
	echo '[FAIL]' | tee -a $LOGFILE
	PREREQ=false
else
    tput setaf 2
	echo '[PASS]' | tee -a $LOGFILE
fi
tput sgr0

# Check if NodeJS is installed
printf 'NodeJS                                                      ' | tee -a $LOGFILE
npm -v > /dev/null 2>&1
NODE=$?
if [[ $NODE -ne 0 ]]; then
    tput setaf 1
	echo '[FAIL]' | tee -a $LOGFILE
	PREREQ=false
else
    tput setaf 2
	echo '[PASS]' | tee -a $LOGFILE
fi
tput sgr0

# Check if Unzip is installed
printf 'Unzip                                                       ' | tee -a $LOGFILE
unzip -v > /dev/null 2>&1
NODE=$?
if [[ $NODE -ne 0 ]]; then
    tput setaf 1
	echo '[FAIL]' | tee -a $LOGFILE
	PREREQ=false
else
    tput setaf 2
	echo '[PASS]' | tee -a $LOGFILE
fi
tput sgr0

#  Check if all prerequesits have passed or not.  If a prereq fails, exit script
if test $PREREQ = false; then
	printf '\n\nOne or more prerequesits has failed.\nPlease install the missing prerequesits and run this installer again.\n\n' | tee -a $LOGFILE
	exit 1
fi
printf '\nWe are good to go - lets move on...\n\n' | tee -a $LOGFILE

#  Verify the webroot directory
echo 'Tech Bench files will be copied to the Web Root directory'
echo 'Any files that are currently in this directory will be overwritten'
printf '\nFiles will be copied to: '
tput setaf 2
printf $PROD_DIR'\n\n'
tput sgr0
echo 'If this directory is not correct please exit the installer and modify '
echo 'the _config.sh file in the scripts folder'

#  Give user the chance to exit out and modify the installation folder
read -p 'Would you like to continue? [y/n]' CONT
if [[ ! $CONT =~ ^[Yy]$ ]]; then
	printf '\n\nExiting Installer\n\n'
	exit 1
fi

#  Get the full URL of the Tech Bench site
printf '\n\nPlease enter the full url that will be used for the Tech Bench: '
echo '(ex. https://techbench.domain.com)' 
read -p 'Enter URL [http://'$IPADDR']:  ' WEB_URL

#  Configure MySQL Database
printf '\n\nConfiguring MySQL Database\n'
echo 'We will create a user just for the Tech Bench '
echo 'Database, but we need admin access first.'
printf '\n\n'
read -p 'Enter the MySQL Admin Username [root]:' DBUSER
read -p 'Enter the MySQL Admin Password:' -s DBPASS

#  Default value if left empty
DBUSER=${DBUSER:-root}
WEBURL=${WEBURL:-http://$IPADDR}

#  New username just for the Tech Bench database
NEWUSER='tb_db_user'
NEWPASS=$(date | sha256sum | base64 | head -c 12)

#  Check if the Tech Bench database user already exists
while true; do
    USER_EXISTS=$(mysql -uroot -p -s -N -e "SELECT COUNT(*) FROM mysql.user WHERE user = '$NEWUSER';")

    if [ $USER_EXISTS = 0 ]; then
        break
    fi

    NEWUSER=$NEWUSER$COUNT
    COUNT=$[COUNT + 1]
done

#  Get the name of the database and make sure it is not an existing database
while true; do
	printf '\n'
	read -p 'Enter the Database name that we will use for the Tech Bench [tech-bench]:' DBNAME
	#  Default value if left empty
	DBNAME=${DBNAME:-tech-bench}
	
	#  Verify that the DB is not already created
	CHKDB=$(mysql -u$DBUSER -p$DBPASS -s -N -e "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='$DBNAME';") -v > /dev/null 2>&1
	if [ -z "$CHKDB" ]; then 
		break
	fi

	printf "\n\nThis Database already exists.  Please enter a database name that is not in use.\n"
done

#  Start the Spinner
echo 'Setting up Tech Bench files...'
spin & 
SPIN_PID=$!
trap "kill -9 $SPIN_PID" `seq 0 15`

#  Create the database and user
mysql -u$DBUSER -p$DBPASS > $LOGFILE 2>&1 <<MYSQL_SCRIPT
	CREATE DATABASE IF NOT EXISTS \`${DBNAME}\`;
	CREATE USER IF NOT EXISTS ${NEWUSER}@localhost IDENTIFIED BY '${NEWPASS}';
	GRANT ALL PRIVILEGES ON \`${DBNAME}\`.* TO '${NEWUSER}'@'localhost' WITH GRANT OPTION;
	GRANT SELECT ON INFORMATION_SCHEMA TO '${NEWUSER}'@'localhost';
	FLUSH PRIVILEGES;
MYSQL_SCRIPT

#  Create the Tech Bench and move to Web Root

echo 'Setting up .env file' >> $LOGFILE
cd $STAGE_DIR
#  Create the .env file
su -c "touch .env" $SUDO_USER
echo '#  The .env file contains configuration data specific to your environment.' 				 >  .env
echo '#  The settings can be changed as needed to match your environment' 						 >> .env
echo '#  Settings that have been commented out are settings that are not necessary, ' 			 >> .env
echo '#  but can be adjusted if necessary' 														 >> .env
echo '' 																						 >> .env
echo '#  VERY IMPORTANT:  In order for these settings to be applied, after saving the file, ' 	 >> .env
echo '#  from the Web Root directory, run the command:  'php artisan config:cache' ' 			 >> .env
echo '' 																						 >> .env
echo '#  USE CAUTION WHEN CHANGING SETTINGS ON A LIVE SYSTEM' 									 >> .env
echo '#  INCORRECT SETTINGS COULD CAUSE THE APPLICATION TO CRASH' 								 >> .env
echo '' 																						 >> .env
echo '#  Primary Application Settings' 															 >> .env
echo '#  Changing the APP_Key modifies how the user session data is encrypted.' 				 >> .env
echo '#  Modifying this setting could negitavely impact the user experience' 					 >> .env
echo 'APP_KEY=' 																				 >> .env
echo '#  For advanced troubleshooting, uncomment the APP_DEBUG line and set to true' 			 >> .env
echo '#  Setting this variable to true will cause error information to be printed to ' 			 >> .env
echo '#  the user on the web browser.' 															 >> .env
echo '#  For security purposes, only turn this option on if absolutly necessary.' 				 >> .env
echo '#  Be sure to turn it back off when troubleshooting is completed' 						 >> .env
echo '#  APP_DEBUG=true' 																		 >> .env
echo '#  The APP_URL is the url that is used for all hyperlinks both in the application ' 		 >> .env
echo '#  and in emails. ' 																		 >> .env
echo "APP_URL=\"$WEBURL\"" 											      					     >> .env
echo '' 																						 >> .env
echo '#  Database Connection Settings' 															 >> .env
echo '#  The Tech Bench uses these settings for all database queries' 							 >> .env
echo '#  Do not modify unless you are sure that the settings are correct and need to be changed' >> .env
echo '' 																						 >> .env
echo 'DB_CONNECTION=mysql' 																		 >> .env
echo 'DB_HOST=127.0.0.1' 																		 >> .env
echo 'DB_PORT=3306' 																			 >> .env
echo "DB_DATABASE=\"$DBNAME\"" 																	 >> .env
echo "DB_USERNAME=\"$NEWUSER\"" 																 >> .env
echo "DB_PASSWORD=\"$NEWPASS\"" 																 >> .env
echo '' 																						 >> .env
echo '#  By default application files are stored int he WebRoot/storage/app directory' 			 >> .env
echo '#  To change this location, uncomment the lines below and modify as needed' 				 >> .env
echo '#  Be sure to make the assigned folders writable by the web_root user' 					 >> .env
echo '' 																						 >> .env
echo '# ROOT_FOLDER="\path\to\doc\root"' 														 >> .env
echo '# DFLT_FOLDER="\default"' 																 >> .env
echo '# SYS_FOLDER="\systems"' 																	 >> .env
echo '# CUST_FOLDER="\customers"' 																 >> .env
echo '# USER_FOLDER="\users"' 																	 >> .env
echo '# TIP_FOLDER="\tips"' 																	 >> .env
echo '# LINK_FOLDER="\links"' 																	 >> .env
echo '# COMP_FOLDER="\company"' 																 >> .env
echo '# MAX_UPLOAD=2147483648' 																	 >> .env
echo ''                                                                                          >> .env

#  Download all dependencies, cache and populate database
echo 'Downloading Dependencies' | tee -a $LOGFILE
echo 'Running Command - npm install --only=prod' >> $LOGFILE
su -c "npm install --only=prod --no-progress" $SUDO_USER >> $LOGFILE
echo 'Running Command composer install --optimize-autoloader --no-dev' >> $LOGFILE
su -c "composer install --optimize-autoloader --no-dev" $SUDO_USER &>> $LOGFILE
echo 'Running Command - php artisan key:generate' >> $LOGFILE
su -c 'php artisan key:generate' $SUDO_USER &>> $LOGFILE
echo 'Setting up Database'
echo 'Running Command - php artisan migrate --force' >> $LOGFILE
su -c 'php artisan migrate --force' $SUDO_USER &>> $LOGFILE
echo 'Updating version' >> $LOGFILE
su -c 'php artisan version:refresh; php artisan version:absorb' $SUDO_USER &>> $LOGFILE

#  Copy files to web directory
echo 'Setting up Tech Bench Files'
echo 'Copying files to '$PROD_DIR >> $LOGFILE
rsync -av --delete-after --force --exclude='tests' --exclude='scripts' --exclude='webpack.mix.js' --exclude='composer.*' --exclude='.editorconfig' --exclude='.env.example' --exclude='.gi*' --exclude='.*.yml' $STAGE_DIR/ $PROD_DIR >> $LOGFILE

#  Change the owner of the files to the web user and set permissions
chown -R $APUSR:$APUSR $PROD_DIR >> $LOGFILE
chmod -R 755 $PROD_DIR >> $LOGFILE

#  Create the symbolic link for public storage
php artisan storage:link >> $LOGFILE

#  Change to the production directory and cache the settings
#cd $PROD_DIR
#php artisan config:cache >> $LOGFILE
#php artisan route:cache >> $LOGFILE

#  Log the final results
echo '##################################################################' >> $LOGFILE
echo '#                                                                #' >> $LOGFILE
echo '#                The Tech Bench Setup is Complete                #' >> $LOGFILE
echo '#                                                                #' >> $LOGFILE
echo '##################################################################' >> $LOGFILE
echo '' >> $LOGFILE
echo 'Please save the following information:' >> $LOGFILE
echo 'Website URL      - '$WEBURL >> $LOGFILE
echo 'Default Username - admin' >> $LOGFILE
echo 'Default Password - password' >> $LOGFILE
echo 'MySQL Database   - '$DBNAME >> $LOGFILE
echo 'MySQL Username   - '$NEWUSER >> $LOGFILE
echo 'MySQL Password   - '$NEWPASS >> $LOGFILE
echo 'The .env file contains the configuration information for this application.' >> $LOGFILE
echo 'Please make a backup of this file' >> $LOGFILE
echo '' >> $LOGFILE

#  Show the finished product
clear
tput setaf 4
echo '##################################################################'
echo '#                                                                #'
echo '#                 The Tech Bench is ready to go!                 #'
echo '#                                                                #'
echo '##################################################################'
tput sgr0
echo ''
echo 'Visit '$WEBURL' and log in with the default user name and password:'
echo ''
echo 'Username:  admin'
echo 'Password:  password'
echo ''
echo 'Post Install Instructions:'
echo ''
echo 'For security purposes it is highly recommended to change the Apache ' | tee -a $LOGFILE
echo 'conf file to point to '$PROD_DIR'/public.' | tee -a $LOGFILE
echo ''
echo 'More information can be found in the log file'

#  Copyt the log file so it can be viewed by the website
cp $LOGFILE $PROD_DIR/storage/logs/

exit 1
