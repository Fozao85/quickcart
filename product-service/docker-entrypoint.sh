#!/bin/bash

# QuickCart Product Service Docker Entrypoint Script

echo "🚀 Starting QuickCart Product Service..."

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
while ! mysqladmin ping -h"$DB_HOST" --silent; do
    echo "Waiting for MySQL..."
    sleep 2
done

echo "✅ MySQL is ready!"

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate application key if not exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
fi

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache config
echo "🔧 Optimizing application..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Seed database if empty
PRODUCT_COUNT=$(php artisan tinker --execute="echo App\Models\Product::count();")
if [ "$PRODUCT_COUNT" -eq "0" ]; then
    echo "🌱 Seeding database with sample data..."
    php artisan db:seed --force
fi

# Cache config for production
if [ "$APP_ENV" = "production" ]; then
    echo "⚡ Caching configuration for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

echo "✅ QuickCart Product Service is ready!"

# Start Apache
exec apache2-foreground
