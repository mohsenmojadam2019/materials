<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Customer extends Model {
    protected $fillable=['name','type','phone','company','city','credit_limit','status'];
    protected $casts=['credit_limit'=>'integer'];
    public function orders():HasMany{return $this->hasMany(Order::class);}
}
