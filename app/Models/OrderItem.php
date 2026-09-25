<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class OrderItem extends Model {
 protected $fillable=["order_id","product_id","qty","unit_price","total"];
 public $timestamps=false;
 public function product():BelongsTo{return $this->belongsTo(Product::class);}
 public function order():BelongsTo{return $this->belongsTo(Order::class);}
}
