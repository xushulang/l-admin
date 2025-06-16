#!/bin/bash

sudo chmod a+x "$(pwd)"
sudo rm -rf /var/www/html
sudo ln -s "$(pwd)" /var/www/html

if [ ! -d "vendor" ]; then
    composer install
fi

php artisan key:generate

TABLE_EXISTS=$(php artisan tinker --execute "\$exists = Schema::hasTable('migrations'); echo \$exists ? 'yes' : 'no';")
if [ "$TABLE_EXISTS" == "no" ]; then
    php artisan migrate:fresh --seed
fi

php artisan scout:sync-index-settings

if [ ! -d "node_modules" ]; then
    npm install
fi
