<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
class PasswordController extends Controller {
 public function request(){return view("auth.forgot-password");}
 public function email(Request $request){
  $request->validate(["email"=>["required","email"]]);
  $status=Password::sendResetLink($request->only("email"));
  return $status===Password::RESET_LINK_SENT?back()->with("success",__($status)):back()->withErrors(["email"=>__($status)]);
 }
 public function reset(Request $request,string $token){return view("auth.reset-password",["token"=>$token,"email"=>$request->query("email")]);}
 public function update(Request $request){
  $request->validate(["token"=>["required"],"email"=>["required","email"],"password"=>["required","confirmed","min:8"]]);
  $status=Password::reset($request->only("email","password","password_confirmation","token"),function($user,$password){
   $user->forceFill(["password"=>Hash::make($password)])->setRememberToken(Str::random(60));$user->save();event(new PasswordReset($user));
  });
  return $status===Password::PASSWORD_RESET?redirect()->route("login")->with("success",__($status)):back()->withErrors(["email"=>__($status)]);
 }
}
