#!/bin/bash

#################################################################################################
#                                                                                               #
#                              Entrypoint Script for Dev Environment                            #
#                  If Tech Bench is not initialized, first time setup will occur                #
#                           After initialization, services will start                           #
#                                                                                               #
#################################################################################################

set -m

echo "Starting Tech Bench"

if [ $SERVICE = "master" ] || [ $SERVICE = "app" ]
then
    #  If the .env file does not exist, run the setup script to create the database and configuration
    if [ ! -f "/app/.env" ]
    then
        /scripts/setup.dev.sh
    fi
fi

#  During startup process, the MySQL container runs a self update command
#  To allow this update to finish properly and not cause issues with TB Boot
#  process, we will pause the TB startup process for 45 seconds
sleep 45

#  Start the Horizon and PHP-FPM Services and run the Scheduler script based on server purppose
if [ $SERVICE = "app" ]
then
     php-fpm -F --pid /opt/bitnami/php/tmp/php-fpm.pid -y /opt/bitnami/php/etc/php-fpm.conf
elif [ $SERVICE = "horizon" ]
then
    php /app/artisan horizon
elif [ $SERVICE = "scheduler" ]
then
    /scripts/scheduler.sh
else
    php-fpm -F --pid /opt/bitnami/php/tmp/php-fpm.pid -y /opt/bitnami/php/etc/php-fpm.conf &
    /scripts/scheduler.sh &&
    fg
fi
