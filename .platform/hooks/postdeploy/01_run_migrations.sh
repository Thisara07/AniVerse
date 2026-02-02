#!/bin/bash

# Navigate to the application directory
cd /var/app/current

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Optimize the application
echo "Optimizing..."
php artisan optimize
