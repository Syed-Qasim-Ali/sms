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
        Schema::create('ticket_assigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->uuid('ticket_id');  // <-- yahan uuid use karna hai
            $table->unsignedBigInteger('truck_id');
            $table->unsignedBigInteger('trailer_id')->nullable();
            $table->string('status');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ticket_id')->references('uuid')->on('tickets')->onDelete('cascade'); // matches uuid
            $table->foreign('truck_id')->references('id')->on('trucks')->onDelete('cascade');
            $table->foreign('trailer_id')->references('id')->on('trailers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_assigns');
    }
};
