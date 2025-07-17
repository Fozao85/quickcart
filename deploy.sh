#!/bin/bash

echo "🚀 Starting QuickCart Product Service..."

# Navigate to product service directory
cd product-service

# Ensure database exists and is migrated
echo "🗄️ Ensuring database is ready..."
touch database/database.sqlite
php artisan migrate --force

# Start the Laravel server
echo "🌐 Starting Laravel server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT
