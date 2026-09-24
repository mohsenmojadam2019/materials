<?php
namespace App\Http\Controllers;
use App\Models\{Category,Product};
use Illuminate\Http\Request;
class StorefrontController extends Controller {
 public function index(Request $request){
  $query=trim((string)$request->get('q'));
  $products=Product::with(['category','supplier'])->where('active',true)
   ->when($query,fn($q)=>$q->where(fn($w)=>$w->where('name','like',"%{$query}%")->orWhere('sku','like',"%{$query}%")->orWhereHas('category',fn($c)=>$c->where('name','like',"%{$query}%"))))
   ->orderByDesc('featured')->latest()->get();
  return view('storefront',[
   'categories'=>Category::where('active',true)->orderBy('sort_order')->withCount('products')->get(),
   'products'=>$products,'market'=>Product::whereNot('market_change',0)->take(4)->get(),
   'cartCount'=>array_sum(session('cart',[])),'query'=>$query,
  ]);
 }
}
