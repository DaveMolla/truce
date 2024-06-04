#!/bin/bash

# Define the path to your Laravel project directory
PROJECT_PATH="/home/dirtechnologycom/truce.dirtechnology.com"

# Navigate to your project directory
cd $PROJECT_PATH

# Pull latest changes from GitHub
git pull origin main

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies
npm install

# Compile assets for production
npm run build

# Run database migrations
php artisan migrate:fresh --seed --step --force

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Set permissions (if needed)
chown -R www-data:www-data $PROJECT_PATH

echo "Deployment completed!"
