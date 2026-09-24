<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::create('categories', function(Blueprint $table){
   $table->id(); $table->string('name'); $table->string('slug')->unique();
   $table->string('image')->nullable(); $table->unsignedInteger('sort_order')->default(0);
   $table->boolean('active')->default(true); $table->timestamps();
  });
  Schema::create('suppliers', function(Blueprint $table){
   $table->id(); $table->string('name'); $table->string('phone')->nullable();
   $table->string('city')->nullable(); $table->string('status')->default('active'); $table->timestamps();
  });
  Schema::create('products', function(Blueprint $table){
   $table->id(); $table->foreignId('category_id')->constrained()->cascadeOnDelete();
   $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
   $table->string('name'); $table->string('slug')->unique(); $table->string('sku')->unique();
   $table->string('unit')->default('عدد'); $table->unsignedBigInteger('price');
   $table->unsignedBigInteger('old_price')->nullable(); $table->integer('stock')->default(0);
   $table->integer('min_stock')->default(0); $table->string('image')->nullable();
   $table->decimal('market_change',6,2)->default(0); $table->boolean('featured')->default(false);
   $table->boolean('active')->default(true); $table->timestamps();
  });
  Schema::create('customers', function(Blueprint $table){
   $table->id(); $table->string('name'); $table->string('type')->default('individual');
   $table->string('phone')->nullable(); $table->string('company')->nullable();
   $table->string('city')->nullable(); $table->unsignedBigInteger('credit_limit')->default(0);
   $table->string('status')->default('active'); $table->timestamps();
  });
  Schema::create('orders', function(Blueprint $table){
   $table->id(); $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
   $table->string('order_no')->unique(); $table->string('type')->default('retail');
   $table->string('status')->default('processing'); $table->string('payment_status')->default('pending');
   $table->string('shipping_status')->default('preparing'); $table->unsignedBigInteger('subtotal')->default(0);
   $table->unsignedBigInteger('shipping')->default(0); $table->unsignedBigInteger('total')->default(0);
   $table->timestamp('ordered_at')->nullable(); $table->timestamps();
  });
  Schema::create('order_items', function(Blueprint $table){
   $table->id(); $table->foreignId('order_id')->constrained()->cascadeOnDelete();
   $table->foreignId('product_id')->constrained()->restrictOnDelete(); $table->integer('qty');
   $table->unsignedBigInteger('unit_price'); $table->unsignedBigInteger('total');
  });
  Schema::create('project_quotes', function(Blueprint $table){
   $table->id(); $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
   $table->string('code')->unique(); $table->string('project_name'); $table->string('status')->default('new');
   $table->unsignedBigInteger('estimated_amount')->default(0); $table->timestamp('requested_at')->nullable(); $table->timestamps();
  });
  Schema::create('shipments', function(Blueprint $table){
   $table->id(); $table->foreignId('order_id')->constrained()->cascadeOnDelete();
   $table->string('carrier')->nullable(); $table->string('tracking_code')->nullable();
   $table->string('status')->default('preparing'); $table->string('destination')->nullable();
   $table->timestamp('dispatched_at')->nullable(); $table->timestamps();
  });
  Schema::create('financial_transactions', function(Blueprint $table){
   $table->id(); $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
   $table->string('type'); $table->unsignedBigInteger('amount'); $table->string('status')->default('pending');
   $table->date('due_date')->nullable(); $table->timestamps();
  });
  Schema::create('tickets', function(Blueprint $table){
   $table->id(); $table->string('subject'); $table->string('customer_name')->nullable();
   $table->string('status')->default('open'); $table->string('priority')->default('normal'); $table->timestamps();
  });
 }
 public function down(): void {
  Schema::dropIfExists('tickets'); Schema::dropIfExists('financial_transactions');
  Schema::dropIfExists('shipments'); Schema::dropIfExists('project_quotes'); Schema::dropIfExists('order_items');
  Schema::dropIfExists('orders'); Schema::dropIfExists('customers'); Schema::dropIfExists('products');
  Schema::dropIfExists('suppliers'); Schema::dropIfExists('categories');
 }
};
