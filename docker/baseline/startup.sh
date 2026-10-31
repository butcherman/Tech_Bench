#!/bin/bash

################################################################################
#                                                                              #
#                              Entrypoint Script                               #
#          If Tech Bench is not initialized, first time setup will occur       #
#                   After initialization, services will start                  #
#                                                                              #
################################################################################

RED='\033[0;31m'
NC='\033[0m'

SERVICE=${SERVICE:-master}

main()
{
    VER=$(php /app/artisan version --format=compact)
    APP_VERSION=${VER#Tech Bench }

    echo "Starting Tech Bench Version $APP_VERSION"

    # Primary application services
    if [ "$SERVICE" = "master" ] || [ "$SERVICE" = "app" ]
    then
        checkForUpdate
        checkForInit
    fi

    echo "Tech Bench $SERVICE is now running"

    if [ "$SERVICE" = "app" ]
    then
        echo "Importing Meilisearch Data"

        php artisan scout:sync-index-settings
        php artisan scout:import "App\Models\TechTip"
        php artisan scout:import "App\Models\Customer"

        exec php-fpm -F --pid /tmp/php-fpm.pid

    elif [ "$SERVICE" = "horizon" ]
    then
        exec php /app/artisan horizon

    elif [ "$SERVICE" = "scheduler" ]
    then
        exec /scripts/scheduler.sh

    elif [ "$SERVICE" = "reverb" ]
    then
        exec php /app/artisan reverb:start

    else
        exec php-fpm -F --pid /tmp/php-fpm.pid
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

    for ((i=${#ver1[@]}; i<${#ver2[@]}; i++))
    do
        ver1[i]=0
    done

    for ((i=0; i<${#ver1[@]}; i++))
    do
        if [[ -z ${ver2[i]} ]]
        then
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

checkForUpdate()
{
    STAGED_VERSION=$(head -n 1 /staging/version)

    versionCompare "$STAGED_VERSION" "$APP_VERSION"
    NEED_UPDATE=$?

    if [ "$NEED_UPDATE" == 1 ]
    then
        echo -e "${RED} New Version found. Please wait while we update ${NC}"
        /scripts/update.sh

    elif [ "$NEED_UPDATE" == 2 ]
    then
        echo -e "${RED} ERROR: VERSION MISMATCH"
        echo -e "${RED} TECH BENCH VERSION IS OLDER THAN DATABASE VERSION"
        echo -e "${RED} PLEASE UPGRADE TO VERSION $APP_VERSION OR HIGHER TO CONTINUE ${NC}"
        exit 1
    fi
}

checkForInit()
{
    if [ ! -f /app/keystore/version ]
    then
        /scripts/setup.sh
    fi
}

main
