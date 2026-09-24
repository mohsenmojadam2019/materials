<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Product,Category,Supplier};
use Illuminate\Http\Request;
class ProductController extends Controller {
 public function index(Request $request){
  $q=trim((string)$request->get('q')); $category=$request->get('category');
  $products=Product::with(['category','supplier'])->when($q,fn($x)=>$x->where(fn($w)=>$w->where('name','like',"%{$q}%")->orWhere('sku','like',"%{$q}%")))
   ->when($category,fn($x)=>$x->where('category_id',$category))->latest()->paginate(20)->withQueryString();
  return view('admin.products',['products'=>$products,'categories'=>Category::orderBy('sort_order')->get(),'suppliers'=>Supplier::all(),
   'productCount'=>Product::count(),'lowCount'=>Product::whereColumn('stock','<=','min_stock')->count(),'inventoryValue'=>Product::selectRaw('SUM(price * stock) total')->value('total')??0]);
 }
}
