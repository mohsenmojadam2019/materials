<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Order,ProjectQuote,FinancialTransaction,Shipment,Customer};
use Illuminate\Http\Request;
class OrderController extends Controller {
 public function index(Request $request){
  $type=$request->get('type');
  $orders=Order::with('customer')->when($type,fn($q)=>$q->where('type',$type))->latest('ordered_at')->paginate(20)->withQueryString();
  return view('admin.orders',['orders'=>$orders,'quotes'=>ProjectQuote::with('customer')->latest('requested_at')->get(),
   'receivables'=>FinancialTransaction::where('status','pending')->sum('amount'),'shipments'=>Shipment::latest('dispatched_at')->take(6)->get(),
   'customer'=>Customer::with('orders')->first()]);
 }
}
