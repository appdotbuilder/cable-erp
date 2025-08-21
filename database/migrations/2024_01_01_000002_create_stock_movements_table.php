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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cable_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->enum('type', ['in', 'out', 'adjustment'])->comment('Movement type');
            $table->integer('quantity')->comment('Quantity moved (positive/negative)');
            $table->integer('previous_stock')->comment('Stock before movement');
            $table->integer('current_stock')->comment('Stock after movement');
            $table->string('reference_number')->nullable()->comment('Reference document number');
            $table->text('notes')->nullable()->comment('Movement notes/reason');
            $table->timestamp('movement_date')->comment('Date of movement');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('cable_id');
            $table->index('user_id');
            $table->index('type');
            $table->index('movement_date');
            $table->index(['cable_id', 'movement_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};