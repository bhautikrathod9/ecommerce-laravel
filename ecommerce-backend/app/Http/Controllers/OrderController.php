<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        try {
            $orders = $this->orderService->getUserOrders();
            return OrderResource::collection($orders);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($orderId)
    {
        try {
            $order = $this->orderService->getOrder($orderId);
            return new OrderResource($order);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Order not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500'
        ]);

        try {
            $order = $this->orderService->createOrderFromCart($request->shipping_address);

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => new OrderResource($order)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to place order',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        try {
            $order = $this->orderService->updateOrderStatus($orderId, $request->status);

            return response()->json([
                'message' => 'Order status updated successfully',
                'order' => new OrderResource($order)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update order status',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}