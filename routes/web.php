<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{StorefrontController,CartController,QuoteController,AuthController};
use App\Http\Controllers\Admin\{DashboardController,ProductController,OrderController,ModuleController};

Route::get('/',[StorefrontController::class,'index'])->name('home');
Route::post('/cart/{product}',[CartController::class,'add'])->name('cart.add');
Route::delete('/cart',[CartController::class,'clear'])->name('cart.clear');
Route::post('/project-quote',[QuoteController::class,'store'])->name('quote.store');

Route::middleware('guest')->group(function(){
    Route::get('/login',[AuthController::class,'showLogin'])->name('login');
    Route::get('/admin/login',[AuthController::class,'showLogin'])->name('admin.login');
    Route::post('/login',[AuthController::class,'login'])->name('login.submit');
    Route::get('/register',[AuthController::class,'showRegister'])->name('register');
    Route::post('/register',[AuthController::class,'register'])->name('register.submit');
});
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function(){
    Route::get('/',[DashboardController::class,'index'])->name('dashboard');
    Route::get('/products',[ProductController::class,'index'])->name('products');
    Route::get('/orders',[OrderController::class,'index'])->name('orders');
    Route::get('/{module}',[ModuleController::class,'show'])
        ->where('module','categories|inventory|suppliers|pricing|customers|projects|logistics|finance|reports|users|discounts|content|tickets|settings')
        ->name('module');
});
