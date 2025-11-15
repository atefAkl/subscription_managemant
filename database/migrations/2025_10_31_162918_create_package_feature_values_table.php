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
        Schema::create('package_feature_values', function (Blueprint $table) {
            $table->engine('innodb');
            $table->id();
            $table->foreignId('service_package_id')->constrained('service_packages')->cascadeOnDelete();
            $table->foreignId('package_feature_id')->constrained('package_features')->cascadeOnDelete();
            $table->string('value', 45)->nullable(false);
            $table->string('value_type')->nullable(true)->default('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_feature_values');
    }
};
