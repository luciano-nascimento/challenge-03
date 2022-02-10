#!/bin/bash

chmod -R 777 storage   
composer install
php artisan key:generate
php artisan migrate

#starting supervisor
echo "$(date +[%d-%b-%Y\ %H:%M:%S]) Starting Supervisor"
/usr/bin/supervisord -c /etc/supervisord.conf 2>&1 &

php-fpm

