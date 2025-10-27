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
        Schema::create('subscription_request_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_request_id')->constrained()->onDelete('cascade');

            // iPhone-specific fields
            $table->string('device_identifier', 10)->comment('رقم مميز من 10 خانات');
            $table->string('iphone_model')->comment('طراز الآيفون');
            $table->string('device_nickname')->comment('اسم مخصص للجهاز');
            $table->text('special_requirements')->nullable()->comment('متطلبات خاصة');

            $table->timestamps();

            // Indexes
            $table->unique('device_identifier');
            $table->index('subscription_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_request_devices');
    }
};
