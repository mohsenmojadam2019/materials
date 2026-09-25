<?php
namespace App\Http\Controllers;
use App\Models\B2BRequest;
use Illuminate\Http\Request;
class B2BController extends Controller {
 public function create(string $type){
  abort_unless(in_array($type,["wholesale","credit"]),404);
  return view("b2b.create",compact("type"));
 }
 public function store(Request $request,string $type){
  abort_unless(in_array($type,["wholesale","credit"]),404);
  $data=$request->validate([
   "company_name"=>["nullable","string","max:150"],"national_id"=>["nullable","string","max:50"],"contact_name"=>["required","string","max:120"],
   "phone"=>["required","string","max:20"],"amount"=>["nullable","integer","min:0"],"term_days"=>["nullable","integer","min:0","max:365"],
   "description"=>["nullable","string","max:2000"],
  ]);
  $request->user()->b2bRequests()->create(array_merge($data,["type"=>$type,"status"=>"new"]));
  return redirect()->route("account.dashboard")->with("success","درخواست شما ثبت شد.");
 }
}
