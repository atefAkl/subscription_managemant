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
        Schema::create('ss_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key_string', 36)->unique();
            $table->string('uuid', 10)->nullable();
            $table->foreignId('device_type_id')->nullable()->constrained('device_types')->onDelete('cascade');
            $table->foreignId('group_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->datetime('activated_at')->nullable();
            $table->datetime('blocked_at')->nullable();
            $table->enum('status', ['active', 'new', 'blocked', 'expired'])->default('new');
            $table->enum('period', ['week', 'month', 'year'])->default('year');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->engine('InnoDB');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keys');
    }
};
