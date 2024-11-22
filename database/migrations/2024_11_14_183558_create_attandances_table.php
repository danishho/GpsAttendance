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
        Schema::create('attandances', function (Blueprint $table) {
            $table->id();
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->integer('interval_time')->default(0);
            $table->date('date')->nullable();
            $table->string('status_checkin')->nullable();
            $table->string('status_checkout')->nullable();
            $table->decimal('total_hours', 5,2)->nullable();
            $table->integer('points_earned')->nullable()->default(0);
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attandances');
    }
};
