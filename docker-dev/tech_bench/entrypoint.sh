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
        checkForInit
        # syncScout
    fi
}

# Check to see if the database is empty or not
checkForInit()
{
    echo 'Checking if Tech Bench is Initialized'

    if [ ! -f $INSTALL_BASE/keystore/version ]
    then
        php /var/www/html/artisan migrate:fresh --seed
        echo $(php /var/www/html/artisan version --format=compact | sed -e 's/Tech Bench //g') > /var/www/html/keystore/version
    fi
}

# Sync Scout settings and database
syncScout()
{
    # Import all Scout data
    echo "Importing Meilisearch Data"
    php /var/www/html/artisan scout:sync-index-settings
    php /var/www/html/artisan scout:import "App\Models\TechTip"
    php /var/www/html/artisan scout:import "App\Models\Customer"
}

main

exec "$@"
