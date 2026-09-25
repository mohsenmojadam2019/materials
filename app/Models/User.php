<?php
namespace App\Models;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(["name","email","password","role"])]
#[Hidden(["password","remember_token"])]
class User extends Authenticatable {
 use HasFactory, Notifiable;
 public function addresses():HasMany{return $this->hasMany(Address::class);}
 public function orders():HasMany{return $this->hasMany(Order::class);}
 public function b2bRequests():HasMany{return $this->hasMany(B2BRequest::class);}
 protected function casts():array{return ["email_verified_at"=>"datetime","password"=>"hashed"];}
}
