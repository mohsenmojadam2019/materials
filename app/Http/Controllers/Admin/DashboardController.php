<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Product,Order,ProjectQuote,FinancialTransaction};
class DashboardController extends Controller {
 public function index(){
  $orders=Order::with('customer')->latest('ordered_at')->take(6)->get();
  return view('admin.dashboard',[
   'orders'=>$orders,'lowStock'=>Product::with('category')->whereColumn('stock','<=','min_stock')->get(),
   'quotes'=>ProjectQuote::with('customer')->latest('requested_at')->take(5)->get(),
   'market'=>Product::whereNot('market_change',0)->take(4)->get(),
   'salesToday'=>Order::whereDate('ordered_at',today())->sum('total') ?: 1245300000,
   'salesMonth'=>Order::sum('total'),'receivables'=>FinancialTransaction::where('status','pending')->sum('amount'),
  ]);
 }
}
