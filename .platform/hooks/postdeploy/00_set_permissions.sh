#!/bin/bash

# Ensure storage and cache directories exist
echo "Creating missing directories..."
mkdir -p /var/app/current/storage/framework/sessions
mkdir -p /var/app/current/storage/framework/views
mkdir -p /var/app/current/storage/framework/cache
mkdir -p /var/app/current/storage/logs
mkdir -p /var/app/current/bootstrap/cache

# Ensure storage and cache have correct permissions
echo "Setting permissions..."
chmod -R 775 /var/app/current/storage /var/app/current/bootstrap/cache
chown -R webapp:webapp /var/app/current/storage /var/app/current/bootstrap/cache
