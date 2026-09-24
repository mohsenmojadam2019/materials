<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{StorefrontController,CartController,QuoteController};
use App\Http\Controllers\Admin\{DashboardController,ProductController,OrderController,ModuleController};

Route::get('/',[StorefrontController::class,'index'])->name('home');
Route::post('/cart/{product}',[CartController::class,'add'])->name('cart.add');
Route::delete('/cart',[CartController::class,'clear'])->name('cart.clear');
Route::post('/project-quote',[QuoteController::class,'store'])->name('quote.store');

Route::prefix('admin')->name('admin.')->group(function(){
    Route::get('/',[DashboardController::class,'index'])->name('dashboard');
    Route::get('/products',[ProductController::class,'index'])->name('products');
    Route::get('/orders',[OrderController::class,'index'])->name('orders');
    Route::get('/{module}',[ModuleController::class,'show'])->where('module','categories|inventory|suppliers|pricing|customers|projects|logistics|finance|reports|users|discounts|content|tickets|settings')->name('module');
});
