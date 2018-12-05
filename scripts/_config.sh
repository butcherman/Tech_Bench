#!/bin/bash
################################################################################
#  This bash script holds all variables for Tech Bench scripts                 #
#  You can modify this file as necessary to point to the proper locations      #
#                                                                              #
#  Script Version:  2.0                                                        #
#  Script Date:     11-22-2018                                                 #
################################################################################

########################  Working directories  #################################

#  File staging directory
STAGE_DIR="$(dirname "$(dirname "$(readlink -fm "$0")")")"   

#  Web root directory
PROD_DIR="/var/www/html"      

#  Apache Web User
$APUSR="www-data"
