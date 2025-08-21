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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('cable_id')->constrained();
            $table->string('description')->comment('Item description');
            $table->decimal('quantity', 10, 2)->comment('Quantity sold');
            $table->decimal('unit_price', 10, 2)->comment('Unit price at time of sale');
            $table->decimal('line_total', 15, 2)->comment('Line total (quantity * unit_price)');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('invoice_id');
            $table->index('cable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};