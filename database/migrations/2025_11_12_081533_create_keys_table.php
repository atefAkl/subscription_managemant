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
        Schema::create('keys', function (Blueprint $table) {
            $table->id();
            $table->string('key_string', 36)->unique();
            $table->string('uuid', 10)->nullable();
            $table->string('device_type', 10)->nullable();
            $table->foreignId('group_item_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'new', 'blocked', 'expired'])->default('new');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
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
