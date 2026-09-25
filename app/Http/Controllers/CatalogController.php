<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function show(Product $product)
    {
        abort_unless($product->active,404);
        $product->load(['category','supplier','priceHistories','warehouseStocks.warehouse']);
        $related=Product::with('supplier')->where('active',true)
            ->where('category_id',$product->category_id)->whereKeyNot($product->id)->take(4)->get();

        return view('catalog.show',[
            'product'=>$product,
            'related'=>$related,
            'wishlist'=>session('wishlist',[]),
            'compare'=>session('compare',[]),
        ]);
    }

    public function wishlist()
    {
        $ids=session('wishlist',[]);
        return view('catalog.wishlist',[
            'products'=>Product::with(['supplier','category'])->whereIn('id',$ids)->get(),
        ]);
    }

    public function toggleWishlist(Product $product)
    {
        $ids=session('wishlist',[]);
        $ids=in_array($product->id,$ids)?array_values(array_diff($ids,[$product->id])):array_values(array_unique([...$ids,$product->id]));
        session(['wishlist'=>$ids]);
        return back()->with('success',in_array($product->id,$ids)?'به علاقه‌مندی‌ها اضافه شد.':'از علاقه‌مندی‌ها حذف شد.');
    }

    public function compare()
    {
        $ids=session('compare',[]);
        return view('catalog.compare',[
            'products'=>Product::with(['supplier','category'])->whereIn('id',$ids)->get(),
        ]);
    }

    public function toggleCompare(Product $product)
    {
        $ids=session('compare',[]);
        if(in_array($product->id,$ids)){
            $ids=array_values(array_diff($ids,[$product->id]));
            $message='از مقایسه حذف شد.';
        }elseif(count($ids)>=4){
            return back()->withErrors(['compare'=>'حداکثر ۴ محصول را می‌توانید همزمان مقایسه کنید.']);
        }else{
            $ids[]=$product->id;
            $message='به مقایسه اضافه شد.';
        }
        session(['compare'=>$ids]);
        return back()->with('success',$message);
    }
}