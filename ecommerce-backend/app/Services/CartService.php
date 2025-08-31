<?php
// app/Services/CartService.php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function addToCart($productId, $quantity)
    {
        $product = Product::findOrFail($productId);
        
        if ($product->stock < $quantity) {
            throw new \Exception('Insufficient stock available');
        }

        $cartItem = Cart::where('user_id', Auth::id())
                       ->where('product_id', $productId)
                       ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->stock < $newQuantity) {
                throw new \Exception('Insufficient stock available');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cartItem = Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return $cartItem->load('product');
    }

    public function updateCartItem($cartId, $quantity)
    {
        $cartItem = Cart::where('id', $cartId)
                       ->where('user_id', Auth::id())
                       ->firstOrFail();

        if ($cartItem->product->stock < $quantity) {
            throw new \Exception('Insufficient stock available');
        }

        $cartItem->update(['quantity' => $quantity]);
        return $cartItem->load('product');
    }

    public function removeFromCart($cartId)
    {
        return Cart::where('id', $cartId)
                  ->where('user_id', Auth::id())
                  ->delete();
    }

    public function getUserCart()
    {
        return Cart::with('product')
                  ->where('user_id', Auth::id())
                  ->get();
    }

    public function clearCart()
    {
        return Cart::where('user_id', Auth::id())->delete();
    }

    public function getCartTotal()
    {
        return Cart::with('product')
                  ->where('user_id', Auth::id())
                  ->get()
                  ->sum(function ($item) {
                      return $item->quantity * $item->product->price;
                  });
    }
}
