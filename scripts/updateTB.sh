#!/bin/bash

REPO_URL="https://github.com/butcherman/Tech_Bench.git"
CURRENT_VERSION=0
UPGRADE_VERSION=0
FORCE=false

while getopts v:f opts
do
    case ${opts} in
        v) UPGRADE_VERSION=${OPTARG}
        ;;
        f) FORCE=true
        ;;
    esac
done

# Primary function for script
main()
{
    echo '##########################################################################'
    echo '#                                                                        #'
    echo '#                        Upgrade Tech Bench                              #'
    echo '#                                                                        #'
    echo '#                          IMPORTANT NOTE:                               #'
    echo '#         Be sure to backup configuration before doing update!!!         #'
    echo '#                                                                        #'
    echo '##########################################################################'
    echo

    validateTechBench

    # Double check the user wants to continue
    if [ $FORCE == false ]
    then
        read -p "Are you sure you want to upgrade (y/n)? " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]
        then
            [[ "$0" = "$BASH_SOURCE" ]] && exit 1 || return 1
        fi
    fi

    CURRENT_VERSION=$(getVersion)

    # If a version was not supplied, get the latest version number
    if [ $UPGRADE_VERSION == 0 ]
    then
        # Get the latest tag using git ls-remote, sort by version, and extract the tag name
        UPGRADE_VERSION=$(git -c 'versionsort.suffix=-' \
        ls-remote --exit-code --refs --sort='version:refname' --tags "$REPO_URL" \
            | tail --lines=1 \
            | cut --delimiter='/' --fields=3)

        # Print the latest tag
        echo "Latest Tech Bench Version: $UPGRADE_VERSION"
    fi

    if(validateVersion $CURRENT_VERSION $UPGRADE_VERSION )
    then
        echo "Current Version $CURRENT_VERSION"
        echo "Upgrade is not needed at this time"
        exit 1 || return 1
    fi

    # Set the APP_VERSION variable in .env to the current App Version if it exists
    sed -i "/APP_VERSION=/ c\APP_VERSION=$UPGRADE_VERSION" .env

    # Verify that all needed Docker Containers are present
    echo "Validating all Docker Containers are Present"

    declare -a NEEDED=("tech_bench" "nginx" "horizon" "scheduler" "database" "redis" "reverb" "meilisearch")
    for SVC in "${NEEDED[@]}"
    do
        HAS_SVC="$(docker compose config --services | grep $SVC)"
        if [[ $HAS_SVC != $SVC ]]
        then
            echo "Missing $SVC Service"
            findService $SVC
        fi
    done

    # Take all containers offline
    echo "Taking Tech Bench Offline"
    docker compose down

    # Remove all Tech Bench Containers
    # echo "Removing Old Containers"
    # for SVC in "${NEEDED[@]}"
    # do
    #     docker container rm $SVC -f
    # done

    # Delete all Tech Bench Images
    echo "Removing Old Images"
    docker rmi $(docker images --filter=reference="butcherman/tech_bench*:*" -q)

    # Bring Tech Bench Back Online
    docker compose up -d

    echo '##########################################################################'
    echo '#                                                                        #'
    echo '#                        Upgrade Complete                                #'
    echo '#                                                                        #'
    echo '##########################################################################'
}

# Verify that we are in the correct directory before continuing
validateTechBench()
{
    # Verify JQ is installed
    if ! command -v jq 2>&1 >/dev/null
    then
        echo "Missing JQ"
        echo "Please install with command - sudo apt install jq"
        exit 1 || return 1
    fi

    # Verify that the update script is in the same directory as the Docker Compose file
    if [ ! -f docker-compose.yml ]
    then
        echo "docker-compose.yml not found"
        echo "Please run this script from the root directory of the Tech Bench"
        exit 1 || return 1
    fi

    # Verify that the .env file exists
    if [ ! -f .env ]
    then
        echo "Tech Bench Environment file not found"
        echo "Please run this script from the root Directory of the Tech Bench "
        exit 1 || return 1
    fi
}

# Get the current version of the Tech Bench Application
getVersion()
{
    VER=$(docker exec tech_bench php artisan version)
    echo "${VER#Tech Bench Version }"
}

# Verify that the version being installed is newer than the current version
validateVersion() {
    local v1="$1"
    local v2="$2"

    # Use printf to put each version on a new line, then sort them version-wise.
    # If the first element after sorting is v2, it means v2 is smaller or equal to v1.
    if [ "$(printf '%s\n' "$v1" "$v2" | sort -V | head -n1)" = "$v2" ]; then
        return 0 # v1 is greater than or equal to v2
    else
        return 1 # v1 is less than v2
    fi
}

# Determine which service is missing and run function to add it
findService()
{
    case $1 in
        'reverb')
            addReverbService
            ;;
        'meilisearch')
            addMeilisearchService
            ;;
    esac
}

# Add the Reverb Service to the Docker Compose file
addReverbService()
{
    echo "Installing Reverb Container for Websockets"

    # Get the volumes of the tech_bench container to match those
    VOL_LIST="$(docker compose config --format json | jq -r '.services.tech_bench.volumes')"

    echo "" >> docker-compose.yml
    echo "    #  Reverb Container for Websockets" >> docker-compose.yml
    echo "    reverb:" >> docker-compose.yml
    echo "        container_name: reverb" >> docker-compose.yml
    echo "        restart: unless-stopped" >> docker-compose.yml
    echo "        image: butcherman/tech_bench_app:\${APP_VERSION:-latest}" >> docker-compose.yml
    echo "        volumes:" >> docker-compose.yml

    # Add the proper volumes
    for VOL in $(echo $VOL_LIST | jq -c '.[]')
    do
        SRC=$(echo $VOL | jq -r '.source')
        DST=$(echo $VOL | jq -r '.target')
        echo "            - $SRC:$DST" >> docker-compose.yml
    done

    echo "        environment:" >> docker-compose.yml
    echo "            - SERVICE=reverb" >> docker-compose.yml
    echo "        networks:" >> docker-compose.yml
    echo "            - app-tier" >> docker-compose.yml
    echo "        depends_on:" >> docker-compose.yml
    echo "            - tech_bench" >> docker-compose.yml
}

# Add the Meilisearch Service to the Docker Compose file
addMeilisearchService()
{
    echo "Installing Meilisearch Service for Search Engine"

    # Create volume for Meilisearch Data
    echo "$(awk '{print};/^volumes:/ && !ins { print "    meilisearchData:";ins=1}' docker-compose.yml)" > docker-compose.yml

    echo "" >> docker-compose.yml
    echo "    # Melisearch Container for Search Engine" >> docker-compose.yml
    echo "    meilisearch:" >> docker-compose.yml
    echo "        image: butcherman/tech_bench_meilisearch:1.0" >> docker-compose.yml
    echo "        container_name: meilisearch" >> docker-compose.yml
    echo "        restart: unless-stopped" >> docker-compose.yml
    echo "        volumes:" >> docker-compose.yml
    echo "            - meilisearchData:/meili_data" >> docker-compose.yml
    echo "        networks:" >> docker-compose.yml
    echo "            - app-tier" >> docker-compose.yml
}

main
exit 0
