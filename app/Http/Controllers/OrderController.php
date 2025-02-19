<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrdersStoreRequest;
use App\Http\Requests\OrdersUpdateRequest;
use App\Models\Cart;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Utils\OrdersTotalPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public $orderRepository;
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function addOrders(OrdersStoreRequest $request){
        try{
            $user_id = auth()->user()->id();
            $cartRep = app(CartRepository::class);
            $cart = $cartRep->getId($user_id);
            if ($cart){
                DB::beginTransaction();
                $data = $request->validated();
                $data['user_id'] = $user_id;
                $data['name'] = auth()->user()->name;
                $data['total_price'] = OrdersTotalPrice::totalPrice($user_id) + ($request->shipping_price ?? 0);
                $order = $this->orderRepository->saveOrder($data,$user_id);
                // order details and delete cart
                $order = $this->orderRepository->orderDetail($user_id,$order->id);
                DB::commit();
                return response()->json([
                    'message' => 'order added successfully',
                    'order' => $order
                ], 201);
            }
            return response()->json([
                'error' => 'There are no products in the cart',
            ], 400);
        }catch (\Exception $e){
            DB::rollback();
            return response()->json([
                'message' => 'Failed to add order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $order = $this->orderRepository->showOrder($id);
        return view('orders.show', compact('order'));
    }

    public function updateOrderWithItems($id,OrdersUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $order = $this->orderRepository->updateOrder($id,$data);
            DB::commit();
            return response()->json([
                'message' => 'Order and items updated successfully',
                'order' => $order->load('orderDetails')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
