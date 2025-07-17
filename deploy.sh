#!/bin/bash

echo "🚀 Starting QuickCart Product Service..."

# Navigate to product service directory
cd product-service

# Ensure database directory exists
echo "📁 Creating database directory..."
mkdir -p database
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views

# Create and set permissions for database
echo "🗄️ Setting up SQLite database..."
touch database/database.sqlite
chmod 666 database/database.sqlite
chmod 755 database

# Set storage permissions
chmod -R 755 storage
chmod -R 666 storage/logs

# Run migrations and seed
echo "🌱 Running migrations and seeding..."
php artisan migrate --force
php artisan db:seed --force

# Start the Laravel server
echo "🌐 Starting Laravel server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT
