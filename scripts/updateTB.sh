#!/bin/bash

UPGRADE_VERSION=dev7
FORCE=false

# Get Arguments
while [[ $# -gt 0 ]]
do
    case $1 in
        -f|--force)
            FORCE=true
            ;;
    esac
    shift
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
        echo "Please run this script from the root direcotry of the Tech Bench "
    fi

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
    echo "Removing Old Containers"
    for SVC in "${NEEDED[@]}"
    do
        docker container rm $SVC -f
    done

    # Delete all Tech Bench Images
    echo "Removing Old Images"
    docker rmi $(docker images --filter=reference="butcherman/tech_bench*:*" -q)

    # Bring Tech Bench Back Online
    docker compose up -d

    echo '##########################################################################'
    echo '#                                                                        #'
    echo '#                        Upgrade Commplete                               #'
    echo '#                                                                        #'
    echo '##########################################################################'
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
