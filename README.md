# 🛒 QuickCart - E-commerce Microservices Platform

> **Modern e-commerce platform built with microservices architecture, demonstrating scalable backend development with Laravel, Docker, and cloud deployment.**

[![Laravel](https://img.shields.io/badge/Laravel-9.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.0+-blue.svg)](https://php.net)
[![SQLite](https://img.shields.io/badge/Database-SQLite-green.svg)](https://sqlite.org)
[![Docker](https://img.shields.io/badge/Docker-Ready-blue.svg)](https://docker.com)

## 🚀 Live Demo

- **API Base URL:** `[DEPLOYMENT_URL_HERE]`
- **Health Check:** `[DEPLOYMENT_URL_HERE]/api/health`
- **Products API:** `[DEPLOYMENT_URL_HERE]/api/products-simple`

## 🏗️ Architecture Overview

QuickCart is designed as a **microservices platform** with independent, scalable services:

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│  Product Service │    │   User Service  │    │   Cart Service  │
│    (Laravel)    │    │   (Planned)     │    │   (Planned)     │
│                 │    │                 │    │                 │
│  • Products CRUD│    │  • Authentication│    │  • Cart Management│
│  • Inventory    │    │  • User Profiles │    │  • Order Processing│
│  • Categories   │    │  • Permissions   │    │  • Payment Integration│
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## 📦 Current Features

### ✅ Product Service (Implemented)
- **RESTful API** with Laravel 9
- **Database Management** with migrations & seeders
- **Product CRUD Operations** 
- **Inventory Management**
- **Health Monitoring**
- **Error Handling & Validation**

### 🔄 Planned Services
- **User Service** - Authentication & user management
- **Cart Service** - Shopping cart & order processing
- **Payment Service** - Payment processing integration

## 🛠️ Tech Stack

- **Backend:** Laravel 9, PHP 8.0+
- **Database:** SQLite (dev), MySQL (production)
- **Containerization:** Docker & Docker Compose
- **Cloud:** Railway/Render deployment ready
- **Testing:** PHPUnit
- **Documentation:** OpenAPI/Swagger ready

## 🚀 Quick Start

### Prerequisites
- PHP 8.0+
- Composer
- Git

### Local Development
```bash
# Clone repository
git clone https://github.com/[YOUR_USERNAME]/quickcart.git
cd quickcart

# Setup Product Service
cd product-service
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# Start development server
php artisan serve --port=8001
```

### Using Docker
```bash
# Start all services
docker-compose -f docker-compose.simple.yml up -d

# Check service health
curl http://localhost:8001/api/health
```

## 📚 API Documentation

### Base URL
```
Local: http://localhost:8001
Production: [DEPLOYMENT_URL]
```

### Endpoints

#### Health & Status
```http
GET /api/health
```
**Response:**
```json
{
  "service": "Product Service",
  "status": "healthy",
  "timestamp": "2025-07-17T19:44:55.913715Z",
  "version": "1.0.0"
}
```

#### Products
```http
GET /api/products-simple
```
**Response:**
```json
{
  "success": true,
  "count": 5,
  "data": [
    {
      "id": 1,
      "name": "iPhone 15 Pro",
      "price": "999.99",
      "stock_quantity": 50,
      "sku": "IPHONE-15-PRO-001"
    }
  ],
  "message": "Products retrieved successfully"
}
```

#### Full Product API
```http
GET    /api/v1/products       # List all products
POST   /api/v1/products       # Create product
GET    /api/v1/products/{id}  # Get specific product
PUT    /api/v1/products/{id}  # Update product
DELETE /api/v1/products/{id}  # Delete product
PATCH  /api/v1/products/{id}/stock # Update stock
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
```

## 🚀 Deployment

### Railway (Recommended)
1. Connect GitHub repository
2. Configure environment variables
3. Deploy automatically

### Render
1. Connect GitHub repository  
2. Set build command: `composer install --no-dev`
3. Set start command: `php artisan serve --host=0.0.0.0 --port=$PORT`

## 🔧 Development

### Project Structure
```
quickcart/
├── product-service/          # Laravel Product API
│   ├── app/Models/          # Eloquent models
│   ├── app/Http/Controllers/ # API controllers
│   ├── database/migrations/ # Database schema
│   ├── database/seeders/    # Sample data
│   └── routes/api.php       # API routes
├── docker-compose.yml       # Multi-service setup
└── README.md               # This file
```

### Adding New Services
1. Create new Laravel project in service directory
2. Update docker-compose.yml
3. Configure service communication
4. Update API gateway routes

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**[Your Name]**
- GitHub: [@your-username](https://github.com/your-username)
- LinkedIn: [Your LinkedIn](https://linkedin.com/in/your-profile)
- Portfolio: [your-portfolio.com](https://your-portfolio.com)

---

⭐ **Star this repository if you found it helpful!**
