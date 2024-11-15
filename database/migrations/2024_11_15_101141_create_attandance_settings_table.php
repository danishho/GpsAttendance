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
        Schema::create('attandance_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('check_in_latitude', 8,6)->nullable();
            $table->decimal('check_in_longitude', 9,6)->nullable();
            $table->decimal('check_out_latitude', 8,6)->nullable();
            $table->decimal('check_out_longitude', 9,6)->nullable();
            $table->decimal('radius', 5,2)->nullable();
            $table->decimal('min_hour', 5, 2)->nullable();
            $table->decimal('max_hour', 5, 2)->nullable();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attandance_settings');
    }
};
