#!/bin/bash

################################################################################
#                                                                              #
#                              Download Script                                 #
#         Download the Tech Bench application and stage for install            #
#                                                                              #
################################################################################

set -eE

# Variables
BRANCH="${1:-false}"           # If this argument is populated, the branch identified will be downloaded and installed
VERSION="${2:-latest}"          # If BRANCH is left to false, this version will be downloaded.  Default value is "latest"

# Color's for text
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color

STAGE_ROOT=/tb_data/staging
TMP_ROOT=/tb_data/tmp
DNLD_ROOT=$TMP_ROOT/downloads

# Catch any errors and revert to previous version
catchError()
{
    EXIT_CODE=$1
    ERROR_LINE=$2

    echo -e "${RED} Unable to Complete Download ${NC}" 1>&2

    exit 1
}

trap 'catchError $? $LINENO' ERR


# Main script
main()
{
    mkdir -p $DNLD_ROOT
    mkdir -p $STAGE_ROOT
    cd $DNLD_ROOT

    # Determine if we are using a Github Branch, or downloading an official release
    if [ "${BRANCH}" != false ];
    then
        echo "Downloading Branch $BRANCH"
        URL=https://github.com/butcherman/tech_bench/archive/$BRANCH.zip;
    else
        echo "Downloading Latest Version"
        URL=$(curl -s https://api.github.com/repos/butcherman/tech_bench/releases/$VERSION | grep zipball_url | cut -d : -f 2,3 | tr -d \" | tr -d \,)
    fi

    # Download package
    FILE_ROOT=$(curl -LJO -w "%{filename_effective}" $URL)

    # Extract package
    if [ ! -f $FILE_ROOT ]
    then
        echo -e "${RED}Unable to download Tech Bench${NC}" 1>&2
        exit 1
    fi

    # Extract the zip file
    unzip -o $FILE_ROOT
    DIRNAME=$(zipinfo -1 $FILE_ROOT | grep -o "^[^/]\+[/]" | sort -u | tr -d \/)
    cd $DIRNAME

    # Move everything into a staging directory so it can be installed or updated
    if test -d src;
    then
        cp -R src/* $STAGE_ROOT/
        cp src/.env.example $STAGE_ROOT/.env.example
    else
        cp -R * $STAGE_ROOT/
        cp .env.example $STAGE_ROOT/.env.example
    fi

    # Cleanup
    rm -rf $TMP_ROOT
    rm -rf $STAGE_ROOT/tests
    rm $STAGE_ROOT/phpunit.xml

    # Write the version information into a file for the staging area
    VERSION_FILE="/tb_data/staging/config/version.yml"

    MAJOR=$(yq '.current.major' "$VERSION_FILE")
    MINOR=$(yq '.current.minor' "$VERSION_FILE")
    PATCH=$(yq '.current.patch' "$VERSION_FILE")

    echo "$MAJOR.$MINOR.$PATCH" > $STAGE_ROOT/version

    echo -e "${GREEN}Download Complete${NC}"
}

main
exit 0
