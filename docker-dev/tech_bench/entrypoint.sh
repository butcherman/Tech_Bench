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
        checkForMigration
        syncScout
    fi
}

# Check to see if the database is empty or not
checkForMigration()
{
    echo 'Checking if Migration is needed'

    # Check if the table exists by counting records in information_schema
    EXISTS=$(mysql -u"root" -p"$MYSQL_ROOT_PASSWORD" -e \
        "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = `tech-bench` AND table_name = 'app_settings';" \
        -sN 2>/dev/null)

    if [ ! $EXISTS ]
    then
        php /var/www/html/artisan migrate:fresh --seed
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
exit 0
