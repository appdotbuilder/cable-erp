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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique()->comment('Unique invoice number');
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->date('invoice_date')->comment('Invoice date');
            $table->date('due_date')->comment('Payment due date');
            $table->decimal('subtotal', 15, 2)->comment('Subtotal before tax');
            $table->decimal('tax_amount', 15, 2)->default(0)->comment('Tax amount');
            $table->decimal('total_amount', 15, 2)->comment('Total invoice amount');
            $table->decimal('paid_amount', 15, 2)->default(0)->comment('Amount paid');
            $table->decimal('outstanding_amount', 15, 2)->comment('Outstanding amount');
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft')->comment('Invoice status');
            $table->text('notes')->nullable()->comment('Invoice notes');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('invoice_number');
            $table->index('customer_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('due_date');
            $table->index(['status', 'due_date']);
            $table->index('invoice_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};