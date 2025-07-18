<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Get user's orders
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Auth::user()->orders()->with('items.product');

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Sort by creation date (newest first)
            $orders = $query->orderBy('created_at', 'desc')->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $orders->items(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                ],
                'message' => 'Orders retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific order
     */
    public function show($id): JsonResponse
    {
        try {
            $order = Auth::user()->orders()->with('items.product')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create a new order from cart
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'shipping_address' => 'required|array',
                'shipping_address.name' => 'required|string|max:255',
                'shipping_address.address_line_1' => 'required|string|max:255',
                'shipping_address.address_line_2' => 'nullable|string|max:255',
                'shipping_address.city' => 'required|string|max:255',
                'shipping_address.state' => 'required|string|max:255',
                'shipping_address.postal_code' => 'required|string|max:20',
                'shipping_address.country' => 'required|string|max:255',
                'billing_address' => 'required|array',
                'billing_address.name' => 'required|string|max:255',
                'billing_address.address_line_1' => 'required|string|max:255',
                'billing_address.address_line_2' => 'nullable|string|max:255',
                'billing_address.city' => 'required|string|max:255',
                'billing_address.state' => 'required|string|max:255',
                'billing_address.postal_code' => 'required|string|max:20',
                'billing_address.country' => 'required|string|max:255',
                'payment_method' => 'required|string|in:credit_card,debit_card,paypal,stripe',
                'notes' => 'nullable|string|max:1000',
            ]);

            // Get user's cart
            $cart = Auth::user()->cart()->with('items.product')->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty'
                ], 400);
            }

            // Check stock availability for all items
            foreach ($cart->items as $item) {
                if ($item->product->stock_quantity < $item->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for product: {$item->product->name}"
                    ], 400);
                }
            }

            DB::beginTransaction();

            try {
                // Calculate totals
                $subtotal = $cart->total;
                $taxAmount = $subtotal * 0.08; // 8% tax rate
                $shippingAmount = $subtotal > 100 ? 0 : 10; // Free shipping over $100
                $totalAmount = $subtotal + $taxAmount + $shippingAmount;

                // Create order
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => Auth::id(),
                    'status' => 'pending',
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'shipping_amount' => $shippingAmount,
                    'total_amount' => $totalAmount,
                    'shipping_address' => $validated['shipping_address'],
                    'billing_address' => $validated['billing_address'],
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'pending',
                    'notes' => $validated['notes'] ?? null,
                ]);

                // Create order items and update stock
                foreach ($cart->items as $item) {
                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'product_sku' => $item->product->sku,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->price,
                        'total_price' => $item->quantity * $item->price,
                    ]);

                    // Update product stock
                    $item->product->decrement('stock_quantity', $item->quantity);
                }

                // Clear the cart
                $cart->items()->delete();

                DB::commit();

                $order->load('items.product');

                return response()->json([
                    'success' => true,
                    'data' => $order,
                    'message' => 'Order created successfully'
                ], 201);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order status (admin only)
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            ]);

            $order = Order::findOrFail($id);
            $order->update(['status' => $validated['status']]);

            // Set timestamps for status changes
            if ($validated['status'] === 'shipped' && !$order->shipped_at) {
                $order->update(['shipped_at' => now()]);
            } elseif ($validated['status'] === 'delivered' && !$order->delivered_at) {
                $order->update(['delivered_at' => now()]);
            }

            $order->load('items.product');

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order status updated successfully'
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
                'message' => 'Failed to update order status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel an order
     */
    public function cancel($id): JsonResponse
    {
        try {
            $order = Auth::user()->orders()->findOrFail($id);

            if (!in_array($order->status, ['pending', 'processing'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order cannot be cancelled'
                ], 400);
            }

            DB::beginTransaction();

            try {
                // Restore stock quantities
                foreach ($order->items as $item) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }

                // Update order status
                $order->update(['status' => 'cancelled']);

                DB::commit();

                $order->load('items.product');

                return response()->json([
                    'success' => true,
                    'data' => $order,
                    'message' => 'Order cancelled successfully'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
