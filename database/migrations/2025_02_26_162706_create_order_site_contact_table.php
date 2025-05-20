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
        Schema::create('order_site_contact', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->string('site_contact');
            $table->timestamps();

            $table->foreign('order_number')->references('order_number')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_site_contact');
    }
};
