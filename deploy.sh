#!/bin/bash
# Laravel deployment script for AWS

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Generate application key
php artisan key:generate

# Set proper permissions
chmod -R 755 storage bootstrap/cache

# Run database migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment completed successfully!"