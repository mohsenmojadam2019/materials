<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo,HasMany};
class Product extends Model {
 protected $fillable=[
  'category_id','supplier_id','name','brand','origin','description','specs','slug','sku','unit','weight',
  'price','old_price','tax_percent','stock','loading_location','min_stock','image','featured','allow_quote','active','market_change'
 ];
 protected $casts=[
  'featured'=>'boolean','allow_quote'=>'boolean','active'=>'boolean','price'=>'integer','old_price'=>'integer',
  'stock'=>'integer','min_stock'=>'integer','market_change'=>'float','tax_percent'=>'float','weight'=>'float','specs'=>'array'
 ];
 public function category():BelongsTo{return $this->belongsTo(Category::class);}
 public function supplier():BelongsTo{return $this->belongsTo(Supplier::class);}
 public function priceHistories():HasMany{return $this->hasMany(PriceHistory::class)->orderBy('recorded_at');}
 public function warehouseStocks():HasMany{return $this->hasMany(WarehouseStock::class);}
 public function items():HasMany{return $this->hasMany(OrderItem::class);}
 public function getRouteKeyName():string{return 'slug';}
 public function getLowStockAttribute():bool{return $this->stock <= $this->min_stock;}
 public function getPriceWithTaxAttribute():int{return (int)round($this->price*(1+$this->tax_percent/100));}
}