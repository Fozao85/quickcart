@echo off
echo 🔧 Fixing QuickCart Database Issues
echo ==================================

cd product-service

echo 🧹 Clearing all caches...
php artisan config:clear
php artisan cache:clear
php artisan route:clear

echo 🗄️ Checking database file...
if exist database\database.sqlite (
    echo ✅ Database file exists
) else (
    echo 📝 Creating database file...
    echo. > database\database.sqlite
)

echo 🗄️ Refreshing database...
php artisan migrate:fresh --seed --force

echo 🧪 Testing database connection...
php artisan tinker --execute="echo 'Products count: ' . App\Models\Product::count();"

echo 🚀 Starting Laravel server...
echo 📊 Server will start on: http://localhost:8001
echo 🏥 Health check: http://localhost:8001/api/health
echo.
echo 💡 Press Ctrl+C to stop the server
echo.

php artisan serve --port=8001
