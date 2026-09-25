<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Warehouse extends Model {
 protected $fillable=['name','city','address','active'];
 protected $casts=['active'=>'boolean'];
 public function stocks():HasMany{return $this->hasMany(WarehouseStock::class);}
}