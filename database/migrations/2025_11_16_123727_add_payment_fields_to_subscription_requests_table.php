<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscription_requests', function (Blueprint $table) {
            // تحديث payment_method ليكون enum بدلاً من string
            DB::statement("ALTER TABLE `subscription_requests` MODIFY `payment_method` ENUM('vodafone_cash', 'etisalat_cash', 'orange_cash', 'fawry', 'bank_transfer', 'visa_card') NULL");

            // إضافة الحقول الجديدة فقط
            if (!Schema::hasColumn('subscription_requests', 'payment_verification_status')) {
                $table->enum('payment_verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('payment_method');
            }
            if (!Schema::hasColumn('subscription_requests', 'payment_verified_by')) {
                $table->foreignId('payment_verified_by')->nullable()->constrained('users')->onDelete('set null')->after('payment_verification_status');
            }
            if (!Schema::hasColumn('subscription_requests', 'payment_verified_at')) {
                $table->timestamp('payment_verified_at')->nullable()->after('payment_verified_by');
            }
            if (!Schema::hasColumn('subscription_requests', 'payment_verification_notes')) {
                $table->text('payment_verification_notes')->nullable()->after('payment_verified_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_requests', function (Blueprint $table) {
            $table->dropForeign(['payment_verified_by']);
            $table->dropColumn([
                'payment_method',
                'payment_verification_status',
                'payment_verified_by',
                'payment_verified_at',
                'payment_verification_notes'
            ]);
        });
    }
};
