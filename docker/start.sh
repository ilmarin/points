#!/bin/bash
set -x #echo on

# reset permissions of laravel run-time caches
echo "Resetting permissions"
chown -R www-data:www-data /var/www/html/points/storage

# start processes
echo "Starting Apache"
source /etc/apache2/envvars
exec /usr/sbin/apache2 -DFOREGROUND