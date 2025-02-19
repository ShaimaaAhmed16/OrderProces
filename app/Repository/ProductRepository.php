<?php
/**
 * Created by PhpStorm.
 * User: shimaa
 * Date: 2/18/2025
 * Time: 1:44 AM
 */

namespace App\Repository;


use App\Models\Product;

class ProductRepository
{
    private $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getAll(){
        $products = $this->product->get()->pagint(10);
        return $products;
    }

    public function saveProduct($request){
        $product = $this->product->create($request);
        return $product;
    }
     public function getId($id){
         $product = $this->product->whereId($id)->first();
         return $product;
     }
    public function updateProduct($id,$request){
        $product = $this->getId($id);
        $product_data = $product->update($request);
        return $product_data;
    }

}