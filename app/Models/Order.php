<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo,HasMany};
class Order extends Model {
 protected $fillable=["user_id","customer_id","order_no","type","status","payment_status","shipping_status","subtotal","shipping","total","recipient","phone","province","city","shipping_address","postal_code","payment_method","shipping_method","invoice_type","notes","ordered_at"];
 protected $casts=["subtotal"=>"integer","shipping"=>"integer","total"=>"integer","ordered_at"=>"datetime"];
 public function user():BelongsTo{return $this->belongsTo(User::class);}
 public function customer():BelongsTo{return $this->belongsTo(Customer::class);}
 public function items():HasMany{return $this->hasMany(OrderItem::class);}
 public function shipments():HasMany{return $this->hasMany(Shipment::class);}
}
