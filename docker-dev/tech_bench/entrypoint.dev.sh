#!/bin/sh

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

        echo "Importing Meilisearch Data"
        php artisan scout:sync-index-settings
        php artisan scout:import "App\Models\TechTip"
        php artisan scout:import "App\Models\Customer"
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
        mkdir -p $INSTALL_BASE/keystore
        /tb_data/scripts/setup.dev.sh
    fi
}

main
exit 0
