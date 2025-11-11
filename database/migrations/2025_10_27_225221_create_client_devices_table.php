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
        Schema::create('client_devices', function (Blueprint $table) {
            $table->engine('innodb');
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->string('device_name');
            $table->string('device_serial', 50)->unique()->nullable();
            $table->enum('device_type', ['iphone', 'ipad', 'mac', 'apple_tv', 'apple_watch'])->default('iphone');
            $table->string('device_model', 100)->nullable();
            $table->string('ios_version', 20)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('activation_date')->nullable();
            $table->timestamp('last_connection')->nullable();
            $table->json('device_info')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('subscription_id')->references('id')->on('client_profiles')->onDelete('cascade');

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index('device_serial');
            $table->index('last_connection');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_devices');
    }
};
