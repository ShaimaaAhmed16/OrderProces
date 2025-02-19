<?php
/**
 * Created by PhpStorm.
 * User: shimaa
 * Date: 2/19/2025
 * Time: 12:24 AM
 */

namespace App\Utils;


use App\Models\Cart;
use App\Repository\CartRepository;

class OrdersTotalPrice
{
    public static function totalPrice($user_id){
        $carts = Cart::whereUserId($user_id)->get();
        $total = 0;
        foreach ($carts as $cart){
          $subTotal = $cart->price*$cart->quantity;
            $total += $subTotal;
        }
        return $total;
    }

}