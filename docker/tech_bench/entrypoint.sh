#!/bin/bash

################################################################################
#                                                                              #
#                              Entrypoint Script                               #
#          If Tech Bench is not initialized, first time setup will occur       #
#                   After initialization, services will start                  #
#                                                                              #
################################################################################

source /tb_data/scripts/_functions.sh

INSTALL_BASE="/var/www/html"

# Primary Script
main()
{
    echo "Starting Tech Bench"

    if [ $SERVICE == "master" ] || [ $SERVICE == "app" ]
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
        echo "${RED} ERROR:  TECH BENCH NOT INSTALLED ${NC}" 1>&2
        echo "${RED} PLEASE USE PROPER DOCKER TECH BENCH IMAGE ${NC}" 1>&2
        exit 1
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
        echo -e "${RED} ERROR: VERSION MISMATCH ${NC}" 1>&2
        echo -e "${RED} RUNNING VERSION IS NEWER THAN STAGED VERSION ${NC}" 1>&2
        echo -e "${RED} PLEASE UPGRADE TO VERSION $APP_VERSION OR HIGHER TO CONTINUE ${NC}" 1>&2
        exit 1
    fi
}

main

echo -e "${GREED}Tech Bench is now Running${NC}"

exec "$@"
