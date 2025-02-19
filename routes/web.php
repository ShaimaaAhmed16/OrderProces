<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => 'cart'], function () {
     Route::POST('add',[CartController::class,'store']);
     Route::POST('update/{id}',[CartController::class,'update']);
});
Route::group(['prefix' => 'order'], function () {
     Route::POST('add',[OrderController::class,'addOrders']);
     Route::get('show/{id}',[OrderController::class,'show']);
     Route::put('update/{id}',[OrderController::class,'updateOrderWithItems']);
});
Route::group(['prefix' => 'products'], function () {
    Route::get('index',[ProductController::class,'index']);
    Route::get('create',[ProductController::class,'create']);
    Route::POST('store',[ProductController::class,'store']);
    Route::get('edit/{id}',[ProductController::class,'edit']);
    Route::put('update/{id}',[ProductController::class,'update']);
});
