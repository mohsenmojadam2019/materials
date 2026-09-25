<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Shipment extends Model {
 protected $fillable=["order_id","carrier","tracking_code","status","destination","dispatched_at"];
 protected $casts=["dispatched_at"=>"datetime"];
 public function order():BelongsTo{return $this->belongsTo(Order::class);}
}