#!/bin/bash

# Verify the script is being run as root
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root"
   exit 1
fi

echo '##########################################################################'
echo '#                                                                        #'
echo '#                        Install Tech Bench                              #'
echo '#                                                                        #'
echo '##########################################################################'

printf 'Preparing to Install Tech Bench'

# Get the full URL of the Tech Bench site
printf '\n\nPlease enter the full url that will be used for the Tech Bench: \n'
printf '(ex. techbench.domain.com)\n'
read -p "Enter URL [$WebURL]:  " WebURL
echo ''

# Check if Docker is installed
if [ ! -x "$(command -v docker)" ]; then
    echo "Docker is not installed on this server"
    echo "Please install Docker and try again"

    exit 0
fi

# Create the Docker Group
groupadd docker
usermod -aG docker $USER

# Download Docker Compose script and .env file
wget https://raw.githubusercontent.com/butcherman/Tech_Bench/master/docker-compose.yml
wget https://raw.githubusercontent.com/butcherman/Tech_Bench/master/.env

# Set permissions for downloaded files
chown $USER:docker docker-compose.yml
chown $USER:docker .env
chmod 755 docker-compose.yml
chmod 775 .env

echo $WebURL

# Write the base URL to the BASE_URL variable
sed -i "s/BASE_URL=/BASE_URL=$WebURL/g" .env

# Create the Backup Folder
mkdir backupData
chown $USER:docker backupData
chmod 775 backupData

# Start the Tech Bench
docker compose up -d

echo "Tech Bench is starting in the background"
echo "visit https://$WebURL to continue"
