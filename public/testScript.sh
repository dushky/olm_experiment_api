#!/bin/sh
processId=$(pidof MATLAB)
#processId=$(ps -ef | grep 'matlab' | grep -v 'grep' | awk '{ printf $2 }')
if [ -z "$processId" ];
then
    /usr/local/bin/matlab -r 'matlab.engine.shareEngine' -desktop
else
    echo 'running'
fi
