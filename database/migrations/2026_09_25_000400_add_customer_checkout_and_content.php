<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::create('addresses', function(Blueprint $table){
   $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete();
   $table->string('title')->default('آدرس اصلی'); $table->string('recipient');
   $table->string('phone'); $table->string('province'); $table->string('city');
   $table->text('address'); $table->string('postal_code')->nullable();
   $table->boolean('is_default')->default(false); $table->timestamps();
  });
  Schema::table('orders', function(Blueprint $table){
   $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
   $table->string('recipient')->nullable()->after('shipping_status');
   $table->string('phone')->nullable()->after('recipient');
   $table->string('province')->nullable()->after('phone');
   $table->string('city')->nullable()->after('province');
   $table->text('shipping_address')->nullable()->after('city');
   $table->string('postal_code')->nullable()->after('shipping_address');
   $table->string('payment_method')->default('online')->after('postal_code');
   $table->string('shipping_method')->default('carrier')->after('payment_method');
   $table->string('invoice_type')->default('personal')->after('shipping_method');
   $table->text('notes')->nullable()->after('invoice_type');
  });
  Schema::create('articles', function(Blueprint $table){
   $table->id(); $table->string('title'); $table->string('slug')->unique();
   $table->string('excerpt')->nullable(); $table->longText('body'); $table->string('image')->nullable();
   $table->string('category')->default('راهنمای خرید'); $table->boolean('published')->default(true);
   $table->timestamp('published_at')->nullable(); $table->timestamps();
  });
 }
 public function down(): void {
  Schema::dropIfExists('articles');
  Schema::table('orders', function(Blueprint $table){
   $table->dropConstrainedForeignId('user_id');
   $table->dropColumn(['recipient','phone','province','city','shipping_address','postal_code','payment_method','shipping_method','invoice_type','notes']);
  });
  Schema::dropIfExists('addresses');
 }
};