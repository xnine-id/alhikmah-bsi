#!/bin/sh
set -e

# Replace env variable placeholders with real values
printenv | grep VITE_ | while read -r line ; do
    key=$(echo $line | cut -d "=" -f1)
    value=$(echo $line | cut -d "=" -f2)
    
    find /var/www/html/public/build/ -type f -exec sed -i "s|$key|$value|g" {} \;
done

# Storage link
php artisan storage:link || true

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# php-fpm
exec php-fpm
