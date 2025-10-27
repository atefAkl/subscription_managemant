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
    Schema::create('devices', function (Blueprint $table) {
      $table->id();
      $table->foreignId('subscription_id')->constrained()->onDelete('cascade');

      // iPhone-specific fields
      $table->string('device_identifier', 10)->comment('رقم مميز من 10 خانات');
      $table->string('iphone_model')->comment('طراز الآيفون');
      $table->string('device_nickname')->nullable()->comment('اسم مخصص للجهاز');
      $table->string('serial_number')->nullable()->comment('الرقم التسلسلي للجهاز');
      $table->json('device_info')->nullable()->comment('معلومات إضافية عن الجهاز');
      $table->timestamp('last_token_update')->nullable()->comment('آخر تحديث للرمز المميز');

      // Original device fields
      $table->string('device_number')->nullable();
      $table->string('device_version')->nullable();
      $table->string('device_name')->nullable();
      $table->string('machine_name')->nullable();
      $table->string('token')->nullable();
      $table->enum('status', ['pending', 'active', 'disabled', 'blocked'])->default('pending');
      $table->timestamp('activated_at')->nullable();
      $table->timestamp('last_connected_at')->nullable();
      $table->string('ip_address')->nullable();

      $table->timestamps();

      // Indexes
      $table->unique('device_identifier');
      $table->index(['subscription_id', 'status']);
      $table->index('iphone_model');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('devices');
  }
};
