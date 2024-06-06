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



# #!/bin/bash

# # Navigate to the project directory
# cd /home/dirtechnologycom/truce.dirtechnology.com

# # Pull the latest changes from the main branch
# git pull origin main

# # Install PHP dependencies
# composer install --no-dev --optimize-autoloader

# # Install Node.js dependencies and build assets
# # Use the full path to npm
# /home/dirtechnologycom/nodevenv/truce.dirtechnology.com/20/bin/npm install
# /home/dirtechnologycom/nodevenv/truce.dirtechnology.com/20/bin/npm run dev

# # Migrate and seed the database
# php artisan migrate --force
# php artisan db:seed --force

# # Clear and cache configurations
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# # Set the correct permissions
# chown -R dirtechnologycom:dirtechnologycom /home/dirtechnologycom/truce.dirtechnology.com
# chmod -R 755 /home/dirtechnologycom/truce.dirtechnology.com/storage
# chmod -R 755 /home/dirtechnologycom/truce.dirtechnology.com/bootstrap/cache

# echo "Deployment completed successfully."
