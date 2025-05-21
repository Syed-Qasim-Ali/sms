<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Add invoice_id column with nullable constraint
            $table->integer('adjusted_minutes')->default(0)->after('status');

            $table->string('adjusted_minutes_reason')->nullable()->after('status');

            $table->unsignedBigInteger('invoice_id')->nullable()->after('uuid');

            // Set up foreign key relationship with the invoice table
            $table->foreign('invoice_id')
                ->references('id')->on('invoices')
                ->onDelete('set null'); // Nullable on delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['invoice_id']);
            $table->dropColumn(['invoice_id', 'adjusted_minutes', 'adjusted_minutes_reason']);
        });
    }
};
