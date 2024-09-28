# Installing Tech Bench

## Requirements

Tech Bench requires that Docker and Docker Compose are installed on the dedicated
server for the application.  For more information regarding installing and setting
up Docker, refer to the Docker website:  <https://www.docker.com/get-started/>

By default, Tech Bench is set to run only through HTTPS.  It is highly recommended
to upload a valid SSL Certificate to the application.

## Installation Instructions

Download the included docker-compose.yml and .env files to the desired root folder
of the application server.  To download the files using wget, enter the following
commands:

```bash
wget https://raw.githubusercontent.com/butcherman/Tech_Bench/dev7/docker-compose.yml
wget https://raw.githubusercontent.com/butcherman/Tech_Bench/dev7/.env
```

Read the .env file and modify any necessary fields.  The BASE_URL variable must
be set for the Tech Bench to work properly.

### Backups

In order to backup the Tech Bench to an off-server location, you will need to use
a package such as [Samba](https://www.samba.org/) to mount a network shared drive.
This package needs to be installed on the dedicated server.

Create a folder called **backupData** in the same directory as the Docker Compose
file, and make sure that it has write permissions.

```bash
mkdir backupData
chmod 775 backupData
```

Uncomment the following line the Docker Compose file.

```yaml
tech_bench:
        container_name: tech_bench
        restart: unless-stopped
        image: butcherman/tech_bench_app:${APP_VERSION:-latest}
        volumes:
            - appData:/app
            - ./.env:/app/.env
            - ./backupData:/app/storage/backups/tech-bench  # <-- Uncomment this line -->
```

All backups will be stored in this folder and can be mounted to any shared location.

### Running The Tech Bench

Run the command: ` docker-compose up -d ` to download, build and start the containers
to run the Tech Bench application.

Visit the website URL provided in the .env file.  The initial login will be:

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username: ***admin***

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password: ***password***

You will be forced to change this password on the first login.
