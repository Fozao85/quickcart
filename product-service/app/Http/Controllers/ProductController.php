<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Product::query();

            // Apply filters
            if ($request->has('category_id')) {
                $query->byCategory($request->category_id);
            }

            if ($request->has('search')) {
                $query->search($request->search);
            }

            if ($request->has('active')) {
                $query->active();
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $products = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $products,
                'message' => 'Products retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'category_id' => 'nullable|integer',
                'image_url' => 'nullable|url',
                'sku' => 'required|string|unique:products,sku',
                'is_active' => 'boolean'
            ]);

            $product = Product::create($validated);

            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Product created successfully'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified product.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Product retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'price' => 'sometimes|numeric|min:0',
                'stock_quantity' => 'sometimes|integer|min:0',
                'category_id' => 'nullable|integer',
                'image_url' => 'nullable|url',
                'sku' => 'sometimes|string|unique:products,sku,' . $id,
                'is_active' => 'boolean'
            ]);

            $product->update($validated);

            return response()->json([
                'success' => true,
                'data' => $product->fresh(),
                'message' => 'Product updated successfully'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified product.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product stock.
     */
    public function updateStock(Request $request, string $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'quantity' => 'required|integer',
                'operation' => 'required|in:add,subtract,set'
            ]);

            switch ($validated['operation']) {
                case 'add':
                    $product->stock_quantity += $validated['quantity'];
                    break;
                case 'subtract':
                    $product->stock_quantity = max(0, $product->stock_quantity - $validated['quantity']);
                    break;
                case 'set':
                    $product->stock_quantity = max(0, $validated['quantity']);
                    break;
            }

            $product->save();

            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Stock updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all products with basic information (legacy endpoint)
     */
    public function getProductsSimple(): JsonResponse
    {
        try {
            $products = Product::where('is_active', true)
                ->select('id', 'name', 'price', 'stock_quantity', 'sku')
                ->orderBy('created_at', 'desc')
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
                'message' => 'Failed to retrieve products',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
