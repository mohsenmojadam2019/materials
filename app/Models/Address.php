<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Address extends Model {
 protected $fillable=["user_id","title","recipient","phone","province","city","address","postal_code","is_default"];
 protected $casts=["is_default"=>"boolean"];
 public function user():BelongsTo{return $this->belongsTo(User::class);}
}
