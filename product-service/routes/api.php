<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

// Product API routes
Route::prefix('v1')->group(function () {
    // Product CRUD operations
    Route::apiResource('products', ProductController::class);

    // Additional product endpoints
    Route::patch('products/{id}/stock', [ProductController::class, 'updateStock']);
});

// Legacy auth route (can be removed if not needed)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
