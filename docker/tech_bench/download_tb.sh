#!/bin/bash

################################################################################
#                                                                              #
#                              Download Script                                 #
#         Download the Tech Bench application and stage for install            #
#                                                                              #
################################################################################

set -eE

source /tb_data/scripts/_functions.sh

# Variables
BRANCH="${1:-false}"     # GitHub Branch to be downloaded - blank for version
VERSION="${2:-latest}"   # Version to be downloaded
ERROR_MSG="Unable to Complete Download"

# Directory Variables
STAGE_ROOT=/tb_data/staging
TMP_ROOT=/tb_data/tmp
DNLD_ROOT=$TMP_ROOT/download

# Catch any errors
trap 'catchError $? $LINENO' ERR

# Download the proper version of Tech Bench and stage for install
main()
{
    buildDirectoryStructure
    downloadPackage
    cleanup

    # Write the version information into a file for the staging area
    VERSION_FILE="${STAGE_ROOT}/config/version.yml"

    MAJOR=$(yq '.current.major' "$VERSION_FILE")
    MINOR=$(yq '.current.minor' "$VERSION_FILE")
    PATCH=$(yq '.current.patch' "$VERSION_FILE")

    echo "$MAJOR.$MINOR.$PATCH" > $STAGE_ROOT/version

    echo -e "${GREEN}Download Complete${NC}"
}

# Build directory structure
buildDirectoryStructure()
{
    if test -d $STAGE_ROOT
    then
        run rm -rf $STAGE_ROOT
    fi

    run mkdir -p $DNLD_ROOT
    run mkdir -p $STAGE_ROOT
}

# Download the Tech Bench package and Stage it in the Staging directory
downloadPackage()
{
    cd $DNLD_ROOT

    # Determine if we are using a Github Branch, or downloading an official release
    if [ "${BRANCH}" != false ];
    then
        echo "Downloading Branch $BRANCH"
        URL=https://github.com/butcherman/tech_bench/archive/$BRANCH.zip;
    elif [ "${VERSION}" == 'latest' ]
    then
        echo "Downloading Latest Version"
        URL=$(curl -s https://api.github.com/repos/butcherman/tech_bench/releases/$VERSION | grep zipball_url | cut -d : -f 2,3 | tr -d \" | tr -d \,)
    else
        echo "Downloading Version ${VERSION}"
        URL=$(curl -s https://api.github.com/repos/butcherman/tech_bench/releases/tags/$VERSION | grep zipball_url | cut -d : -f 2,3 | tr -d \" | tr -d \,)
    fi

    if [ ! $URL ]
    then
        echo -e "${RED}Version or Branch Invalid${NC}" 1>&2
        exit 1
    fi

    # Download package
    FILE_ROOT=$(curl -LJO -w "%{filename_effective}" $URL)

    # Extract the zip file
    run unzip -o $FILE_ROOT
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
}

# Remove temporary files
cleanup()
{
    # Cleanup
    run rm -rf $TMP_ROOT
    run rm -rf $STAGE_ROOT/tests
    run rm $STAGE_ROOT/phpunit.xml
}

main
exit 0
