#!/bin/bash

# QuickCart - Quick Start Script

echo "🚀 Starting QuickCart Microservices..."
echo "======================================"

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker Desktop first."
    exit 1
fi

echo "✅ Docker is running"

# Build and start services
echo "🔨 Building and starting services..."
docker-compose up --build -d

echo "⏳ Waiting for services to be ready..."
sleep 30

# Check service health
echo "🏥 Checking service health..."

# Check MySQL
echo "📊 MySQL Status:"
docker-compose exec mysql mysqladmin ping -h localhost --silent && echo "✅ MySQL is healthy" || echo "❌ MySQL is not ready"

# Check Redis
echo "📊 Redis Status:"
docker-compose exec redis redis-cli ping && echo "✅ Redis is healthy" || echo "❌ Redis is not ready"

# Check Product Service
echo "📊 Product Service Status:"
curl -s http://localhost:8001/api/health > /dev/null && echo "✅ Product Service is healthy" || echo "❌ Product Service is not ready"

echo ""
echo "🎉 QuickCart is starting up!"
echo "=============================="
echo ""
echo "📋 Service URLs:"
echo "  🛍️  Product Service:  http://localhost:8001"
echo "  🏥  Health Check:     http://localhost:8001/api/health"
echo "  📊  phpMyAdmin:       http://localhost:8080"
echo "  🌐  API Gateway:      http://localhost"
echo ""
echo "📚 API Endpoints:"
echo "  GET  /api/health                 - Health check"
echo "  GET  /api/v1/products           - List products"
echo "  POST /api/v1/products           - Create product"
echo "  GET  /api/v1/products/{id}      - Get product"
echo "  PUT  /api/v1/products/{id}      - Update product"
echo "  DELETE /api/v1/products/{id}    - Delete product"
echo ""
echo "🔧 Management Commands:"
echo "  docker-compose logs product-service  - View logs"
echo "  docker-compose exec product-service php artisan tinker  - Laravel console"
echo "  docker-compose down                  - Stop all services"
echo ""
echo "✨ Happy coding!"
