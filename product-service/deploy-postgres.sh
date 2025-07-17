#!/bin/bash

echo "🚀 Starting QuickCart Product Service with PostgreSQL..."

# Create necessary directories
echo "📁 Creating directories..."
mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views

# Set storage permissions
chmod -R 755 storage || echo "storage chmod failed, continuing..."

# Create .env file with PostgreSQL settings
echo "🔧 Setting up environment for PostgreSQL..."
cat > .env << EOF
APP_NAME=QuickCart
APP_ENV=production
APP_DEBUG=false
APP_KEY=${APP_KEY:-base64:$(openssl rand -base64 32)}
APP_URL=https://quickcart-production.up.railway.app

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=pgsql
DATABASE_URL=${DATABASE_URL}

CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
EOF

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear

# Wait for database to be ready
echo "⏳ Waiting for database..."
sleep 10

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Seed database
echo "🌱 Seeding database..."
php artisan db:seed --force

# Start server
echo "🌐 Starting server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT
