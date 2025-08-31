<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function createOrderFromCart($shippingAddress)
    {
        $cartItems = $this->cartService->getUserCart();
        
        if ($cartItems->isEmpty()) {
            throw new \Exception('Cart is empty');
        }

        // Check stock availability
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                throw new \Exception("Insufficient stock for product: {$item->product->name}");
            }
        }

        return DB::transaction(function () use ($cartItems, $shippingAddress) {
            $totalAmount = $this->cartService->getCartTotal();

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'shipping_address' => $shippingAddress,
                'status' => 'pending',
            ]);

            // Create order items and update stock
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);

                // Update product stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Clear cart
            $this->cartService->clearCart();

            return $order->load('orderItems.product');
        });
    }

    public function getUserOrders()
    {
        return Order::with('orderItems.product')
                   ->where('user_id', Auth::id())
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    public function getOrder($orderId)
    {
        return Order::with('orderItems.product')
                   ->where('user_id', Auth::id())
                   ->where('id', $orderId)
                   ->firstOrFail();
    }

    public function updateOrderStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => $status]);
        return $order;
    }
}