#!/bin/bash

################################################################################
#                                                                              #
#                               Update Script                                  #
#          Complete the update process by copying files in the staging         #
#                       directory to the app directory                         #
#                                                                              #
################################################################################

set -eE

source /scripts/_functions.sh
trap 'catchError $? $LINENO' ERR

ERROR_MSG="Upgrade Failed"
APP_ROOT=/app
TMP_ROOT=/app/tmp
STAGE_ROOT=/staging

# Roll back the update attempt
handleError()
{
    echo -e "${RED}Rolling Back Update${NC}"

    # Move the backup files back into the primary folder
    cp -R $TMP_ROOT/* $APP_ROOT/
    buildDependencies
    buildCache
    cleanup

    echo -e "${RED}Update Failed - Run with -v flag to locate error${NC}"
}

#Update Tech Bench
main()
{
    echo "Starting Update"

    cd $APP_ROOT

    checkForUpdate
    backupAppFiles
    moveAppFiles

    echo "Building Dependencies"
    buildDependencies

    # Verify .env file is up to date
    echo "Validating Environment"
    run php $APP_ROOT/artisan app:validate-env --force

    buildCache

    # Update the database
    echo "Configuring Database"
    run php $APP_ROOT/artisan migrate --force

    # Store the version information in the keystore volume
    echo "Tidying up"
    echo $(php $APP_ROOT/artisan version --format=compact | sed -e 's/Tech Bench //g') > $APP_ROOT/keystore/version
    echo $(php $APP_ROOT/artisan version --format=compact | sed -e 's/Tech Bench //g') > $APP_ROOT/version

    # Resync Scout and restart child containers
    run php $APP_ROOT/artisan scout:sync-index-settings
    run php $APP_ROOT/artisan scout:import "App\Models\TechTip"
    run php $APP_ROOT/artisan scout:import "App\Models\Customer"

    # Cleanup after upgrade
    cleanup

    echo -e "${GREED}Update Successful${NC}"
}

# Check the staged version vs. the running version to see if update is needed
checkForUpdate()
{
    STAGED_VERSION=$(head -n 1 $STAGE_ROOT/version)
    APP_VERSION=$(head -n 1 $APP_ROOT/keystore/version)

    NEED_UPDATE=$(versionCompare $STAGED_VERSION $APP_VERSION)

    if [ $NEED_UPDATE == 1 ]
    then
        echo -e "${GREEN} New Version $STAGED_VERSION found.  Please wait will we update ${NC}"
    elif [ $NEED_UPDATE == 2 ]
    then
        echo -e "${RED} ERROR: VERSION MISMATCH ${NC}" 1>&2
        echo -e "${RED} RUNNING VERSION IS NEWER THAN STAGED VERSION ${NC}" 1>&2
        echo -e "${RED} PLEASE UPGRADE TO VERSION $APP_VERSION OR HIGHER TO CONTINUE ${NC}" 1>&2
        exit 1
    elif [ $NEED_UPDATE == 0 ] && [ $FORCE == false ]
    then
        echo "Running and Staged Versions are the same.  No update needed"
        exit 0
    fi
}

# Make a backup of the existing app files in case of error
backupAppFiles()
{
    if test -d $TMP_ROOT
    then
        run mkdir -p $TMP_ROOT
    fi

    run cp -R $APP_ROOT/app $TMP_ROOT/
    run cp -R $APP_ROOT/bootstrap $TMP_ROOT/
    run cp -R $APP_ROOT/config $TMP_ROOT/
    run cp -R $APP_ROOT/database $TMP_ROOT/
    run cp -R $APP_ROOT/lang $TMP_ROOT/
    run cp -R $APP_ROOT/resources $TMP_ROOT/
    run cp -R $APP_ROOT/routes $TMP_ROOT/
}

# Copy all of the staged files to the Application Directory
moveAppFiles()
{
    run cp -R $STAGE_ROOT/* $APP_ROOT/
}

# Update and compile Composer and NPM dependencies
buildDependencies()
{
    cd $APP_ROOT
    run composer install --no-dev --no-interaction --optimize-autoloader  --no-ansi
    run npm install
    run npm run build
}

# Build the system Cache and clear out other app cache
buildCache()
{
    # Cache configuration files
    echo "Optimizing Installation"
    run php $APP_ROOT/artisan cache:clear
    run php $APP_ROOT/artisan optimize:clear
    run php $APP_ROOT/artisan breadcrumbs:cache
    run php $APP_ROOT/artisan optimize
}

# Delete the temporary files
cleanup()
{
    run rm -rf $TMP_ROOT
}

main
exit 0
