<?php
namespace App\Http\Controllers;
use App\Models\{Address,Order};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class AccountController extends Controller {
 public function dashboard(Request $request){
  $orders=$request->user()->orders()->with("items.product")->latest("ordered_at")->take(5)->get();
  return view("account.dashboard",compact("orders"));
 }
 public function profile(){return view("account.profile");}
 public function updateProfile(Request $request){
  $user=$request->user();$data=$request->validate(["name"=>["required","string","max:120"],"email"=>["required","email",Rule::unique("users")->ignore($user->id)]]);
  $user->update($data);return back()->with("success","پروفایل بروزرسانی شد.");
 }
 public function orders(Request $request){return view("account.orders",["orders"=>$request->user()->orders()->latest("ordered_at")->paginate(15)]);}
 public function order(Request $request,Order $order){
  abort_unless($order->user_id===$request->user()->id,403);$order->load(["items.product","shipments"]);return view("account.order",compact("order"));
 }
 public function addresses(Request $request){return view("account.addresses",["addresses"=>$request->user()->addresses()->latest()->get()]);}
 public function storeAddress(Request $request){
  $data=$request->validate(["title"=>["required","string","max:80"],"recipient"=>["required","string","max:120"],"phone"=>["required","string","max:20"],"province"=>["required","string"],"city"=>["required","string"],"address"=>["required","string"],"postal_code"=>["nullable","string"]]);
  $request->user()->addresses()->create($data);return back()->with("success","آدرس اضافه شد.");
 }
 public function deleteAddress(Request $request,Address $address){abort_unless($address->user_id===$request->user()->id,403);$address->delete();return back()->with("success","آدرس حذف شد.");}
}
