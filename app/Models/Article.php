<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Article extends Model {
 protected $fillable=["title","slug","excerpt","body","image","category","published","published_at"];
 protected $casts=["published"=>"boolean","published_at"=>"datetime"];
 public function getRouteKeyName():string{return "slug";}
}
