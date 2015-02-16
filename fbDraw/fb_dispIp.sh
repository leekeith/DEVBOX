#! /bin/bash
IP=$(/sbin/ifconfig eth0 | grep 'inet addr' | cut -d: -f2 | awk '{print $1}');
fbPrintArg "Welcome to DEVBOX! Please go to \"" $IP "\" in your web browser to begin" ;
