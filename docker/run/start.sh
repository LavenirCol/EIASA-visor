#!/bin/bash
cd /app/web
git pull origin master
php yii migrate --interactive=0
ln -s /usr/share/zoneinfo/America/Bogota /etc/localtime