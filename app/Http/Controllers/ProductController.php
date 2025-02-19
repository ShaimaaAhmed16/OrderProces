<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Category;
use App\Repository\ProductRepository;
use App\Utils\ImageUpload;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $pRepository;
    public function __construct(ProductRepository $pRepository)
    {
        $this->pRepository = $pRepository;
    }

    public function index(){
      $products = $this->pRepository->getAll();
        return view('products.index',compact('products'));
    }

    public function create(){
        $categories = Category::pluck('name','id');
        return view('products.create',compact('categories'));
    }

    public function store(ProductStoreRequest $request){
        try{
            $data = $request->validated();
            if ($request->image) {
                $data['image'] = ImageUpload::uploadImage($request->image);
            }
            $product = $this->pRepository->saveProduct($data);
            return response()->json([
                'message' => 'Product added successfully',
                'product' => $product
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Failed to add product',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function edit($id){
        $categories = Category::pluck('name','id');
        $product = $this->pRepository->getId($id);
        return view('products.create',compact('categories','product'));
    }
    public function update(ProductUpdateRequest $request,$id){
        try{
            $data = $request->validated();
            if ($request->image) {
                $product = $this->pRepository->getId($id);
                $oldImagePath = storage_path('app/public/' . $product->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $data['image'] = ImageUpload::uploadImage($request->image);
            }
            $product = $this->pRepository->updateProduct($id,$data);
            return response()->json([
                'message' => 'updated added successfully',
                'product' => $product
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Failed to add product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id){
        try{
            $product = $this->pRepository->getId($id);
            $product->delete();
            return response()->json([
                'message' => 'Delete added successfully',
                'product' => $product
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Failed to add product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
