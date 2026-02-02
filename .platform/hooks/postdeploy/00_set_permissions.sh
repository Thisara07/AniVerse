#!/bin/bash

# Ensure storage and cache have correct permissions
echo "Setting permissions..."
chmod -R 775 /var/app/current/storage /var/app/current/bootstrap/cache
chown -R webapp:webapp /var/app/current/storage /var/app/current/bootstrap/cache
