#!/bin/bash
################################################################################
#                                                                              #
#  This bash script holds all variables for Tech Bench scripts                 #
#  You can modify this file as necessary to point to the proper locations      #
#                                                                              #
################################################################################

########################  Working directories  #################################

#  File staging directory
STAGE_DIR="$(dirname "$(dirname "$(readlink -fm "$0")")")"   

#  Web root directory
PROD_DIR="/var/www/html"      

#  Apache Web User
APUSR="www-data"

#  Function to show a spinner while a process executes
spin()
{
    spinner="/|\\-/|\\-"
    while :
    do
        for i in `seq 0 7`
        do
            echo -n "${spinner:$i:1}"
            echo -en "\010"
            sleep 0.5
        done
    done
}
