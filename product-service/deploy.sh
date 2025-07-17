#!/bin/bash

echo "🚀 Starting QuickCart Product Service..."

# Ensure we're in the right directory
pwd
ls -la

# Create necessary directories
echo "📁 Creating directories..."
mkdir -p database storage/logs storage/framework/cache storage/framework/sessions storage/framework/views

# Set up environment
echo "🔧 Setting up environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate --force
fi

# Create SQLite database
echo "🗄️ Setting up database..."
touch database/database.sqlite
chmod 666 database/database.sqlite || echo "chmod failed, continuing..."

# Set storage permissions
chmod -R 755 storage || echo "storage chmod failed, continuing..."

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force || echo "Migration failed, continuing..."

# Seed database
echo "🌱 Seeding database..."
php artisan db:seed --force || echo "Seeding failed, continuing..."

# Start server
echo "🌐 Starting server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT
