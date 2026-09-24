<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Order extends Model {
    protected $fillable=['customer_id','order_no','type','status','payment_status','shipping_status','subtotal','shipping','total','ordered_at'];
    protected $casts=['subtotal'=>'integer','shipping'=>'integer','total'=>'integer','ordered_at'=>'datetime'];
    public function customer():BelongsTo{return $this->belongsTo(Customer::class);}
    public function items():HasMany{return $this->hasMany(OrderItem::class);}
}
