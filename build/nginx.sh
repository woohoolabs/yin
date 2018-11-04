#!/bin/sh
set -e

export DOLLAR='$'

rm -f /etc/nginx/conf.d/*
rm -Rf /var/www/html

envsubst < /var/www/build/nginx.conf > /etc/nginx/nginx.conf
envsubst < /var/www/build/site.conf > /etc/nginx/conf.d/20-site.conf

nginx
