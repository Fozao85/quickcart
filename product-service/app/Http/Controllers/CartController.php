<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Get the current user's cart
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $cart = $this->getOrCreateCart($request);
            $cart->load(['items.product']);

            return response()->json([
                'success' => true,
                'data' => [
                    'cart' => $cart,
                    'total' => $cart->total,
                    'total_quantity' => $cart->total_quantity,
                ],
                'message' => 'Cart retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add item to cart
     */
    public function addItem(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $product = Product::findOrFail($validated['product_id']);
            
            // Check if product is active and in stock
            if (!$product->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is not available'
                ], 400);
            }

            if ($product->stock_quantity < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock available'
                ], 400);
            }

            $cart = $this->getOrCreateCart($request);

            // Check if item already exists in cart
            $cartItem = $cart->items()->where('product_id', $product->id)->first();

            if ($cartItem) {
                // Update quantity
                $newQuantity = $cartItem->quantity + $validated['quantity'];
                
                if ($product->stock_quantity < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock available'
                    ], 400);
                }

                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                // Create new cart item
                $cartItem = $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                    'price' => $product->price,
                ]);
            }

            $cart->load(['items.product']);

            return response()->json([
                'success' => true,
                'data' => [
                    'cart' => $cart,
                    'total' => $cart->total,
                    'total_quantity' => $cart->total_quantity,
                ],
                'message' => 'Item added to cart successfully'
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
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateItem(Request $request, $itemId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $cart = $this->getOrCreateCart($request);
            $cartItem = $cart->items()->findOrFail($itemId);
            $product = $cartItem->product;

            if ($product->stock_quantity < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock available'
                ], 400);
            }

            $cartItem->update(['quantity' => $validated['quantity']]);
            $cart->load(['items.product']);

            return response()->json([
                'success' => true,
                'data' => [
                    'cart' => $cart,
                    'total' => $cart->total,
                    'total_quantity' => $cart->total_quantity,
                ],
                'message' => 'Cart item updated successfully'
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
                'message' => 'Failed to update cart item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request, $itemId): JsonResponse
    {
        try {
            $cart = $this->getOrCreateCart($request);
            $cartItem = $cart->items()->findOrFail($itemId);
            $cartItem->delete();

            $cart->load(['items.product']);

            return response()->json([
                'success' => true,
                'data' => [
                    'cart' => $cart,
                    'total' => $cart->total,
                    'total_quantity' => $cart->total_quantity,
                ],
                'message' => 'Item removed from cart successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove cart item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all items from cart
     */
    public function clear(Request $request): JsonResponse
    {
        try {
            $cart = $this->getOrCreateCart($request);
            $cart->items()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get or create cart for the current user/session
     */
    private function getOrCreateCart(Request $request): Cart
    {
        if (Auth::check()) {
            // For authenticated users
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            // For guest users, use session
            $sessionId = $request->session()->getId();
            return Cart::firstOrCreate(['session_id' => $sessionId]);
        }
    }
}
