<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class B2BRequest extends Model {
 protected $table="b2b_requests";
 protected $fillable=["user_id","type","company_name","national_id","contact_name","phone","amount","term_days","description","status"];
 protected $casts=["amount"=>"integer","term_days"=>"integer"];
 public function user():BelongsTo{return $this->belongsTo(User::class);}
}
