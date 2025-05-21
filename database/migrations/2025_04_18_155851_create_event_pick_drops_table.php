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
        Schema::create('event_pick_drops', function (Blueprint $table) {
            $table->id();
            $table->uuid('ticket_uuid');
            $table->foreign('ticket_uuid')->references('uuid')->on('tickets')->onDelete('cascade');
            $table->decimal('pickup_lat', 10, 7)->nullable();
            $table->decimal('pickup_lng', 10, 7)->nullable();
            $table->decimal('drop_lat', 10, 7)->nullable();
            $table->decimal('drop_lng', 10, 7)->nullable();
            $table->string('material')->nullable();
            $table->float('quantity')->nullable();
            $table->string('image')->nullable(); // Store image path

            $table->dateTime('pickup_time')->nullable();
            $table->dateTime('drop_time')->nullable();

      $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_pick_drops');
    }
};
