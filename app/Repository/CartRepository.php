<?php
/**
 * Created by PhpStorm.
 * User: shimaa
 * Date: 2/18/2025
 * Time: 11:11 PM
 */

namespace App\Repository;


use App\Models\Cart;
use App\Models\Product;

class CartRepository
{
    private $cart;
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function getAll(){
       $user_id = auth()->user()->id();
        $carts = $this->cart->whereUserId($user_id)->get()->pagint(10);
        return $carts;
    }
    public function getCount(){
       $user_id = auth()->user()->id();
        $count = $this->cart->whereUserId($user_id)->count();
        return $count;
    }
    public function getId($user_id){
        $get_cart = $this->cart->whereUserId($user_id)->first();
        return $get_cart;
    }

    public function saveCart($request){
        $add_cart = $this->cart->create($request);
        return $add_cart;
    }
    public function updateCart($id,$request){
        $get_cart = $this->cart->whereId($id)->first();
        $update_cart = $get_cart->update($request);
        return $update_cart;
    }

    public function productPrice($id){
        $product = Product::whereId($id)->first();
        return $product->price;

    }



}