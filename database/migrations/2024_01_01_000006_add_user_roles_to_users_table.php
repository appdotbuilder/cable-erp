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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['administrator', 'inventory_manager', 'accountant', 'sales_staff'])
                  ->default('sales_staff')
                  ->after('email_verified_at')
                  ->comment('User role for access control');
            $table->enum('status', ['active', 'inactive'])
                  ->default('active')
                  ->after('role')
                  ->comment('User account status');
            
            // Add indexes for performance
            $table->index('role');
            $table->index('status');
            $table->index(['role', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['status']);
            $table->dropIndex(['role', 'status']);
            $table->dropColumn(['role', 'status']);
        });
    }
};