<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::table('products', function(Blueprint $table){
   $table->string('brand')->nullable()->after('name');
   $table->string('origin')->nullable()->after('brand');
   $table->text('description')->nullable()->after('origin');
   $table->json('specs')->nullable()->after('description');
   $table->decimal('tax_percent',5,2)->default(10)->after('old_price');
   $table->decimal('weight',10,2)->nullable()->after('unit');
   $table->string('loading_location')->nullable()->after('stock');
   $table->boolean('allow_quote')->default(true)->after('featured');
  });
  Schema::create('price_histories', function(Blueprint $table){
   $table->id(); $table->foreignId('product_id')->constrained()->cascadeOnDelete();
   $table->unsignedBigInteger('price'); $table->timestamp('recorded_at'); $table->timestamps();
   $table->index(['product_id','recorded_at']);
  });
  Schema::create('warehouses', function(Blueprint $table){
   $table->id(); $table->string('name'); $table->string('city'); $table->string('address')->nullable();
   $table->boolean('active')->default(true); $table->timestamps();
  });
  Schema::create('warehouse_stocks', function(Blueprint $table){
   $table->id(); $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
   $table->foreignId('product_id')->constrained()->cascadeOnDelete(); $table->integer('qty')->default(0);
   $table->unique(['warehouse_id','product_id']);
  });
 }
 public function down(): void {
  Schema::dropIfExists('warehouse_stocks'); Schema::dropIfExists('warehouses'); Schema::dropIfExists('price_histories');
  Schema::table('products', function(Blueprint $table){
   $table->dropColumn(['brand','origin','description','specs','tax_percent','weight','loading_location','allow_quote']);
  });
 }
};