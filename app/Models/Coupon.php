<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Coupon extends Model {
 protected $fillable=["code","type","value","min_order","starts_at","expires_at","active"];
 protected $casts=["value"=>"integer","min_order"=>"integer","starts_at"=>"datetime","expires_at"=>"datetime","active"=>"boolean"];
}
