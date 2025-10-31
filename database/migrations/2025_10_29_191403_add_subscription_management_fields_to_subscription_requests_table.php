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
            $table->timestamp('activated_at')->nullable()->after('quoted_at');
            $table->timestamp('expires_at')->nullable()->after('activated_at');
            $table->timestamp('suspended_at')->nullable()->after('expires_at');
            $table->timestamp('renewed_at')->nullable()->after('suspended_at');
            $table->text('suspension_reason')->nullable()->after('renewed_at');
            // admin_notes already exists, so we don't need to add it again
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_requests', function (Blueprint $table) {
            $table->dropColumn([
                'activated_at',
                'expires_at',
                'suspended_at',
                'renewed_at',
                'suspension_reason'
            ]);
        });
    }
};
