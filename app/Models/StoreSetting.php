<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class StoreSetting extends Model {
 protected $fillable=["key","value"];
 public static function valueOf(string $key,?string $fallback=null):?string{
  return static::query()->where("key",$key)->value("value") ?? $fallback;
 }
}
