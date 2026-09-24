<?php
namespace App\Http\Controllers;
use App\Models\ProjectQuote;
use Illuminate\Http\Request;
class QuoteController extends Controller {
 public function store(Request $request){
  $data=$request->validate(['project_name'=>'required|string|max:180','estimated_amount'=>'nullable|integer|min:0']);
  ProjectQuote::create(['code'=>'REQ-'.now()->format('ymdHis'),'project_name'=>$data['project_name'],'status'=>'new','estimated_amount'=>$data['estimated_amount']??0,'requested_at'=>now()]);
  return back()->with('success','درخواست استعلام پروژه ثبت شد.');
 }
}
