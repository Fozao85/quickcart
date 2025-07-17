@echo off
echo 🚀 Testing QuickCart Product Service Locally
echo ==========================================

cd product-service

echo 📝 Setting up environment...
if not exist .env copy .env.example .env

echo 🔑 Generating application key...
php artisan key:generate

echo 🗄️ Running migrations...
php artisan migrate --force

echo 🌱 Seeding database...
php artisan db:seed --force

echo 🚀 Starting Laravel server...
start "QuickCart Product Service" php artisan serve --port=8001

echo ⏳ Waiting for server to start...
timeout /t 5

echo 🧪 Testing API endpoints...
echo.
echo Health Check:
curl -s http://localhost:8001/api/health
echo.
echo.
echo Test Endpoint:
curl -s http://localhost:8001/api/test
echo.
echo.
echo ✅ QuickCart Product Service is running!
echo 📊 Access at: http://localhost:8001
echo 🏥 Health check: http://localhost:8001/api/health
echo.
pause
