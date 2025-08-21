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
        Schema::create('cables', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique()->comment('Unique barcode identifier');
            $table->string('name')->comment('Cable name/model');
            $table->string('size')->comment('Cable size/gauge');
            $table->string('type')->comment('Cable type (e.g., power, data, coaxial)');
            $table->text('description')->nullable()->comment('Detailed description');
            $table->string('manufacturer')->nullable()->comment('Manufacturer name');
            $table->decimal('unit_price', 10, 2)->comment('Price per unit');
            $table->integer('stock_quantity')->default(0)->comment('Current stock quantity');
            $table->integer('minimum_stock')->default(10)->comment('Minimum stock level for alerts');
            $table->string('unit_of_measure')->default('meters')->comment('Unit of measurement');
            $table->string('location')->nullable()->comment('Storage location');
            $table->enum('status', ['active', 'discontinued'])->default('active')->comment('Cable status');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('barcode');
            $table->index('name');
            $table->index('type');
            $table->index(['status', 'stock_quantity']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cables');
    }
};