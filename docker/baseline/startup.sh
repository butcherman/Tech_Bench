#!/bin/bash

#################################################################################################
#                                                                                               #
#                                      Entrypoint Script                                        #
#                  If Tech Bench is not initialized, first time setup will occur                #
#                           After initialization, services will start                           #
#                                                                                               #
#################################################################################################

#  Color's for text
RED='\033[0;31m'
NC='\033[0m' # No Color

#  Fuction to compare version numbers
vercomp () {
    if [[ $1 == $2 ]]
    then
        return 0
    fi
    local IFS=.
    local i ver1=($1) ver2=($2)
    # fill empty fields in ver1 with zeros
    for ((i=${#ver1[@]}; i<${#ver2[@]}; i++))
    do
        ver1[i]=0
    done
    for ((i=0; i<${#ver1[@]}; i++))
    do
        if [[ -z ${ver2[i]} ]]
        then
            # fill empty fields in ver2 with zeros
            ver2[i]=0
        fi
        if ((10#${ver1[i]} > 10#${ver2[i]}))
        then
            return 1
        fi
        if ((10#${ver1[i]} < 10#${ver2[i]}))
        then
            return 2
        fi
    done
    return 0
}

set -m

echo "Starting Tech Bench"

# If this is the primary container, perform additional Checks
if [ $SERVICE = "master" ] || [ $SERVICE = "app" ]
then
    # Do we need to run the first time setup script?
    if [ ! -f /app/keystore/version ]
    then
        /scripts/setup.sh
    else
        # Check if the version file is available in the /staging/config/ directory
        STAGED_VERSION=$(head -n 1 /staging/version)
        APP_VERSION=$(php /app/artisan version --format=compact | sed -e 's/Tech Bench //g')

        vercomp $STAGED_VERSION $APP_VERSION
        NEED_UPDATE=$?

        if [ $NEED_UPDATE == 1 ]
        then
            echo -e "${RED} New Version found.  Please wait will we update ${NC}"
            /scripts/update.sh
        elif [ $NEED_UPDATE == 2 ]
        then
            echo -e "${RED} ERROR: VERSION MISMATCH"
            echo -e "${RED} TECH BENCH VERSION IS OLDER THAN DATABASE VERSION"
            echo -e "${RED} PLEASE UPGRADE TO VERSION $APP_VERSION OR HIGHER TO CONTINUE ${NC}"
        fi
    fi

    sleep 15
fi

# If this is not the master service, pause for 30 seconds to allow all setup scripts to complete
if [ ! $SERVICE = "app" ]
then
    sleep 60
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
    php /app/artisan horizon
elif [ $SERVICE = "scheduler" ]
then
    /scripts/scheduler.sh
elif [ $SERVICE = "reverb" ]
then
    php /app/artisan reverb:start
else
    php-fpm -F --pid /opt/bitnami/php/tmp/php-fpm.pid -y /opt/bitnami/php/etc/php-fpm.conf
fi
