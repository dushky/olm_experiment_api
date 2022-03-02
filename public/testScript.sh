#!/bin/sh
processId=$(ps -ef | grep 'matlab' | grep -v 'grep' | awk '{ printf $2 }')
if [ -z "$processId" ];
then
    /usr/local/bin/matlab -r 'matlab.engine.shareEngine' -desktop >> /var/www/olm_experiment_api/public/output.txt
else
    echo 'running'
fi
