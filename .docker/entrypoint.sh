#!/bin/bash
ln -s /app/web /var/www/html
rm -rf app/cache/*

service apache2 start

while true; do echo a >> /dev/null ; sleep 1; done
