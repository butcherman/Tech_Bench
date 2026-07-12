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
        syncScout
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

main
exit 0
