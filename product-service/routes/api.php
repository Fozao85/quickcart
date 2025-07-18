<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'service' => 'Product Service',
        'status' => 'healthy',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

// Simple test endpoint
Route::get('/test', function () {
    return response()->json([
        'message' => 'Test endpoint working',
        'database' => 'connected',
        'timestamp' => now()
    ]);
});

// Simple products endpoint
Route::get('/products-simple', function () {
    try {
        $products = \App\Models\Product::select('id', 'name', 'price', 'stock_quantity', 'sku')
            ->where('is_active', true)
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'count' => $products->count(),
            'data' => $products,
            'message' => 'Products retrieved successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
});

// Authentication routes (public)
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Product API routes (public)
Route::prefix('v1')->group(function () {
    // Product CRUD operations
    Route::apiResource('products', ProductController::class);

    // Additional product endpoints
    Route::patch('products/{id}/stock', [ProductController::class, 'updateStock']);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logout-all', [AuthController::class, 'logoutAll']);
    });

    // Cart routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('items', [CartController::class, 'addItem']);
        Route::patch('items/{id}', [CartController::class, 'updateItem']);
        Route::delete('items/{id}', [CartController::class, 'removeItem']);
        Route::delete('clear', [CartController::class, 'clear']);
    });

    // Order routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('{id}', [OrderController::class, 'show']);
        Route::patch('{id}/cancel', [OrderController::class, 'cancel']);

        // Admin routes (for updating order status)
        Route::patch('{id}/status', [OrderController::class, 'updateStatus']);
    });

    // Legacy user route
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
