@echo off
echo 🚀 Starting QuickCart - Local Development Mode
echo ===============================================

echo 📝 Setting up Product Service...
cd product-service

echo 🔧 Configuring environment...
if not exist .env copy .env.example .env

echo 🔑 Generating application key...
php artisan key:generate --force

echo 🧹 Clearing caches...
php artisan config:clear
php artisan cache:clear
php artisan route:clear

echo 🗄️ Setting up SQLite database...
if not exist database\database.sqlite (
    echo. > database\database.sqlite
)

echo 🗄️ Running migrations...
php artisan migrate --force

echo 🌱 Seeding database with sample products...
php artisan db:seed --force

echo 🚀 Starting Product Service on port 8001...
start "QuickCart Product Service" php artisan serve --port=8001

echo ⏳ Waiting for service to start...
timeout /t 5

echo 🧪 Testing API endpoints...
echo.
echo 📊 Health Check:
curl -s http://localhost:8001/api/health
echo.
echo.
echo 📦 Sample Products:
curl -s http://localhost:8001/api/products-simple
echo.
echo.

echo ✅ QuickCart Product Service is running!
echo ==========================================
echo.
echo 🌐 Service URLs:
echo   📦 Product API:    http://localhost:8001
echo   🏥 Health Check:   http://localhost:8001/api/health
echo   🧪 Test Endpoint:  http://localhost:8001/api/test
echo   📊 Simple Products: http://localhost:8001/api/products-simple
echo.
echo 📚 API Documentation:
echo   GET  /api/health              - Service health check
echo   GET  /api/test                - Basic connectivity test
echo   GET  /api/products-simple     - List products (simple)
echo   GET  /api/v1/products         - Full product API
echo   POST /api/v1/products         - Create product
echo.
echo 🔧 Development Commands:
echo   php artisan tinker            - Laravel console
echo   php artisan migrate:fresh     - Reset database
echo   php artisan db:seed           - Reseed data
echo.
echo 🎯 Next Steps:
echo   1. Test the API endpoints above
echo   2. Create GitHub repository
echo   3. Deploy to cloud (Render/Railway)
echo   4. Add to portfolio
echo.
echo 💡 Press Ctrl+C to stop the service
pause
