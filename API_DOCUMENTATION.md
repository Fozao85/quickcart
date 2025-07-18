# QuickCart API Documentation

## Base URL
```
Production: https://quickcart-production.up.railway.app/api
Local: http://localhost:8000/api
```

## Authentication
The API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header for protected endpoints.

```http
Authorization: Bearer {your-token-here}
```

## Response Format
All API responses follow a consistent JSON format:

### Success Response
```json
{
  "success": true,
  "data": {...},
  "message": "Operation completed successfully"
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "errors": {...} // Validation errors (if applicable)
}
```

## Endpoints

### Health Check

#### GET /health
Check API health status.

**Response:**
```json
{
  "service": "Product Service",
  "status": "healthy",
  "timestamp": "2025-07-18T01:00:00.000000Z",
  "version": "1.0.0"
}
```

---

## Authentication Endpoints

### POST /auth/register
Register a new user account.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "email_verified_at": null,
      "created_at": "2025-07-18T01:00:00.000000Z",
      "updated_at": "2025-07-18T01:00:00.000000Z"
    },
    "access_token": "1|abc123...",
    "token_type": "Bearer"
  },
  "message": "User registered successfully"
}
```

### POST /auth/login
Authenticate user and get access token.

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "access_token": "1|abc123...",
    "token_type": "Bearer"
  },
  "message": "Login successful"
}
```

### GET /auth/me
Get current authenticated user information.

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null,
    "created_at": "2025-07-18T01:00:00.000000Z",
    "updated_at": "2025-07-18T01:00:00.000000Z"
  },
  "message": "User retrieved successfully"
}
```

### POST /auth/logout
Logout current user (invalidate current token).

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Logout successful"
}
```

### POST /auth/logout-all
Logout from all devices (invalidate all tokens).

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Logged out from all devices successfully"
}
```

---

## Product Endpoints

### GET /v1/products
Get paginated list of products with optional filtering.

**Query Parameters:**
- `page` (integer): Page number (default: 1)
- `per_page` (integer): Items per page (default: 15, max: 100)
- `search` (string): Search in name, description, or SKU
- `category_id` (integer): Filter by category
- `min_price` (decimal): Minimum price filter
- `max_price` (decimal): Maximum price filter
- `in_stock` (boolean): Filter by stock availability
- `active` (boolean): Filter by active status
- `sort_by` (string): Sort field (default: created_at)
- `sort_order` (string): Sort direction (asc/desc, default: desc)

**Example Request:**
```http
GET /v1/products?search=iphone&category_id=1&min_price=500&page=1&per_page=10
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "iPhone 15 Pro",
      "description": "Latest iPhone with A17 Pro chip...",
      "price": "999.99",
      "stock_quantity": 50,
      "category_id": 1,
      "image_url": "https://images.unsplash.com/...",
      "sku": "IPHONE-15-PRO-001",
      "is_active": true,
      "created_at": "2025-07-18T01:00:00.000000Z",
      "updated_at": "2025-07-18T01:00:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 10,
    "total": 50,
    "from": 1,
    "to": 10
  },
  "message": "Products retrieved successfully"
}
```

### GET /v1/products/{id}
Get single product by ID.

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "iPhone 15 Pro",
    "description": "Latest iPhone with A17 Pro chip...",
    "price": "999.99",
    "stock_quantity": 50,
    "category_id": 1,
    "image_url": "https://images.unsplash.com/...",
    "sku": "IPHONE-15-PRO-001",
    "is_active": true,
    "created_at": "2025-07-18T01:00:00.000000Z",
    "updated_at": "2025-07-18T01:00:00.000000Z"
  },
  "message": "Product retrieved successfully"
}
```

### POST /v1/products
Create a new product (Admin only).

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "iPhone 15 Pro",
  "description": "Latest iPhone with A17 Pro chip, titanium design, and advanced camera system.",
  "price": 999.99,
  "stock_quantity": 50,
  "category_id": 1,
  "image_url": "https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=400",
  "sku": "IPHONE-15-PRO-001",
  "is_active": true
}
```

**Response (201):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "iPhone 15 Pro",
    "description": "Latest iPhone with A17 Pro chip...",
    "price": "999.99",
    "stock_quantity": 50,
    "category_id": 1,
    "image_url": "https://images.unsplash.com/...",
    "sku": "IPHONE-15-PRO-001",
    "is_active": true,
    "created_at": "2025-07-18T01:00:00.000000Z",
    "updated_at": "2025-07-18T01:00:00.000000Z"
  },
  "message": "Product created successfully"
}
```

### PUT /v1/products/{id}
Update an existing product.

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "iPhone 15 Pro Max",
  "price": 1099.99,
  "stock_quantity": 30
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "iPhone 15 Pro Max",
    "description": "Latest iPhone with A17 Pro chip...",
    "price": "1099.99",
    "stock_quantity": 30,
    "category_id": 1,
    "image_url": "https://images.unsplash.com/...",
    "sku": "IPHONE-15-PRO-001",
    "is_active": true,
    "created_at": "2025-07-18T01:00:00.000000Z",
    "updated_at": "2025-07-18T01:05:00.000000Z"
  },
  "message": "Product updated successfully"
}
```

### DELETE /v1/products/{id}
Delete a product (soft delete by setting is_active to false).

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Product deleted successfully"
}
```

### PATCH /v1/products/{id}/stock
Update product stock quantity.

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "quantity": 10,
  "operation": "add" // "add", "subtract", or "set"
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "iPhone 15 Pro",
    "stock_quantity": 60,
    // ... other product fields
  },
  "message": "Stock updated successfully"
}
```

---

## Cart Endpoints

### GET /cart
Get current user's cart.

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "cart": {
      "id": 1,
      "user_id": 1,
      "items": [
        {
          "id": 1,
          "cart_id": 1,
          "product_id": 1,
          "quantity": 2,
          "price": "999.99",
          "product": {
            "id": 1,
            "name": "iPhone 15 Pro",
            "sku": "IPHONE-15-PRO-001",
            "image_url": "https://images.unsplash.com/..."
          }
        }
      ]
    },
    "total": 1999.98,
    "total_quantity": 2
  },
  "message": "Cart retrieved successfully"
}
```

### POST /cart/items
Add item to cart.

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "product_id": 1,
  "quantity": 2
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "cart": {
      "id": 1,
      "user_id": 1,
      "items": [...]
    },
    "total": 1999.98,
    "total_quantity": 2
  },
  "message": "Item added to cart successfully"
}
```

### PATCH /cart/items/{id}
Update cart item quantity.

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "quantity": 3
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "cart": {
      "id": 1,
      "user_id": 1,
      "items": [...]
    },
    "total": 2999.97,
    "total_quantity": 3
  },
  "message": "Cart item updated successfully"
}
```

### DELETE /cart/items/{id}
Remove item from cart.

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "cart": {
      "id": 1,
      "user_id": 1,
      "items": []
    },
    "total": 0,
    "total_quantity": 0
  },
  "message": "Item removed from cart successfully"
}
```

### DELETE /cart/clear
Clear all items from cart.

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Cart cleared successfully"
}
```

---

## Order Endpoints

### GET /orders
Get user's orders with optional filtering.

**Headers:**
```http
Authorization: Bearer {token}
```

**Query Parameters:**
- `status` (string): Filter by order status
- `page` (integer): Page number

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "order_number": "QC-2025-000001",
      "user_id": 1,
      "status": "pending",
      "subtotal": "999.99",
      "tax_amount": "79.99",
      "shipping_amount": "10.00",
      "total_amount": "1089.98",
      "shipping_address": {
        "name": "John Doe",
        "address_line_1": "123 Main St",
        "city": "New York",
        "state": "NY",
        "postal_code": "10001",
        "country": "USA"
      },
      "billing_address": {...},
      "payment_method": "credit_card",
      "payment_status": "pending",
      "items": [
        {
          "id": 1,
          "order_id": 1,
          "product_id": 1,
          "product_name": "iPhone 15 Pro",
          "product_sku": "IPHONE-15-PRO-001",
          "quantity": 1,
          "unit_price": "999.99",
          "total_price": "999.99"
        }
      ],
      "created_at": "2025-07-18T01:00:00.000000Z",
      "updated_at": "2025-07-18T01:00:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 15,
    "total": 1
  },
  "message": "Orders retrieved successfully"
}
```

### POST /orders
Create a new order from cart.

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "shipping_address": {
    "name": "John Doe",
    "address_line_1": "123 Main St",
    "address_line_2": "Apt 4B",
    "city": "New York",
    "state": "NY",
    "postal_code": "10001",
    "country": "USA"
  },
  "billing_address": {
    "name": "John Doe",
    "address_line_1": "123 Main St",
    "city": "New York",
    "state": "NY",
    "postal_code": "10001",
    "country": "USA"
  },
  "payment_method": "credit_card",
  "notes": "Please deliver during business hours"
}
```

**Response (201):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "order_number": "QC-2025-000001",
    "user_id": 1,
    "status": "pending",
    "subtotal": "999.99",
    "tax_amount": "79.99",
    "shipping_amount": "10.00",
    "total_amount": "1089.98",
    "shipping_address": {...},
    "billing_address": {...},
    "payment_method": "credit_card",
    "payment_status": "pending",
    "notes": "Please deliver during business hours",
    "items": [...],
    "created_at": "2025-07-18T01:00:00.000000Z",
    "updated_at": "2025-07-18T01:00:00.000000Z"
  },
  "message": "Order created successfully"
}
```

### GET /orders/{id}
Get single order by ID.

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "order_number": "QC-2025-000001",
    // ... full order details with items
  },
  "message": "Order retrieved successfully"
}
```

### PATCH /orders/{id}/cancel
Cancel an order (only if status is pending or processing).

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "order_number": "QC-2025-000001",
    "status": "cancelled",
    // ... other order details
  },
  "message": "Order cancelled successfully"
}
```

### PATCH /orders/{id}/status
Update order status (Admin only).

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "status": "shipped"
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "order_number": "QC-2025-000001",
    "status": "shipped",
    "shipped_at": "2025-07-18T02:00:00.000000Z",
    // ... other order details
  },
  "message": "Order status updated successfully"
}
```

---

## Error Codes

### HTTP Status Codes
- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Unprocessable Entity (Validation Error)
- `500` - Internal Server Error

### Common Error Responses

#### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

#### Unauthorized (401)
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

#### Not Found (404)
```json
{
  "success": false,
  "message": "Product not found"
}
```

#### Server Error (500)
```json
{
  "success": false,
  "message": "Internal server error",
  "error": "Error details..."
}
```

---

## Rate Limiting
The API implements rate limiting to prevent abuse:
- **General endpoints**: 60 requests per minute
- **Authentication endpoints**: 5 requests per minute
- **Cart operations**: 30 requests per minute

Rate limit headers are included in responses:
```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1642694400
```

## Testing the API

### Using cURL

#### Health Check
```bash
curl -X GET https://quickcart-production.up.railway.app/api/health
```

#### Register User
```bash
curl -X POST https://quickcart-production.up.railway.app/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### Get Products
```bash
curl -X GET "https://quickcart-production.up.railway.app/api/v1/products?page=1&per_page=5"
```

#### Get Simple Products
```bash
curl -X GET https://quickcart-production.up.railway.app/api/products-simple
```

### Using Postman
Import the API endpoints into Postman and set up environment variables:
- `base_url`: https://quickcart-production.up.railway.app/api
- `token`: Your authentication token

This API documentation provides comprehensive information for integrating with the QuickCart e-commerce platform.
