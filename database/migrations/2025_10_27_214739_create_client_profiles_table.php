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
        Schema::create('client_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('subscription_type', ['basic', 'premium', 'enterprise'])->default('basic');
            $table->enum('subscription_status', ['active', 'inactive', 'suspended', 'expired', 'trial'])->default('trial');
            $table->date('subscription_start_date')->nullable();
            $table->date('subscription_end_date')->nullable();
            $table->integer('device_limit')->default(1);
            $table->integer('devices_count')->default(0);
            $table->enum('payment_status', ['paid', 'pending', 'overdue', 'failed'])->default('pending');
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->text('client_notes')->nullable();
            $table->json('preferences')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('subscription_type');
            $table->index('subscription_status');
            $table->index('payment_status');
            $table->index('subscription_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_profiles');
    }
};
