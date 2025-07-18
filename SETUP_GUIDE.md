# QuickCart Setup Guide

## 🚀 Quick Start (5 Minutes)

### Prerequisites
- Node.js 18+ and npm
- PHP 8.1+ and Composer
- Git

### 1. Clone Repository
```bash
git clone https://github.com/Fozao85/quickcart.git
cd quickcart
```

### 2. Backend Setup (Laravel)
```bash
cd product-service
composer install
cp .env.example .env
php artisan key:generate

# Configure database in .env file
php artisan migrate
php artisan db:seed
php artisan serve
```

### 3. Frontend Setup (React)
```bash
cd ../quickcart-frontend
npm install
cp .env.example .env
npm run dev
```

### 4. Test the Application
- Backend: http://localhost:8000/api/health
- Frontend: http://localhost:5173
- API Products: http://localhost:8000/api/products-simple

## 🌐 Live Demo

### Production URLs
- **Backend API**: https://quickcart-production.up.railway.app/api
- **Health Check**: https://quickcart-production.up.railway.app/api/health
- **Sample Products**: https://quickcart-production.up.railway.app/api/products-simple

### Test the Live API
```bash
# Health check
curl https://quickcart-production.up.railway.app/api/health

# Get products
curl https://quickcart-production.up.railway.app/api/products-simple

# Register user
curl -X POST https://quickcart-production.up.railway.app/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

## 📁 Project Structure

```
quickcart/
├── product-service/          # Laravel Backend
│   ├── app/
│   │   ├── Http/Controllers/ # API Controllers
│   │   ├── Models/          # Eloquent Models
│   │   └── ...
│   ├── database/
│   │   ├── migrations/      # Database Migrations
│   │   └── seeders/         # Database Seeders
│   ├── routes/api.php       # API Routes
│   └── railway.json         # Railway Deployment Config
├── quickcart-frontend/       # React Frontend
│   ├── src/
│   │   ├── components/      # React Components
│   │   ├── contexts/        # React Contexts
│   │   ├── pages/           # Page Components
│   │   ├── services/        # API Services
│   │   └── App.jsx          # Main App Component
│   ├── package.json         # Dependencies
│   └── railway.json         # Railway Deployment Config
├── DOCUMENTATION.md          # Complete Documentation
├── API_DOCUMENTATION.md      # API Reference
├── PORTFOLIO_SHOWCASE.md     # Portfolio Integration
└── README.md                # Project Overview
```

## 🛠 Development Environment

### Backend Development
```bash
cd product-service

# Install dependencies
composer install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup (SQLite for local development)
touch database/database.sqlite
# Update .env: DB_CONNECTION=sqlite, DB_DATABASE=/absolute/path/to/database.sqlite

# Run migrations and seed data
php artisan migrate
php artisan db:seed

# Start development server
php artisan serve
```

### Frontend Development
```bash
cd quickcart-frontend

# Install dependencies
npm install

# Environment setup
cp .env.example .env
# Update VITE_API_URL=http://localhost:8000/api

# Start development server
npm run dev
```

### Development Workflow
1. **Backend Changes**: Edit Laravel files, test with Postman/curl
2. **Frontend Changes**: Edit React components, see live updates
3. **Database Changes**: Create migrations, run `php artisan migrate`
4. **API Testing**: Use the health endpoint and products endpoint

## 🚀 Deployment Guide

### Deploy Backend to Railway

1. **Create Railway Account**: https://railway.app
2. **Connect GitHub**: Link your GitHub repository
3. **Create New Project**: Select "Deploy from GitHub repo"
4. **Configure Service**:
   - **Root Directory**: `product-service`
   - **Build Command**: `composer install --no-dev --optimize-autoloader`
   - **Start Command**: `php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT`

5. **Add PostgreSQL Database**:
   - Click "Add Service" → "Database" → "PostgreSQL"
   - Railway automatically provides DATABASE_URL

6. **Environment Variables**:
   ```
   APP_NAME=QuickCart
   APP_ENV=production
   APP_DEBUG=false
   DB_CONNECTION=pgsql
   DB_HOST=${PGHOST}
   DB_PORT=${PGPORT}
   DB_DATABASE=${PGDATABASE}
   DB_USERNAME=${PGUSER}
   DB_PASSWORD=${PGPASSWORD}
   ```

### Deploy Frontend to Railway

1. **Create New Service**: In the same Railway project
2. **Configure Frontend Service**:
   - **Root Directory**: `quickcart-frontend`
   - **Build Command**: `npm install && npm run build`
   - **Start Command**: `npx serve -s dist -p $PORT`

3. **Environment Variables**:
   ```
   VITE_API_URL=https://your-backend-url.railway.app/api
   ```

### Alternative Deployment Options

#### Vercel (Frontend)
```bash
cd quickcart-frontend
npm install -g vercel
vercel --prod
```

#### Heroku (Backend)
```bash
cd product-service
# Create Procfile: web: php artisan serve --host=0.0.0.0 --port=$PORT
git add .
git commit -m "Deploy to Heroku"
heroku create your-app-name
heroku addons:create heroku-postgresql:hobby-dev
git push heroku main
```

## 🧪 Testing

### Backend Testing
```bash
cd product-service

# Run all tests
php artisan test

# Run specific test
php artisan test --filter=ProductTest

# Test with coverage
php artisan test --coverage
```

### Frontend Testing
```bash
cd quickcart-frontend

# Run tests
npm test

# Run tests with coverage
npm run test:coverage

# Run E2E tests
npm run test:e2e
```

### Manual Testing Checklist

#### API Testing
- [ ] Health check endpoint works
- [ ] User registration works
- [ ] User login returns token
- [ ] Products endpoint returns data
- [ ] Cart operations work with authentication
- [ ] Order creation works

#### Frontend Testing
- [ ] Home page loads correctly
- [ ] Navigation works on all devices
- [ ] Login/register forms work
- [ ] Product catalog displays
- [ ] Cart functionality works
- [ ] Responsive design works

## 🔧 Troubleshooting

### Common Backend Issues

#### Database Connection Error
```bash
# Check database configuration
php artisan config:clear
php artisan cache:clear

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

#### Migration Issues
```bash
# Reset and re-run migrations
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status
```

#### Permission Issues
```bash
# Fix storage permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Common Frontend Issues

#### API Connection Error
```bash
# Check environment variables
cat .env

# Verify API URL
curl $VITE_API_URL/health
```

#### Build Issues
```bash
# Clear node modules and reinstall
rm -rf node_modules package-lock.json
npm install

# Clear Vite cache
npm run dev -- --force
```

### Railway Deployment Issues

#### Build Failures
- Check build logs in Railway dashboard
- Verify `railway.json` configuration
- Ensure all dependencies are in `package.json`/`composer.json`

#### Environment Variables
- Double-check all required environment variables
- Ensure database connection variables are set
- Verify API URL in frontend environment

## 📚 Additional Resources

### Documentation
- [Laravel Documentation](https://laravel.com/docs)
- [React Documentation](https://react.dev)
- [Railway Documentation](https://docs.railway.app)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

### API Testing Tools
- [Postman](https://www.postman.com) - API testing and documentation
- [Insomnia](https://insomnia.rest) - REST client
- [curl](https://curl.se) - Command-line HTTP client

### Development Tools
- [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar) - Debug toolbar
- [React Developer Tools](https://react.dev/learn/react-developer-tools) - Browser extension
- [Vite](https://vitejs.dev) - Build tool and dev server

## 🎯 Next Steps

### For Development
1. **Add Tests**: Write comprehensive test suites
2. **Add Features**: Implement payment processing, email notifications
3. **Optimize Performance**: Add caching, optimize queries
4. **Enhance Security**: Add rate limiting, input sanitization

### For Portfolio
1. **Create Demo Video**: Record application walkthrough
2. **Take Screenshots**: Capture key features and UI
3. **Write Case Study**: Document development process and challenges
4. **Update Resume**: Add project to technical experience

### For Production
1. **Domain Setup**: Configure custom domain
2. **SSL Certificate**: Ensure HTTPS everywhere
3. **Monitoring**: Set up error tracking and performance monitoring
4. **Backup Strategy**: Implement database backup procedures

---

## 🎉 Congratulations!

You now have a complete, production-ready e-commerce platform that demonstrates advanced full-stack development skills. The application is live, documented, and ready for portfolio presentation.

**Key Achievements:**
- ✅ Full-stack e-commerce application
- ✅ Modern technology stack (Laravel + React)
- ✅ Production deployment on Railway
- ✅ Comprehensive documentation
- ✅ Professional code quality
- ✅ Portfolio-ready presentation

This project showcases the skills and knowledge required for senior-level web development positions and serves as an excellent portfolio piece for job applications and client presentations.
