<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
class CartController extends Controller {
 public function add(Request $request, Product $product){
  $cart=session('cart',[]); $cart[$product->id]=($cart[$product->id]??0)+1; session(['cart'=>$cart]);
  return back()->with('success','محصول به سبد خرید اضافه شد.');
 }
 public function clear(){session()->forget('cart');return back()->with('success','سبد خرید پاک شد.');}
}
