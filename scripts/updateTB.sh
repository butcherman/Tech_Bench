#!/bin/bash

echo '##########################################################################'
echo '#                                                                        #'
echo '#                        Upgrade Tech Bench                              #'
echo '#                                                                        #'
echo '#                          IMPORTANT NOTE:                               #'
echo '#         Be sure to backup configuration before doing update!!!         #'
echo '#                                                                        #'
echo '##########################################################################'

read -p "Are you sure you want to upgrade? " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    [[ "$0" = "$BASH_SOURCE" ]] && exit 1 || return 1
fi
