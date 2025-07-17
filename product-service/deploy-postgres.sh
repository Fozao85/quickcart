#!/bin/bash

echo "🚀 Starting QuickCart Product Service with PostgreSQL..."

# Set up environment
echo "🔧 Setting up environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate --force
fi

# Create necessary directories
echo "📁 Creating directories..."
mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views

# Set storage permissions
chmod -R 755 storage || echo "storage chmod failed, continuing..."

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear

# Wait for database to be ready
echo "⏳ Waiting for database..."
sleep 5

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Seed database
echo "🌱 Seeding database..."
php artisan db:seed --force

# Start server
echo "🌐 Starting server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT
