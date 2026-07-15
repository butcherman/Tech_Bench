#!/bin/bash

# Color's for text
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color

# Check for additional flags (Verbose)
VERBOSE=false
FORCE=false
while [[ $# -gt 0 ]]; do
    case "$1" in
        -v|--verbose)
            VERBOSE=true
            ;;
        -f|--force)
            FORCE=true
            ;;
        *)
            POSITIONAL_ARGS+=("$1")
    esac
    shift
done

set -- "${POSITIONAL_ARGS[@]}"

# Handle any errors that happen in the script
catchError()
{
    EXIT_CODE=$1
    ERROR_LINE=$2

    echo -e "${RED}${ERROR_MSG}${NC}" 1>&2
    run echo -e "${RED}Stopped on line ${ERROR_LINE}${NC}" 1>&2

    if declare -F handleError > /dev/null
    then
        handleError
    fi

    exit 1
}

# Determine if the output should be visible via the --verbose flag
run() {
    if $VERBOSE; then
        "$@"
    else
        "$@" > /dev/null
    fi
}

# Function to compare version numbers
versionCompare () {
    # If version's match, no update is needed
    if [[ $1 == $2 ]]
    then
        echo 0
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
            echo 1
            return 0
        fi

        # The staged version is older than the running version do not update
        if ((10#${ver1[i]} < 10#${ver2[i]}))
        then
            echo 2
            return 0
        fi
    done
    echo 0
    return 0
}

# Sync Scout settings and database
syncScout()
{
    # Import all Scout data
    echo "Importing Meilisearch Data"
    php $INSTALL_BASE/artisan scout:sync-index-settings
    php $INSTALL_BASE/artisan scout:import "App\Models\TechTip"
    php $INSTALL_BASE/artisan scout:import "App\Models\Customer"
}
