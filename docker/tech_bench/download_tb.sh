#!/bin/sh

################################################################################
#                                                                              #
#                              Download Script                                 #
#         Download the Tech Bench application and stage for install            #
#                                                                              #
################################################################################

#  Variables
BRANCH="${1:-false}"           # If this argument is populated, the branch identified will be downloaded and installed
VERSION="${2:-latest}"          # If BRANCH is left to false, this version will be downloaded.  Default value is "latest"

mkdir -p /tb_data/tmp/downloads
mkdir -p /tb_data/staging
cd /tb_data/tmp/downloads

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
    echo "Unable to download Tech Bench"
    exit 0
fi

unzip -o $FILE_ROOT

# Copy files to the /app directory
DIRNAME=$(zipinfo -1 $FILE_ROOT | grep -o "^[^/]\+[/]" | sort -u | tr -d \/)
cd $DIRNAME

# Move everything into a staging directory so it can be installed or updated
if test -d src;
then
    cp -R src/* /tb_data/staging/
    cp src/.env.example /tb_data/staging/.env.example
else
    cp -R * /tb_data/staging/
    cp .env.example /tb_data/staging/.env.example
fi

# Cleanup
rm -rf /tb_data/tmp/downloads/
rm -rf /tb_data/staging/tests
rm /tb_data/staging/phpunit.xml

# Write the version information into a file for the staging area
VERSION_FILE="/tb_data/staging/config/version.yml"

MAJOR=$(yq '.current.major' "$VERSION_FILE")
MINOR=$(yq '.current.minor' "$VERSION_FILE")
PATCH=$(yq '.current.patch' "$VERSION_FILE")

echo "$MAJOR.$MINOR.$PATCH" > /tb_data/staging/version

echo "Download Complete"
