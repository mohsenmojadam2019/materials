<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProjectQuote extends Model {
    protected $fillable=['customer_id','code','project_name','status','estimated_amount','requested_at'];
    protected $casts=['estimated_amount'=>'integer','requested_at'=>'datetime'];
    public function customer():BelongsTo{return $this->belongsTo(Customer::class);}
}
