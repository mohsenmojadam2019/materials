<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up():void {
  Schema::create('b2b_requests',function(Blueprint $table){
   $table->id(); $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
   $table->string('type'); $table->string('company_name')->nullable(); $table->string('national_id')->nullable();
   $table->string('contact_name'); $table->string('phone'); $table->unsignedBigInteger('amount')->default(0);
   $table->unsignedInteger('term_days')->default(0); $table->text('description')->nullable();
   $table->string('status')->default('new'); $table->timestamps();
  });
  Schema::create('store_settings',function(Blueprint $table){
   $table->id(); $table->string('key')->unique(); $table->text('value')->nullable(); $table->timestamps();
  });
  Schema::create('faqs',function(Blueprint $table){
   $table->id(); $table->string('question'); $table->text('answer'); $table->unsignedInteger('sort_order')->default(0);
   $table->boolean('active')->default(true); $table->timestamps();
  });
  Schema::table('project_quotes',function(Blueprint $table){
   $table->string('proforma_no')->nullable()->after('code');
   $table->text('notes')->nullable()->after('estimated_amount');
  });
 }
 public function down():void {
  Schema::table('project_quotes',function(Blueprint $table){$table->dropColumn(['proforma_no','notes']);});
  Schema::dropIfExists('faqs'); Schema::dropIfExists('store_settings'); Schema::dropIfExists('b2b_requests');
 }
};