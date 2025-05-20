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
        Schema::create('trucks', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('truck_number')->unique();
            $table->string('truck_type'); // Pickup, Trailer, Tanker
            $table->string('truck_capabilities')->nullable();
            $table->string('truck_specialties')->nullable();
            $table->string('registration_number')->unique();
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->year('year')->nullable();
            $table->decimal('capacity', 8, 2)->nullable();
            $table->enum('fuel_type', ['diesel', 'petrol', 'electric'])->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_contact')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('image')->nullable();
            $table->json('documents')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trucks');
    }
};
