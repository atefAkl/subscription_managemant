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
    Schema::create('subscriptions', function (Blueprint $table) {
      $table->engine('innodb');
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('subscription_request_id')->nullable()->constrained()->onDelete('set null');
      $table->string('name');
      $table->integer('device_count');
      $table->decimal('price', 10, 2);
      $table->date('start_date');
      $table->date('end_date');
      $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');
      $table->text('description')->nullable();
      $table->json('features')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('subscriptions');
  }
};
