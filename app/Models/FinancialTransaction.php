<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class FinancialTransaction extends Model {
 protected $fillable=["order_id","type","amount","status","due_date"];
 protected $casts=["amount"=>"integer","due_date"=>"date"];
 public function order():BelongsTo{return $this->belongsTo(Order::class);}
}