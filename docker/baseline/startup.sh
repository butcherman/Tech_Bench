#!/bin/bash

################################################################################
#                                                                              #
#                              Entrypoint Script                               #
#          If Tech Bench is not initialized, first time setup will occur       #
#                   After initialization, services will start                  #
#                                                                              #
################################################################################

#  Color's for text
RED='\033[0;31m'
NC='\033[0m' # No Color

# set -m

# Primary Script
main()
{
    VER=$(php /app/artisan version --format=compact)
    APP_VERSION=${VER#Tech Bench }

    echo "Starting Tech Bench Version $APP_VERSION"

    # If this is the primary service, check for updates and new installation
    if [ $SERVICE = "master" ] || [ $SERVICE = "app" ]
    then
        checkForUpdate
        checkForInit
    fi

    # Start the proper services based on the containers Service Name
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
}

# Function to compare version numbers
versionCompare () {
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

# Check to see if a newer version of TB was recently loaded.
checkForUpdate()
{
    STAGED_VERSION=$(head -n 1 /staging/version)

    versionCompare $STAGED_VERSION $APP_VERSION
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
        exit 1 || return 1
    fi
}

# Check if this is a new installation of Tech Bench
checkForInit()
{
    if [ ! -f /app/keystore/version ]
    then
        /scripts/setup.sh
    fi
}

main
exit 0
