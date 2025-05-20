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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('truck_id')->constrained('trucks')->onDelete('cascade');
            $table->string('order_number');
            $table->foreign('order_number')
                ->references('order_number')
                ->on('orders')
                ->onDelete('cascade');
            $table->foreignId('time_slot_id')->constrained('order_time_slot')->onDelete('cascade');
            $table->enum('status', ['pending', 'under_review', 'closed', 'open'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
