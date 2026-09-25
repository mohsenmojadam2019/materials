<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    StorefrontController,CartController,QuoteController,AuthController,CatalogController,
    CheckoutController,AccountController,PasswordController,B2BController,ContentController
};
use App\Http\Controllers\Admin\{
    DashboardController,ProductController,OrderController,ModuleController,ManagementController
};

Route::get('/',[StorefrontController::class,'index'])->name('home');
Route::get('/product/{product:slug}',[CatalogController::class,'show'])->name('product.show');
Route::get('/wishlist',[CatalogController::class,'wishlist'])->name('wishlist');
Route::post('/wishlist/{product}',[CatalogController::class,'toggleWishlist'])->name('wishlist.toggle');
Route::get('/compare',[CatalogController::class,'compare'])->name('compare');
Route::post('/compare/{product}',[CatalogController::class,'toggleCompare'])->name('compare.toggle');

Route::get('/cart',[CartController::class,'index'])->name('cart.index');
Route::post('/cart/{product}',[CartController::class,'add'])->name('cart.add');
Route::patch('/cart/{product}',[CartController::class,'update'])->name('cart.update');
Route::delete('/cart/{product}',[CartController::class,'remove'])->name('cart.remove');
Route::delete('/cart',[CartController::class,'clear'])->name('cart.clear');

Route::post('/project-quote',[QuoteController::class,'store'])->name('quote.store');

Route::get('/blog',[ContentController::class,'blog'])->name('blog');
Route::get('/blog/{article:slug}',[ContentController::class,'article'])->name('article');
Route::get('/faq',[ContentController::class,'faq'])->name('faq');
Route::get('/sitemap.xml',[ContentController::class,'sitemap'])->name('sitemap');
Route::get('/robots.txt',[ContentController::class,'robots'])->name('robots');

Route::middleware('guest')->group(function(){
    Route::get('/login',[AuthController::class,'showLogin'])->name('login');
    Route::get('/admin/login',[AuthController::class,'showLogin'])->name('admin.login');
    Route::post('/login',[AuthController::class,'login'])->name('login.submit');
    Route::get('/register',[AuthController::class,'showRegister'])->name('register');
    Route::post('/register',[AuthController::class,'register'])->name('register.submit');
    Route::get('/forgot-password',[PasswordController::class,'request'])->name('password.request');
    Route::post('/forgot-password',[PasswordController::class,'email'])->name('password.email');
    Route::get('/reset-password/{token}',[PasswordController::class,'reset'])->name('password.reset');
    Route::post('/reset-password',[PasswordController::class,'update'])->name('password.update');
});
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function(){
    Route::get('/checkout',[CheckoutController::class,'show'])->name('checkout');
    Route::post('/checkout',[CheckoutController::class,'store'])->name('checkout.store');
    Route::get('/account',[AccountController::class,'dashboard'])->name('account.dashboard');
    Route::get('/account/profile',[AccountController::class,'profile'])->name('account.profile');
    Route::patch('/account/profile',[AccountController::class,'updateProfile'])->name('account.profile.update');
    Route::get('/account/orders',[AccountController::class,'orders'])->name('account.orders');
    Route::get('/account/orders/{order}',[AccountController::class,'order'])->name('account.order');
    Route::get('/account/addresses',[AccountController::class,'addresses'])->name('account.addresses');
    Route::post('/account/addresses',[AccountController::class,'storeAddress'])->name('account.addresses.store');
    Route::delete('/account/addresses/{address}',[AccountController::class,'deleteAddress'])->name('account.addresses.delete');
    Route::get('/business/{type}',[B2BController::class,'create'])->where('type','wholesale|credit')->name('b2b.create');
    Route::post('/business/{type}',[B2BController::class,'store'])->where('type','wholesale|credit')->name('b2b.store');
});

Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function(){
    Route::get('/',[DashboardController::class,'index'])->name('dashboard');
    Route::get('/products',[ProductController::class,'index'])->name('products');
    Route::post('/products',[ManagementController::class,'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit',[ManagementController::class,'editProduct'])->name('products.edit');
    Route::patch('/products/{product}',[ManagementController::class,'updateProduct'])->name('products.update');
    Route::delete('/products/{product}',[ManagementController::class,'deleteProduct'])->name('products.delete');

    Route::get('/orders',[OrderController::class,'index'])->name('orders');
    Route::patch('/orders/{order}',[ManagementController::class,'updateOrder'])->name('orders.update');
    Route::get('/orders-export.csv',[ManagementController::class,'exportOrders'])->name('orders.export');

    Route::get('/categories',[ManagementController::class,'categories'])->name('categories');
    Route::post('/categories',[ManagementController::class,'storeCategory'])->name('categories.store');
    Route::get('/inventory',[ManagementController::class,'inventory'])->name('inventory');
    Route::post('/inventory',[ManagementController::class,'updateStock'])->name('inventory.update');

    Route::get('/projects',[ManagementController::class,'quotes'])->name('quotes');
    Route::patch('/projects/{quote}',[ManagementController::class,'updateQuote'])->name('quotes.update');

    Route::get('/customers',[ManagementController::class,'customers'])->name('customers');
    Route::get('/finance',[ManagementController::class,'finance'])->name('finance');
    Route::get('/reports',[ManagementController::class,'reports'])->name('reports');
    Route::get('/content',[ManagementController::class,'content'])->name('content');
    Route::post('/content',[ManagementController::class,'storeArticle'])->name('content.store');
    Route::get('/settings',[ManagementController::class,'settings'])->name('settings');
    Route::post('/settings',[ManagementController::class,'saveSettings'])->name('settings.save');

    Route::get('/{module}',[ModuleController::class,'show'])
        ->where('module','suppliers|pricing|logistics|users|discounts|tickets')
        ->name('module');
});