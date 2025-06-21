# Installing Tech Bench

## Requirements

Tech Bench requires that Docker and Docker Compose are installed on the dedicated
server for the application.  For more information regarding installing and setting
up Docker, refer to the Docker website:  <https://www.docker.com/get-started/>

By default, Tech Bench is set to run only through HTTPS.  It is highly recommended
to upload a valid SSL Certificate to the application.  This can be done once the
initial setup has been completed by navigating to Administration -> Security Settings.

## Using Setup Script

A bash setup script can be downloaded and ran to automatically configure the Tech
Bench.  Use the following commands to download and run the script.  You will be
asked to enter the URL of the Tech Bench when the script runs.  All other configuration
will be done from the browser after the Tech Bench has started.

```bash
# Download Script
wget https://raw.githubusercontent.com/butcherman/Tech_Bench/master/scripts/installTB.sh
# Make script executable
chmod +x installTB.sh
# Run the script as sudo
sudo ./installTB.sh
```

## Manual Installation Instructions

Download the included docker-compose.yml and .env files to the desired root folder
of the application server.  To download the files using wget, enter the following
commands:

```bash
wget https://raw.githubusercontent.com/butcherman/Tech_Bench/master/docker-compose.yml
wget https://raw.githubusercontent.com/butcherman/Tech_Bench/master/.env
```

Read the .env file and modify any necessary fields.  The BASE_URL variable must
be set for the Tech Bench to work properly.

Create a Docker Group and add the current user (along with any other needed users)
into this group.

```bash
sudo groupadd docker
sudo usermod -aG docker $USER
```

Create a backupData folder that all application backups will be stored in.  This
directory must be writable by the Docker Damon

```bash
mkdir backupData
chgrp docker backupData
chmod 775 backupData
```

### Running The Tech Bench

Run the command: ` docker-compose up -d ` to download, build and start the containers
to run the Tech Bench application.

Visit the website URL provided in the .env file.  The initial login will be:

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username: ***admin***

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password: ***password***

You will be forced to change this password on the first login.

### Accessing The Tech Bench

In order to access the Tech Bench from outside your firewall, you will need to
open ports in your firewall.  TCP Ports 80 and 443 need to be open for http and
https access. Note: All http traffic will be redirected to https traffic.
