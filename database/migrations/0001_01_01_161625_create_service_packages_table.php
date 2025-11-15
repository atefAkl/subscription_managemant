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
        Schema::create('service_packages', function (Blueprint $table) {
            $table->engine('innodb');
            $table->id();
            $table->string('name', 45)->nullable(false)->unique();
            $table->text('description', 255);
            $table->decimal('price', 10, 2);
            $table->integer('duration')->default(1);
            $table->enum('duration_unit', ['months', 'years', 'days', 'weeks'])->default('days');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_packages');
    }
};
