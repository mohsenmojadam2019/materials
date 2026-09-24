<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FinancialTransaction extends Model {
    protected $fillable=['order_id','type','amount','status','due_date'];
    protected $casts=['amount'=>'integer','due_date'=>'date'];
}
