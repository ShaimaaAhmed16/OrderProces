<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartStoreRequest;
use App\Repository\CartRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public $cartRepository;
    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function index(){
        $carts = $this->cartRepository->getAll();
        $cart_count = $this->cartRepository->getCount();
        return view('carts.index',compact('carts','cart_count'));

    }

    public function store(CartStoreRequest $request){
        try{
            $data = $request->validated();
            $data['user_id'] = auth()->user()->id();
            $data['price'] = $this->cartRepository->productPrice($request->product_id);
            $cart = $this->cartRepository->saveCart($data);
            return response()->json([
                'message' => 'Cart added successfully',
                'cart' => $cart
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Failed to add cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update($id,CartStoreRequest $request){
        try{
            $data = $request->validated();
          $data['user_id'] = auth()->user()->id();
            $cart = $this->cartRepository->updateCart($id,$data);
            return response()->json([
                'message' => 'Cart updated successfully',
                'product' => $cart
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Failed to update cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id){
        try{
            $delete_cart = $this->cartRepository->whereId($id)->first();
            $delete_cart->delete();
            return response()->json([
                'message' => 'Deleted successfully',
                'cart' => $delete_cart
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Failed to add cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
