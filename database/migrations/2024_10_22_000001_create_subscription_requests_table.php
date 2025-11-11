<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('subscription_requests', function (Blueprint $table) {
      $table->engine('innodb');
      $table->id();
      $table->timestamp('activated_at')->nullable();
      $table->timestamp('expires_at')->nullable();
      $table->timestamp('suspended_at')->nullable();
      $table->timestamp('renewed_at')->nullable();
      $table->text('suspension_reason')->nullable();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->string('subscription_name');
      $table->integer('device_count');
      $table->date('proposed_start_date');
      $table->text('notes')->nullable();
      $table->enum('status', ['pending', 'quoted', 'paid', 'active', 'approved', 'rejected'])->default('pending');
      $table->decimal('quoted_price', 10, 2)->nullable();
      $table->string('payment_method')->nullable();
      $table->text('admin_notes')->nullable();
      $table->timestamp('quoted_at')->nullable();
      $table->string('payment_receipt')->nullable()->comment('مسار إيصال الدفع');
      $table->timestamp('paid_at')->nullable()->comment('تاريخ الدفع');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('subscription_requests');
  }
};
