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

Run the command: ` docker-compose up -d ` to download, build and start the containers
and run the Tech Bench application.

Visit the website URL provided in the .env file.  The initial login will be:

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username: admin

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password: password

You will be forced to change this password on the first login.
