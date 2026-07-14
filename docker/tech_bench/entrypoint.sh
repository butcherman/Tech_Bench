#!/bin/bash

################################################################################
#                                                                              #
#                              Entrypoint Script                               #
#          If Tech Bench is not initialized, first time setup will occur       #
#                   After initialization, services will start                  #
#                                                                              #
################################################################################

# Color's for text
RED='\033[0;31m'
NC='\033[0m' # No Color

INSTALL_BASE="/var/www/html"

# Primary Script
main()
{
    echo "Starting Tech Bench"

    if [ $SERVICE = "master" ] || [ $SERVICE = "app" ]
    then
        checkForSetup
        checkForEnv
        checkForInit
        checkForUpdate
        syncScout
    fi
}

# Check that the Tech Bench files have been installed in the proper directory
checkForSetup()
{
    if [ ! -f $INSTALL_BASE/public/index.php ]
    then
        echo "${RED} ERROR:  TECH BENCH NOT INSTALLED ${NC}"
        echo "${RED} PLEASE USE PROPER DOCKER TECH BENCH IMAGE ${NC}"
        exit 1 || return 1
    fi
}

# Check that the .env file exists
checkForEnv()
{
    if [ ! -f $INSTALL_BASE/.env ]
    then
        echo 'Creating Environment File'
        cp $INSTALL_BASE/.env.example $INSTALL_BASE/.env
    fi
}

# Check if this is a new installation of Tech Bench
checkForInit()
{
    if [ ! -f $INSTALL_BASE/keystore/version ]
    then
        /tb_data/scripts/setup_tb.sh
    fi
}

# Check the staged version vs. the running version to see if update is needed
checkForUpdate()
{
    STAGED_VERSION=$(head -n 1 /tb_data/staging/version)
    APP_VERSION=$(head -n 1 /var/www/html/keystore/version)

    versionCompare $STAGED_VERSION $APP_VERSION
    NEED_UPDATE=$?

    if [ $NEED_UPDATE == 1 ]
    then
        echo -e "${RED} New Version $STAGED_VERSION found.  Please wait will we update ${NC}"
        /tb_data/scripts/update_tb.sh
    elif [ $NEED_UPDATE == 2 ]
    then
        echo -e "${RED} ERROR: VERSION MISMATCH"
        echo -e "${RED} RUNNING VERSION IS NEWER THAN STAGED VERSION"
        echo -e "${RED} PLEASE UPGRADE TO VERSION $APP_VERSION OR HIGHER TO CONTINUE ${NC}"
        exit 1 || return 1
    fi
}

# Sync Scout settings and database
syncScout()
{
    # Import all Scout data
    echo "Importing Meilisearch Data"
    php artisan scout:sync-index-settings
    php artisan scout:import "App\Models\TechTip"
    php artisan scout:import "App\Models\Customer"
}

# Function to compare version numbers
versionCompare () {
    # If version's match, no update is needed
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

    # Check each of the version fields and compare
    for ((i=0; i<${#ver1[@]}; i++))
    do
        if [[ -z ${ver2[i]} ]]
        then
            # fill empty fields in ver2 with zeros
            ver2[i]=0
        fi

        # A newer version is staged and ready to be deployed
        if ((10#${ver1[i]} > 10#${ver2[i]}))
        then
            return 1
        fi

        # The staged version is older than the running version do not update
        if ((10#${ver1[i]} < 10#${ver2[i]}))
        then
            return 2
        fi
    done
    return 0
}

main

exec "$@"
