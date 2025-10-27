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
        Schema::table('subscription_requests', function (Blueprint $table) {
            $table->string('payment_receipt')->nullable()->after('admin_notes')->comment('مسار إيصال الدفع');
            $table->timestamp('paid_at')->nullable()->after('payment_receipt')->comment('تاريخ الدفع');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_requests', function (Blueprint $table) {
            $table->dropColumn(['payment_receipt', 'paid_at']);
        });
    }
};
