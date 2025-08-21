<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cable;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     */
    public function index()
    {
        // Inventory stats
        $inventoryStats = [
            'total_cables' => Cable::count(),
            'active_cables' => Cable::active()->count(),
            'low_stock_cables' => Cable::lowStock()->count(),
            'total_inventory_value' => Cable::sum(\DB::raw('stock_quantity * unit_price')),
        ];

        // Sales stats
        $salesStats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::active()->count(),
            'total_invoices' => Invoice::count(),
            'total_sales' => Invoice::sum('total_amount'),
            'outstanding_amount' => Invoice::sum('outstanding_amount'),
            'overdue_invoices' => Invoice::overdue()->count(),
        ];

        // Recent activities
        $recentStockMovements = StockMovement::with(['cable', 'user'])
            ->latest('movement_date')
            ->limit(10)
            ->get();

        $recentInvoices = Invoice::with(['customer', 'user'])
            ->latest('invoice_date')
            ->limit(10)
            ->get();

        // Low stock alerts
        $lowStockCables = Cable::lowStock()->active()->limit(10)->get();

        // Overdue invoices
        $overdueInvoices = Invoice::overdue()->with('customer')->limit(10)->get();

        return Inertia::render('dashboard', [
            'inventoryStats' => $inventoryStats,
            'salesStats' => $salesStats,
            'recentStockMovements' => $recentStockMovements,
            'recentInvoices' => $recentInvoices,
            'lowStockCables' => $lowStockCables,
            'overdueInvoices' => $overdueInvoices,
        ]);
    }
}