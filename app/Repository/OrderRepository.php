<?php
/**
 * Created by PhpStorm.
 * User: shimaa
 * Date: 2/18/2025
 * Time: 11:11 PM
 */

namespace App\Repository;


use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use function Symfony\Component\Uid\Factory\create;

class OrderRepository
{
    private $order;
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getAll(){
       $user_id = auth()->user()->id();
        $carts = $this->order->whereUserId($user_id)->get()->pagint(10);;
        return $carts;
    }

    public function saveOrder($request){
        $add_order = $this->order->create($request);
        return $add_order;
    }

    public function showOrder($id){
       $orderData = $this->order->where('id', $id)->where('user_id', 1)->with('orderDetails.product')->firstOrFail();
       return $orderData;
    }

    public function orderDetail($user_id,$order_id){
        $carts = Cart::whereUserId($user_id)->get();
        $order_details = '';
        foreach ($carts as $cart){
            $order_details = OrderDetail::create([
               'order_id' => $order_id,
               'product_id' => $cart->product_id,
               'price' => $cart->price,
               'quantity' => $cart->quantity
           ]);
            $cart->delete();
        }
        return $order_details;
    }

    public function updateOrder($id,$data){
        $orderData = $this->order->findOrFail($id);
        $orderData->update([
            'payment_method' => $data['payment_method'],
            'payment_status' => $data['payment_status'],
            'total_price' => $data['total_price'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'city' => $data['city'],
            'postal_code' => $data['postal_code'],
            'country' => $data['country'],
            'shipping_price' => $data['shipping_price']

        ]);
        foreach ($data['items'] as $itemData) {
            if (isset($itemData['id'])) {
                OrderDetail::where('id', $itemData['id'])->update([
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'order_id' => $orderData->id,
                ]);
            } else {
                $orderData->orderDetails()->create($itemData);
            }
        }

    }

}