<?php

namespace Database\Seeders;

use App\Models\Cable;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users with different roles
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@cableerp.com',
            'role' => 'administrator',
            'status' => 'active',
        ]);

        $inventoryManager = User::factory()->create([
            'name' => 'Inventory Manager',
            'email' => 'inventory@cableerp.com',
            'role' => 'inventory_manager',
            'status' => 'active',
        ]);

        $accountant = User::factory()->create([
            'name' => 'Accountant',
            'email' => 'accountant@cableerp.com',
            'role' => 'accountant',
            'status' => 'active',
        ]);

        $salesStaff = User::factory()->create([
            'name' => 'Sales Staff',
            'email' => 'sales@cableerp.com',
            'role' => 'sales_staff',
            'status' => 'active',
        ]);

        // Create demo user
        User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'role' => 'administrator',
            'status' => 'active',
        ]);

        // Create cables
        $cables = Cable::factory(50)->create();
        
        // Create some low stock cables
        Cable::factory(10)->lowStock()->create();

        // Create customers
        $customers = Customer::factory(30)->active()->create();

        // Create stock movements
        foreach ($cables->take(30) as $cable) {
            StockMovement::factory(3)->create([
                'cable_id' => $cable->id,
                'user_id' => $inventoryManager->id,
            ]);
        }

        // Create invoices with items
        foreach ($customers->take(20) as $customer) {
            $invoice = Invoice::factory()->create([
                'customer_id' => $customer->id,
                'user_id' => $salesStaff->id,
            ]);

            // Create invoice items
            $selectedCables = $cables->random(random_int(1, 5));
            foreach ($selectedCables as $cable) {
                InvoiceItem::factory()->create([
                    'invoice_id' => $invoice->id,
                    'cable_id' => $cable->id,
                ]);
            }
        }

        // Create some overdue invoices
        Invoice::factory(5)->overdue()->create([
            'customer_id' => $customers->random()->id,
            'user_id' => $salesStaff->id,
        ]);
    }
}
