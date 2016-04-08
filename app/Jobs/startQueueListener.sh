#!/bin/bash
#
#crontab -e
#0 * * * * cd /var/www/vital4.0/app/Jobs && ./startQueueListener.sh >> /var/www/vital4.0/storage/logs/startQueueListener.out

if [ `ps -ef | grep artisan | grep -vc grep` == 0 ]; then
    echo `date` "starting php artisan queue:listen" >> /var/www/vital4.0/storage/logs/queueListen.log;
    cd /var/www/vital4.0/ && setsid php artisan queue:listen --timeout=10800 >> /var/www/vital4.0/storage/logs/queueListen.log 2>&1;
    exit 0;
fi

echo `date` "php artisan queue:listen is already running";
exit 1;

