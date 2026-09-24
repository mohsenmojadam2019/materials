<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Product extends Model {
    protected $fillable=['category_id','supplier_id','name','slug','sku','unit','price','old_price','stock','min_stock','image','featured','active','market_change'];
    protected $casts=['featured'=>'boolean','active'=>'boolean','price'=>'integer','old_price'=>'integer','stock'=>'integer','min_stock'=>'integer','market_change'=>'float'];
    public function category():BelongsTo{return $this->belongsTo(Category::class);}
    public function supplier():BelongsTo{return $this->belongsTo(Supplier::class);}
    public function getLowStockAttribute():bool{return $this->stock <= $this->min_stock;}
}
