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
        Schema::create('admin_profiles', function (Blueprint $table) {
            $table->engine('innodb');
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->integer('permissions_level')->default(1);
            $table->integer('access_level')->default(1);
            $table->text('notes')->nullable();
            $table->json('preferences')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('access_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_profiles');
    }
};
