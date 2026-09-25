<?php
namespace App\Http\Controllers;

use App\Models\{Address,Customer,Order,OrderItem,Product,Shipment,FinancialTransaction};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $cart=session('cart',[]);
        abort_if(!$cart,404);

        $products=Product::whereIn('id',array_keys($cart))->get()->keyBy('id');
        $items=[]; $subtotal=0;

        foreach($cart as $id=>$qty){
            if(!$products->has($id)) continue;
            $product=$products[$id];
            $qty=min((int)$qty,$product->stock);
            $line=$product->price*$qty;
            $subtotal+=$line;
            $items[]=['product'=>$product,'qty'=>$qty,'line_total'=>$line];
        }

        $shipping=$subtotal>=500000000?0:3500000;

        return view('checkout.index',[
            'items'=>$items,
            'subtotal'=>$subtotal,
            'shipping'=>$shipping,
            'addresses'=>$request->user()->addresses()->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data=$request->validate([
            'recipient'=>['required','string','max:120'],
            'phone'=>['required','string','max:20'],
            'province'=>['required','string','max:80'],
            'city'=>['required','string','max:80'],
            'shipping_address'=>['required','string','max:500'],
            'postal_code'=>['nullable','string','max:20'],
            'shipping_method'=>['required','in:carrier,company,pickup'],
            'payment_method'=>['required','in:online,card,bank_transfer,on_delivery'],
            'invoice_type'=>['required','in:personal,legal'],
            'notes'=>['nullable','string','max:1000'],
            'save_address'=>['nullable','boolean'],
        ]);

        $cart=session('cart',[]);
        if(!$cart){
            return redirect()->route('cart.index')->withErrors(['cart'=>'سبد خرید خالی است.']);
        }

        $order=DB::transaction(function() use ($request,$data,$cart){
            $products=Product::whereIn('id',array_keys($cart))->lockForUpdate()->get()->keyBy('id');
            $subtotal=0; $lines=[];

            foreach($cart as $id=>$qty){
                $product=$products->get($id);
                if(!$product || $product->stock<$qty){
                    abort(422,'موجودی یکی از کالاها کافی نیست.');
                }
                $line=$product->price*$qty;
                $subtotal+=$line;
                $lines[]=[$product,$qty,$line];
            }

            $shipping=match($data['shipping_method']){
                'pickup'=>0,
                'company'=>$subtotal>=500000000?0:6500000,
                default=>$subtotal>=500000000?0:3500000,
            };

            $customer=Customer::updateOrCreate(
                ['phone'=>$data['phone']],
                [
                    'name'=>$data['recipient'],
                    'type'=>$data['invoice_type']==='legal'?'legal':'individual',
                    'city'=>$data['city'],
                    'status'=>'active',
                ]
            );

            $order=Order::create(array_merge($data,[
                'user_id'=>$request->user()->id,
                'customer_id'=>$customer->id,
                'order_no'=>'ORD-'.now()->format('ymdHis').'-'.random_int(10,99),
                'type'=>'retail',
                'status'=>'processing',
                'payment_status'=>$data['payment_method']==='on_delivery'?'pending':'unpaid',
                'shipping_status'=>'preparing',
                'subtotal'=>$subtotal,
                'shipping'=>$shipping,
                'total'=>$subtotal+$shipping,
                'ordered_at'=>now(),
            ]));

            foreach($lines as [$product,$qty,$line]){
                OrderItem::create([
                    'order_id'=>$order->id,
                    'product_id'=>$product->id,
                    'qty'=>$qty,
                    'unit_price'=>$product->price,
                    'total'=>$line,
                ]);
                $product->decrement('stock',$qty);
            }

            Shipment::create([
                'order_id'=>$order->id,
                'carrier'=>$data['shipping_method']==='pickup'?'تحویل حضوری':'تخصیص پس از آماده‌سازی',
                'status'=>'preparing',
                'destination'=>$data['city'],
            ]);

            FinancialTransaction::create([
                'order_id'=>$order->id,
                'type'=>'receivable',
                'amount'=>$order->total,
                'status'=>'pending',
                'due_date'=>today()->addDays(3),
            ]);

            if($request->boolean('save_address')){
                Address::create([
                    'user_id'=>$request->user()->id,
                    'title'=>'آدرس سفارش',
                    'recipient'=>$data['recipient'],
                    'phone'=>$data['phone'],
                    'province'=>$data['province'],
                    'city'=>$data['city'],
                    'address'=>$data['shipping_address'],
                    'postal_code'=>$data['postal_code'],
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('account.order',$order)->with('success','سفارش با موفقیت ثبت شد.');
    }
}