#!/bin/sh
count=`ps -fe| grep "node server.js" | grep -vc "grep"`
echo $count
if [ $count -lt 1  ];then
    echo ">>>>no server,run it"
    cd /www/restaurant-server/collet-service/
    /usr/bin/node server.js env=production  sid=cns_1  &
else
    echo ">>>>server is running"
fi