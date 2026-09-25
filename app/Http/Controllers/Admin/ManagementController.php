<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{
    Product,Category,Warehouse,WarehouseStock,Order,ProjectQuote,Customer,
    FinancialTransaction,Article,StoreSetting,B2BRequest
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ManagementController extends Controller
{
    protected function publicUpload(Request $request,string $field,string $folder): ?string
    {
        if(! $request->hasFile($field)) return null;

        $file=$request->file($field);
        $name=Str::uuid().'.'.$file->getClientOriginalExtension();
        $dir=public_path('assets/uploads/'.$folder);

        if(! is_dir($dir)){
            mkdir($dir,0775,true);
        }

        $file->move($dir,$name);

        return '/assets/uploads/'.$folder.'/'.$name;
    }

    public function editProduct(Product $product)
    {
        return view('admin.product-edit',[
            'product'=>$product,
            'categories'=>Category::orderBy('sort_order')->get(),
        ]);
    }

    public function storeProduct(Request $request)
    {
        $data=$request->validate([
            'name'=>['required','string','max:190'],
            'category_id'=>['required','exists:categories,id'],
            'sku'=>['required','string','max:80','unique:products,sku'],
            'unit'=>['required','string','max:30'],
            'price'=>['required','integer','min:0'],
            'stock'=>['required','integer','min:0'],
            'brand'=>['nullable','string','max:120'],
            'origin'=>['nullable','string','max:100'],
            'loading_location'=>['nullable','string','max:150'],
            'description'=>['nullable','string'],
            'tax_percent'=>['nullable','numeric','min:0','max:100'],
            'image'=>['nullable','image','max:5120'],
        ]);

        $image=$this->publicUpload($request,'image','products');

        $data['slug']=Str::slug($data['sku']).'-'.Str::lower(Str::random(4));
        $data['min_stock']=max(1,(int)($request->min_stock ?? 10));
        $data['active']=true;
        $data['featured']=$request->boolean('featured');
        $data['allow_quote']=true;
        $data['image']=$image ?: '/assets/img/products/cement.svg';

        Product::create($data);

        return back()->with('success','محصول ایجاد شد.');
    }

    public function updateProduct(Request $request,Product $product)
    {
        $data=$request->validate([
            'name'=>['required','string','max:190'],
            'category_id'=>['required','exists:categories,id'],
            'unit'=>['required','string','max:30'],
            'price'=>['required','integer','min:0'],
            'stock'=>['required','integer','min:0'],
            'brand'=>['nullable','string','max:120'],
            'loading_location'=>['nullable','string','max:150'],
            'image'=>['nullable','image','max:5120'],
        ]);

        if($product->price !== (int)$data['price']){
            DB::table('price_histories')->insert([
                'product_id'=>$product->id,
                'price'=>(int)$data['price'],
                'recorded_at'=>now(),
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }

        if($image=$this->publicUpload($request,'image','products')){
            $data['image']=$image;
        }

        $product->update($data);

        return back()->with('success','محصول بروزرسانی شد.');
    }

    public function deleteProduct(Product $product)
    {
        abort_if($product->items()->exists(),422,'این محصول در سفارش استفاده شده است.');
        $product->delete();
        return back()->with('success','محصول حذف شد.');
    }

    public function categories()
    {
        return view('admin.categories',[
            'categories'=>Category::withCount('products')->orderBy('sort_order')->get(),
        ]);
    }

    public function storeCategory(Request $request)
    {
        $data=$request->validate([
            'name'=>['required','string','max:120'],
            'slug'=>['required','string','max:120','unique:categories,slug'],
        ]);

        Category::create($data+[
            'active'=>true,
            'sort_order'=>Category::max('sort_order')+1,
            'image'=>'/assets/img/products/brick.svg',
        ]);

        return back()->with('success','دسته‌بندی ایجاد شد.');
    }

    public function inventory()
    {
        return view('admin.inventory',[
            'warehouses'=>Warehouse::with(['stocks.product'])->get(),
            'products'=>Product::orderBy('name')->get(),
        ]);
    }

    public function updateStock(Request $request)
    {
        $data=$request->validate([
            'warehouse_id'=>['required','exists:warehouses,id'],
            'product_id'=>['required','exists:products,id'],
            'qty'=>['required','integer','min:0'],
        ]);

        WarehouseStock::updateOrCreate(
            ['warehouse_id'=>$data['warehouse_id'],'product_id'=>$data['product_id']],
            ['qty'=>$data['qty']]
        );

        Product::whereKey($data['product_id'])->update([
            'stock'=>WarehouseStock::where('product_id',$data['product_id'])->sum('qty'),
        ]);

        return back()->with('success','موجودی بروزرسانی شد.');
    }

    public function updateOrder(Request $request,Order $order)
    {
        $data=$request->validate([
            'status'=>['required','string'],
            'payment_status'=>['required','string'],
            'shipping_status'=>['required','string'],
        ]);

        $order->update($data);

        if($order->shipments()->exists()){
            $order->shipments()->latest()->first()->update(['status'=>$data['shipping_status']]);
        }

        return back()->with('success','وضعیت سفارش بروزرسانی شد.');
    }

    public function updateQuote(Request $request,ProjectQuote $quote)
    {
        $data=$request->validate([
            'status'=>['required','string'],
            'estimated_amount'=>['required','integer','min:0'],
            'proforma_no'=>['nullable','string','max:100'],
            'notes'=>['nullable','string','max:2000'],
        ]);

        $quote->update($data);

        return back()->with('success','استعلام بروزرسانی شد.');
    }

    public function quotes()
    {
        return view('admin.quotes',[
            'quotes'=>ProjectQuote::with('customer')->latest('requested_at')->get(),
            'b2b'=>B2BRequest::latest()->get(),
        ]);
    }

    public function customers()
    {
        return view('admin.customers',[
            'customers'=>Customer::withCount('orders')->latest()->get(),
        ]);
    }

    public function finance()
    {
        return view('admin.finance',[
            'transactions'=>FinancialTransaction::with('order')->latest()->get(),
            'receivable'=>FinancialTransaction::where('status','pending')->sum('amount'),
            'paid'=>FinancialTransaction::where('status','paid')->sum('amount'),
        ]);
    }

    public function content()
    {
        return view('admin.content',[
            'articles'=>Article::latest()->get(),
        ]);
    }

    public function storeArticle(Request $request)
    {
        $data=$request->validate([
            'title'=>['required','string','max:190'],
            'excerpt'=>['nullable','string','max:300'],
            'body'=>['required','string'],
            'category'=>['required','string','max:80'],
            'image'=>['nullable','image','max:5120'],
        ]);

        $data['slug']=Str::slug($data['title']).'-'.Str::lower(Str::random(4));
        $data['published']=true;
        $data['published_at']=now();

        if($image=$this->publicUpload($request,'image','blog')){
            $data['image']=$image;
        }

        Article::create($data);

        return back()->with('success','مقاله منتشر شد.');
    }

    public function settings()
    {
        return view('admin.settings',[
            'settings'=>StoreSetting::pluck('value','key'),
        ]);
    }

    public function saveSettings(Request $request)
    {
        $data=$request->validate([
            'store_name'=>['required','string','max:120'],
            'store_phone'=>['required','string','max:30'],
            'store_email'=>['required','email'],
            'store_address'=>['required','string','max:300'],
            'primary_color'=>['required','string','max:20'],
            'store_logo'=>['nullable','image','max:5120'],
        ]);

        unset($data['store_logo']);

        if($logo=$this->publicUpload($request,'store_logo','brand')){
            $data['store_logo']=$logo;
        }

        foreach($data as $key=>$value){
            StoreSetting::updateOrCreate(['key'=>$key],['value'=>$value]);
        }

        return back()->with('success','تنظیمات ذخیره شد.');
    }

    public function reports()
    {
        $sales=Order::selectRaw('date(ordered_at) d, SUM(total) total, COUNT(*) count')
            ->groupBy('d')->orderByDesc('d')->take(30)->get();

        return view('admin.reports',[
            'sales'=>$sales,
            'inventoryValue'=>Product::selectRaw('SUM(price*stock) t')->value('t') ?? 0,
            'receivable'=>FinancialTransaction::where('status','pending')->sum('amount'),
        ]);
    }

    public function exportOrders()
    {
        $rows=Order::with('user')->latest()->get();
        $csv="order_no,user,total,status,payment,shipping\n";

        foreach($rows as $order){
            $name=str_replace('"','',$order->user?->name ?? '');
            $csv.=$order->order_no.',"'.$name.'",'.$order->total.','.$order->status.','.$order->payment_status.','.$order->shipping_status."\n";
        }

        return response("\xEF\xBB\xBF".$csv,200,[
            'Content-Type'=>'text/csv; charset=UTF-8',
            'Content-Disposition'=>'attachment; filename=orders.csv',
        ]);
    }
}