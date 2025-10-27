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
    Schema::create('payments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('subscription_request_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->decimal('amount', 10, 2);
      $table->string('payment_method'); // تحويل بنكي، فودافون كاش، إلخ
      $table->string('transaction_reference')->nullable();
      $table->string('receipt_path')->nullable(); // مسار إيصال الدفع
      $table->enum('status', ['pending_verification', 'verified', 'rejected'])->default('pending_verification');
      $table->text('admin_notes')->nullable();
      $table->timestamp('paid_at')->nullable();
      $table->timestamp('verified_at')->nullable();
      $table->timestamps();

      $table->index(['user_id', 'status']);
      $table->index('subscription_request_id');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('payments');
  }
};
