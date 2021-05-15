#!/bin/bash
pecl install xlswriter 
cd /app/web
composer update 
php init --env=Development --overwrite=All
RUN service memcached start
mkdir temp
chmod 777 -R /app/web/temp