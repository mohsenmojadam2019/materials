<?php
namespace App\Http\Controllers;
use App\Models\{Category,Product};
use Illuminate\Http\Request;
class StorefrontController extends Controller {
 public function index(Request $request){
  $query=trim((string)$request->get('q'));$category=$request->get('category');$brand=$request->get('brand');$sort=$request->get('sort','featured');
  $products=Product::with('category')->where('active',true)
   ->when($query,fn($q)=>$q->where(fn($w)=>$w->where('name','like',"%{$query}%")->orWhere('sku','like',"%{$query}%")->orWhere('brand','like',"%{$query}%")->orWhereHas('category',fn($c)=>$c->where('name','like',"%{$query}%"))))
   ->when($category,fn($q)=>$q->whereHas('category',fn($c)=>$c->where('slug',$category)))
   ->when($brand,fn($q)=>$q->where('brand',$brand))
   ->when($request->boolean('in_stock'),fn($q)=>$q->where('stock','>',0))
   ->when($request->filled('min_price'),fn($q)=>$q->where('price','>=',(int)$request->min_price))
   ->when($request->filled('max_price'),fn($q)=>$q->where('price','<=',(int)$request->max_price));
  match($sort){'price_asc'=>$products->orderBy('price'),'price_desc'=>$products->orderByDesc('price'),'newest'=>$products->latest(),'change'=>$products->orderByDesc('market_change'),default=>$products->orderByDesc('featured')->latest()};
  return view('storefront',[
   'categories'=>Category::where('active',true)->orderBy('sort_order')->withCount('products')->get(),
   'brands'=>Product::where('active',true)->whereNotNull('brand')->distinct()->orderBy('brand')->pluck('brand'),
   'products'=>$products->get(),'market'=>Product::where('active',true)->whereNot('market_change',0)->orderByDesc('market_change')->take(4)->get(),
   'cartCount'=>array_sum(session('cart',[])),'query'=>$query,'wishlist'=>session('wishlist',[]),'compare'=>session('compare',[]),
  ]);
 }
}