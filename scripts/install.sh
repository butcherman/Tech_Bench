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
PREREQ=true

#  Verify the script is being run as root
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root" 
   exit 1
fi

#  Start installation process
clear
tput setaf 4
echo '##################################################################'
echo '#                                                                #'
echo '#                 Welcome to the Tech Bench Setup                #'
echo '#                                                                #'
echo '##################################################################'
echo ''
tput setaf 0
printf 'Checking Dependencies...\n\n'

#  Check Apache is installed and running
printf 'Apache                                                      '
if systemctl is-active --quiet apache2; then
	tput setaf 2
	echo '[PASS]'
else	
	tput setaf 1
	echo '[FAIL]'
	PREREQ=false
fi
tput setaf 0

#  Check if MySQL is installed and running
printf 'MySQL                                                       '
if systemctl is-active --quiet mysql; then
	tput setaf 2
	echo '[PASS]'
else	
	tput setaf 1
	echo '[FAIL]'
	PREREQ=false
fi
tput setaf 0

#  Check if PHP is installed and running the proper version
printf 'PHP 7                                                       '
if hash php 2>/dev/null; then
	PHPVersion=$(php --version | head -n 1 | cut -d " " -f 2 | cut -c 1,3)
	minimumRequiredVersion=71;
	if (($PHPVersion >= $minimumRequiredVersion)); then
		tput setaf 2
		echo '[PASS]'
	else
		tput setaf 1
		echo '[FAIL]'
		PREREQ=false
	fi
else
	tput setaf 1
	echo '[FAIL]'
	PREREQ=false
fi
tput setaf 0

#  Check if the Apache Rewrite Module is installed
printf 'Rewrite Module                                              '
if apachectl -M | grep 'rewrite_module' > /dev/null 2>&1; then
	tput setaf 2
	echo '[PASS]'
else	
	tput setaf 1
	echo '[FAIL]'
	PREREQ=false
fi
tput setaf 0

# Check if Composer is installed
printf 'Composer                                                    '
composer -v > /dev/null 2>&1
COMPOSER=$?
if [[ $COMPOSER -ne 0 ]]; then
    tput setaf 1
	echo '[FAIL]'
	PREREQ=false
else
    tput setaf 2
	echo '[PASS]'
fi
tput setaf 0

# Check if NodeJS is installed
printf 'NodeJS                                                      '
npm -v > /dev/null 2>&1
NODE=$?
if [[ $NODE -ne 0 ]]; then
    tput setaf 1
	echo '[FAIL]'
	PREREQ=false
else
    tput setaf 2
	echo '[PASS]'
fi
tput setaf 0

# Check if Unzip is installed
printf 'Unzip                                                       '
unzip -v > /dev/null 2>&1
NODE=$?
if [[ $NODE -ne 0 ]]; then
    tput setaf 1
	echo '[FAIL]'
	PREREQ=false
else
    tput setaf 2
	echo '[PASS]'
fi
tput setaf 0

#  Check if all prerequesits have passed or not.  If a prereq fails, exit script
if test $PREREQ = false; then
	printf '\n\nOne or more prerequesits has failed.\nPlease install the missing prerequesits and run this installer again.\n\n'
	exit 1
fi
printf '\nWe are good to go - lets move on...\n\n'

#  Verify the webroot directory
echo 'Tech Bench files will be copied to the Web Root directory'
echo 'Any files that are currently in this directory will be overwritten'
printf '\nFiles will be copied to: '
tput setaf 2
printf $PROD_DIR'\n\n'
tput setaf 0
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
read -p 'url:  ' WEB_URL

#  Configure MySQL Database
printf '\n\nConfiguring MySQL Database\n'
echo 'We will create a user just for the Tech Bench '
echo 'Database, but we need admin access first.'
printf '\n\n'
read -p 'Enter the MySQL Admin Username [root]:' DBUSER
read -p 'Enter the MySQL Admin Password:' -s DBPASS

#  Default value if left empty
DBUSER=${DBUSER:-root}

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

#  New username just for the Tech Bench database
NEWUSER='tb_db_user'
NEWPASS=$(date | sha256sum | base64 | head -c 12)
#  Create the database and user
mysql -u$DBUSER -p$DBPASS > /dev/null 2>&1 <<MYSQL_SCRIPT
	CREATE DATABASE IF NOT EXISTS \`${DBNAME}\`;
	CREATE USER IF NOT EXISTS ${NEWUSER}@localhost IDENTIFIED BY '${NEWPASS}';
	GRANT ALL PRIVILEGES ON \`${DBNAME}\`.* TO '${NEWUSER}'@'localhost' WITH GRANT OPTION;
	GRANT SELECT ON INFORMATION_SCHEMA TO '${NEWUSER}'@'localhost';
	FLUSH PRIVILEGES;
MYSQL_SCRIPT

#  Create the Tech Bench and move to Web Root
echo 'Setting up Tech Bench files...'

cd $STAGE_DIR
#  Create the .env file
su -c "touch .env" $SUDO_USER
echo "APP_KEY=" > .env
echo "APP_URL=\"$WEB_URL\"" >> .env
echo '' >> .env
echo 'TIMEZONE="America/Los_Angeles"' >> .env
echo '' >> .env
echo "DB_CONNECTION=mysql" >> .env
echo "DB_HOST=127.0.0.1" >> .env
echo "DB_PORT=3306" >> .env
echo "DB_DATABASE=\"$DBNAME\"" >> .env
echo "DB_USERNAME=\"$NEWUSER\"" >> .env
echo "DB_PASSWORD=\"$NEWPASS\"" >> .env
echo '' >> .env
echo '#DFLT_FOLDER="\default"' >> .env
echo '#SYS_FOLDER="\systems"' >> .env
echo '#CUST_FOLDER="\customers"' >> .env
echo '#USER_FOLDER="\users"' >> .env
echo '#TIP_FOLDER="\tips"' >> .env
echo '#LINK_FOLDER="\links"' >> .env
echo '#COMP_FOLDER="\company"' >> .env
echo '#MAX_UPLOAD=2147483648' >> .env
echo '' >> .env

#  Download all dependencies, cache and populate database
su -c "npm install --only=prod; composer install --optimize-autoloader --no-dev; php artisan migrate --force" $SUDO_USER

#  Copy files to web directory
cp -R $STAGE_DIR/* $PROD_DIR
#  Change the owner of the files to the web user and set permissions
chown -R www-data:www-data $PROD_DIR
chmod -R 755 $PROD_DIR
#  Copy the .env and .htaccess files
cp $STAGE_DIR/.env $PROD_DIR && cp $STAGE_DIR/.htaccess $PROD_DIR

#  Change to the production directory and cache the settings
source $STAGE_DIR/scripts/optimize.sh

#  Show the finished product
clear
tput setaf 4
echo '##################################################################'
echo '#                                                                #'
echo '#                 The Tech Bench is ready to go!                 #'
echo '#                                                                #'
echo '##################################################################'
echo ''
echo 'Visit '$WEB_URL' and log in with the default user name and password:'
echo ''
echo 'Username:  admin'
echo 'Password:  password'
echo ''
echo 'Post Install Instructions:'
echo ''
echo 'For security purposes it is highly recommended to change the Apache '
echo 'conf file to point to '$PROD_DIR'/public.'
tput setaf 0

exit 1
