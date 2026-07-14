#!/bin/bash

################################################################################
#                                                                              #
#                               Update Script                                  #
#          Complete the update process by copying files in the staging         #
#                       directory to the app directory                         #
#                                                                              #
################################################################################

set -eE

# Color's for text
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color

APP_ROOT=/var/www/html
TMP_ROOT=/tb_data/tmp

# Catch any errors and revert to previous version
catchError()
{
    EXIT_CODE=$1
    ERROR_LINE=$2

    echo -e "${RED} Error Found on line ${ERROR_LINE} - Reverting Update ${NC}" 1>&2

    cp -R $TMP_ROOT/* $APP_ROOT/
    buildDependencies

    rm -rf $TMP_ROOT

    exit 1
}

trap 'catchError $? $LINENO' ERR


main()
{
    echo "Starting Update"

    cd $APP_ROOT

    # checkForUpdate
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

    echo -e "${GREED}Update Successful${NC}"
}

# Make a backup of the existing app files in case of error
backupAppFiles()
{
    if test -d $TMP_ROOT
    then
        mkdir -p $TMP_ROOT
    fi

    cp -R $APP_ROOT/app $TMP_ROOT/
    cp -R $APP_ROOT/bootstrap $TMP_ROOT/
    cp -R $APP_ROOT/config $TMP_ROOT/
    cp -R $APP_ROOT/database $TMP_ROOT/
    cp -R $APP_ROOT/lang $TMP_ROOT/
    cp -R $APP_ROOT/resources $TMP_ROOT/
    cp -R $APP_ROOT/routes $TMP_ROOT/
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
exit 0
