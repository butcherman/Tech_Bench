#!/bin/bash
################################################################################
#                                                                              #
#  This bash script is for updating the application cache                      #
#                                                                              #
#  Note:  the script must be run as Sudo in order to properly set permissions  #
#                                                                              #
################################################################################

#  Pull in the variable file
STAGE_DIR="$(dirname "$(dirname "$(readlink -fm "$0")")")" 
source $STAGE_DIR/scripts/_config.sh

#  Change to the production directory and cache the settings
cd $PROD_DIR
php artisan config:cache
php artisan route:cache
