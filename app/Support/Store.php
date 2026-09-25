<?php
namespace App\Support;
use App\Models\StoreSetting;
use Throwable;
class Store {
 public static function get(string $key,?string $fallback=null):?string{
  try{return StoreSetting::valueOf($key,$fallback);}catch(Throwable){return $fallback;}
 }
}