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
        Schema::create('trailers', function (Blueprint $table) {
            $table->id();
            $table->string('trailer_number');
            $table->unsignedBigInteger('truck_id');
            $table->decimal('rate_modifier', 5, 2)->nullable(); // e.g., 10.00 = +10%
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('truck_id')->references('id')->on('trucks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trailers');
    }
};
