@echo off
echo 🐳 Testing QuickCart with Docker
echo ================================

echo 📊 Checking Docker status...
docker --version
if %errorlevel% neq 0 (
    echo ❌ Docker is not available
    pause
    exit /b 1
)

echo ✅ Docker is available

echo 🚀 Starting database services...
docker-compose -f docker-compose.simple.yml up -d

echo ⏳ Waiting for databases to be ready...
timeout /t 15

echo 📊 Checking running containers...
docker ps

echo 🗄️ Testing MySQL connection...
docker exec quickcart-mysql mysql -u quickcart -ppassword -e "SHOW DATABASES;"

echo 📊 Testing Redis connection...
docker exec quickcart-redis redis-cli ping

echo 🔧 Setting up Laravel application...
cd product-service

echo 🗄️ Running migrations...
php artisan migrate --force

echo 🌱 Seeding database...
php artisan db:seed --force

echo 🚀 Starting Laravel server...
start "QuickCart Product Service" php artisan serve --port=8001

echo ⏳ Waiting for Laravel to start...
timeout /t 5

echo 🧪 Testing API endpoints...
echo.
echo Health Check:
curl -s http://localhost:8001/api/health
echo.
echo.

echo ✅ QuickCart is running!
echo 📊 Services:
echo   - Product API: http://localhost:8001
echo   - phpMyAdmin: http://localhost:8080
echo   - MySQL: localhost:3306
echo   - Redis: localhost:6379
echo.
echo 🧪 Test commands:
echo   curl http://localhost:8001/api/health
echo   curl http://localhost:8001/api/v1/products
echo.
pause
