<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OrderItem extends Model {
    protected $fillable=['order_id','product_id','qty','unit_price','total'];
    public $timestamps=false;
}
