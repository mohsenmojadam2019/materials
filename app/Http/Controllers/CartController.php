<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
class CartController extends Controller {
 public function index(){
  $raw=session("cart",[]); $products=Product::whereIn("id",array_keys($raw))->get()->keyBy("id");
  $items=[]; $subtotal=0;
  foreach($raw as $id=>$qty){
   if(!$products->has($id)) continue;
   $p=$products[$id]; $qty=max(1,min((int)$qty,$p->stock)); $line=$p->price*$qty; $subtotal+=$line;
   $items[]=["product"=>$p,"qty"=>$qty,"line_total"=>$line];
  }
  return view("cart.index",compact("items","subtotal"));
 }
 public function add(Request $request,Product $product){
  abort_if(!$product->active,404);
  if($product->stock<1) return back()->withErrors(["cart"=>"این محصول موجود نیست."]);
  $qty=max(1,(int)$request->input("qty",1)); $cart=session("cart",[]);
  $cart[$product->id]=min(($cart[$product->id]??0)+$qty,$product->stock); session(["cart"=>$cart]);
  return back()->with("success","محصول به سبد خرید اضافه شد.");
 }
 public function update(Request $request,Product $product){
  $qty=(int)$request->validate(["qty"=>["required","integer","min:0"]])["qty"];
  $cart=session("cart",[]);
  if($qty<=0) unset($cart[$product->id]); else $cart[$product->id]=min($qty,max(0,$product->stock));
  session(["cart"=>$cart]); return back()->with("success","سبد خرید بروزرسانی شد.");
 }
 public function remove(Product $product){$cart=session("cart",[]);unset($cart[$product->id]);session(["cart"=>$cart]);return back()->with("success","محصول از سبد حذف شد.");}
 public function clear(){session()->forget("cart");return back()->with("success","سبد خرید پاک شد.");}
}
