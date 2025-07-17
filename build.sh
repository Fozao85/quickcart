#!/bin/bash

echo "🚀 Building QuickCart Product Service..."

# Navigate to product service directory
cd product-service

# Install dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Generate application key if not exists
echo "🔑 Setting up application key..."
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate key
php artisan key:generate --force

# Create database file
echo "🗄️ Setting up database..."
touch database/database.sqlite

# Run migrations and seed
echo "🌱 Running migrations and seeding..."
php artisan migrate --force
php artisan db:seed --force

# Cache configuration for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build completed successfully!"
