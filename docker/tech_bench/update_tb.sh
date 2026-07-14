#!/bin/bash

################################################################################
#                                                                              #
#                               Update Script                                  #
#          Complete the update process by copying files in the staging         #
#                       directory to the app directory                         #
#                                                                              #
################################################################################

set -eE

APP_ROOT=/var/www/html
TMP_ROOT=/tb_data/tmp

# Catch any errors and revert to previous version
catchError()
{
    EXIT_CODE=$1
    ERROR_LINE=$2

    echo "Error Found - Reverting Update"

    mv $TMP_ROOT/* $APP_ROOT/
    buildDependencies

    exit 1
}

trap 'catchError $? $LINENO' ERR


main()
{
    echo "Starting Update"

    cd $APP_ROOT

    backupAppFiles
    moveAppFiles

    echo "Building Dependencies"
    buildDependencies

    # Verify .env file is up to date
    echo "Validating Environment"
    php $APP_ROOT/artisan app:validate-env --force

    buildCache

    # Update the database
    echo "Configuring Database"
    php $APP_ROOT/artisan migrate --force

    # Store the version information in the keystore volume
    echo "Tidying up"
    echo $(php $APP_ROOT/artisan version --format=compact | sed -e 's/Tech Bench //g') > $APP_ROOT/keystore/version
    echo $(php $APP_ROOT/artisan version --format=compact | sed -e 's/Tech Bench //g') > $APP_ROOT/version

    # Cleanup by remove the tmp files
    rm -rf $TMP_ROOT

    # Resync Scout and restart child containers
    php $APP_ROOT/artisan scout:sync-index-settings
    php $APP_ROOT/artisan scout:import "App\Models\TechTip"
    php $APP_ROOT/artisan scout:import "App\Models\Customer"
    # php $APP_ROOT/artisan app:reboot --force
}

# Make a backup of the existing app files in case of error
backupAppFiles()
{
    if [ ! -d $TMP_ROOT ]
    then
        mkdir -p $TMP_ROOT
    fi

    mv $APP_ROOT/app $TMP_ROOT/
    mv $APP_ROOT/bootstrap $TMP_ROOT/
    mv $APP_ROOT/config $TMP_ROOT/
    mv $APP_ROOT/database $TMP_ROOT/
    mv $APP_ROOT/lang $TMP_ROOT/
    mv $APP_ROOT/resources $TMP_ROOT/
    mv $APP_ROOT/routes $TMP_ROOT/
}

# Copy all of the staged files to the Application Directory
moveAppFiles()
{
    cp -R /tb_data/staging/* $APP_ROOT/
}

# Update and compile Composer and NPM dependencies
buildDependencies()
{
    cd $APP_ROOT
    composer install --no-dev --no-interaction --optimize-autoloader  --no-ansi >> /dev/null 2>&1
    npm install >> /dev/null 2>&1
    npm run build
}

# Build the system Cache and clear out other app cache
buildCache()
{
    # Cache configuration files
    echo "Optimizing Installation"
    php $APP_ROOT/artisan cache:clear
    php $APP_ROOT/artisan optimize:clear
    php $APP_ROOT/artisan breadcrumbs:cache
    php $APP_ROOT/artisan optimize
}

main
exit 0
