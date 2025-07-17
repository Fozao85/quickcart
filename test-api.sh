#!/bin/bash

# QuickCart Product Service API Test Script

BASE_URL="http://localhost:8001/api"

echo "🚀 Testing QuickCart Product Service API"
echo "========================================"

# Test 1: Health Check
echo "1. Testing Health Check..."
curl -s "$BASE_URL/health" | jq '.' || echo "Health check failed"
echo ""

# Test 2: Get All Products
echo "2. Getting All Products..."
curl -s "$BASE_URL/v1/products" | jq '.data.data[0:3]' || echo "Get products failed"
echo ""

# Test 3: Create a Product
echo "3. Creating a New Product..."
curl -s -X POST "$BASE_URL/v1/products" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Laptop",
    "description": "A powerful test laptop for development",
    "price": 1299.99,
    "stock_quantity": 5,
    "category_id": 2,
    "sku": "TEST-LAPTOP-001",
    "is_active": true
  }' | jq '.' || echo "Create product failed"
echo ""

# Test 4: Search Products
echo "4. Searching Products..."
curl -s "$BASE_URL/v1/products?search=iPhone" | jq '.data.data[0:2]' || echo "Search failed"
echo ""

# Test 5: Filter by Category
echo "5. Filtering by Category..."
curl -s "$BASE_URL/v1/products?category_id=1" | jq '.data.data[0:2]' || echo "Filter failed"
echo ""

echo "✅ API Testing Complete!"
echo "📊 Check the responses above for results"
