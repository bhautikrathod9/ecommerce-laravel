<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        try {
            $cartItems = $this->cartService->getUserCart();
            $total = $this->cartService->getCartTotal();

            return response()->json([
                'cart_items' => CartResource::collection($cartItems),
                'total' => $total,
                'count' => $cartItems->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(CartRequest $request)
    {
        try {
            $cartItem = $this->cartService->addToCart(
                $request->product_id,
                $request->quantity
            );

            return response()->json([
                'message' => 'Item added to cart successfully',
                'cart_item' => new CartResource($cartItem)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $cartId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $cartItem = $this->cartService->updateCartItem($cartId, $request->quantity);

            return response()->json([
                'message' => 'Cart item updated successfully',
                'cart_item' => new CartResource($cartItem)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update cart item',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy($cartId)
    {
        try {
            $this->cartService->removeFromCart($cartId);

            return response()->json([
                'message' => 'Item removed from cart successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to remove item from cart',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function clear()
    {
        try {
            $this->cartService->clearCart();

            return response()->json([
                'message' => 'Cart cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to clear cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}