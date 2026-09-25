<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class WarehouseStock extends Model {
 public $timestamps=false;
 protected $fillable=['warehouse_id','product_id','qty'];
 protected $casts=['qty'=>'integer'];
 public function warehouse():BelongsTo{return $this->belongsTo(Warehouse::class);}
 public function product():BelongsTo{return $this->belongsTo(Product::class);}
}