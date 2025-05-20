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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascade('Delete');
            $table->string('order_number')->unique()->nullable();
            $table->integer('company_id')->nullable();
            $table->string('job')->nullable();
            $table->date('date')->nullable();
            $table->decimal('pay_rate', 10, 2)->nullable();
            $table->string('pay_rate_type')->nullable();
            $table->string('start_location')->nullable();
            $table->string('start_location_lat')->nullable();
            $table->string('start_location_lng')->nullable();
            $table->string('end_location')->nullable();
            $table->string('end_location_lat')->nullable();
            $table->string('end_location_lng')->nullable();
            $table->string('material')->nullable();
            $table->integer('quantity')->nullable();
            $table->text('instruction')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
