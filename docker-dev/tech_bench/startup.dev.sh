#!/bin/bash

#################################################################################################
#                                                                                               #
#                              Entrypoint Script for Dev Environment                            #
#                  If Tech Bench is not initialized, first time setup will occur                #
#                           After initialization, services will start                           #
#                                                                                               #
#################################################################################################

set -m

echo "Starting Tech Bench Development Container"

if [ $SERVICE = "master" ] || [ $SERVICE = "app" ]
then
    # Do we need to run the first time setup script?
    if [ ! -f /app/keystore/version ]
    then
        /scripts/setup.dev.sh
    fi
fi

# Since the Reverb container has to start at the same time as Tech Bench, pause it
if [ $SERVICE = "reverb" ]
then
    sleep 30
fi

#  Start the Horizon and PHP-FPM Services and run the Scheduler script based on server purppose
echo "Tech Bench $SERVICE is now running"
if [ $SERVICE = "app" ]
then
    # Import all Scout data
    echo "Importing Meilisearch Data"
    php artisan scout:sync-index-settings
    php artisan scout:import "App\Models\TechTip"
    php artisan scout:import "App\Models\Customer"
    php-fpm -F --pid /opt/bitnami/php/tmp/php-fpm.pid -y /opt/bitnami/php/etc/php-fpm.conf
elif [ $SERVICE = "horizon" ]
then
    php /app/artisan horizon:watch --without-tty
elif [ $SERVICE = "scheduler" ]
then
    /scripts/scheduler.sh
elif [ $SERVICE = "reverb" ]
then
    php /app/artisan reverb:start --debug
else
    php-fpm -F --pid /opt/bitnami/php/tmp/php-fpm.pid -y /opt/bitnami/php/etc/php-fpm.conf
fi
