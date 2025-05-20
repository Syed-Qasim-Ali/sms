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
        Schema::create('backlogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truck_id')->references('id')->on('trucks');
            $table->unsignedBigInteger('time_slot_id');
            $table->foreign('time_slot_id')->references('id')->on('order_time_slot');
            $table->string('order_number');
            $table->enum('status', ['pending', 'assigned'])->default('pending');
            $table->enum('priority', ['high', 'medium', 'low'])->default('high');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backlogs');
    }
};
