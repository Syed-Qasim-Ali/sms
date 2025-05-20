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
        Schema::create('users_arrivals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User ID as foreign key
            $table->uuid('ticket_uuid'); // UUID column
            $table->foreign('ticket_uuid')->references('uuid')->on('tickets')->onDelete('cascade'); // Foreign key constraint
            $table->enum('arrival_status', ['pending', 'arrived', 'departed'])->default('pending');
            $table->decimal('location_lat', 10, 7)->nullable();
            $table->decimal('location_lng', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_arrivals');
    }
};
