#!/bin/bash
git pull origin main
php8.3 /usr/local/bin/composer install
php8.3 artisan optimize:clear
php8.3 artisan migrate --force
php8.3 artisan optimize
npm i
npm run prod
