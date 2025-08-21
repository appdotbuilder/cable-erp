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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Customer name');
            $table->string('email')->unique()->nullable()->comment('Customer email');
            $table->string('phone')->nullable()->comment('Customer phone number');
            $table->string('company')->nullable()->comment('Company name');
            $table->text('address')->nullable()->comment('Customer address');
            $table->string('tax_id')->nullable()->comment('Tax identification number');
            $table->enum('payment_terms', ['cash', 'net_15', 'net_30', 'net_45', 'net_60'])->default('net_30')->comment('Payment terms');
            $table->decimal('credit_limit', 15, 2)->default(0)->comment('Credit limit');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Customer status');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('name');
            $table->index('email');
            $table->index('company');
            $table->index('status');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};