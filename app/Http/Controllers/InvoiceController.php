<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Cable;
use App\Models\Customer;
use App\Models\Invoice;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['customer', 'user'])
            ->when(request('search'), function ($query, $search) {
                $query->where('invoice_number', 'like', "%{$search}%")
                      ->orWhereHas('customer', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('company', 'like', "%{$search}%");
                      });
            })
            ->when(request('status'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->when(request('customer_id'), function ($query, $customerId) {
                $query->where('customer_id', $customerId);
            })
            ->when(request('overdue'), function ($query) {
                $query->overdue();
            })
            ->orderBy('invoice_date', 'desc')
            ->paginate(20)
            ->withQueryString();

        $customers = Customer::active()->orderBy('name')->get(['id', 'name', 'company']);

        $stats = [
            'total_invoices' => Invoice::count(),
            'total_amount' => Invoice::sum('total_amount'),
            'outstanding_amount' => Invoice::sum('outstanding_amount'),
            'overdue_count' => Invoice::overdue()->count(),
        ];

        return Inertia::render('invoices/index', [
            'invoices' => $invoices,
            'customers' => $customers,
            'stats' => $stats,
            'filters' => request()->only(['search', 'status', 'customer_id', 'overdue']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::active()->orderBy('name')->get(['id', 'name', 'company', 'payment_terms']);
        $cables = Cable::active()->orderBy('name')->get(['id', 'name', 'barcode', 'unit_price']);

        return Inertia::render('invoices/create', [
            'customers' => $customers,
            'cables' => $cables,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $validated = $request->validated();
        
        // Generate invoice number
        $validated['invoice_number'] = 'INV-' . now()->format('Y') . '-' . str_pad((string)(Invoice::whereYear('created_at', now()->year)->count() + 1), 4, '0', STR_PAD_LEFT);
        $validated['user_id'] = auth()->id();
        $validated['invoice_date'] = now()->toDateString();
        
        // Calculate due date based on customer payment terms
        $customer = Customer::findOrFail($validated['customer_id']);
        $daysToAdd = match ($customer->payment_terms) {
            'cash' => 0,
            'net_15' => 15,
            'net_30' => 30,
            'net_45' => 45,
            'net_60' => 60,
            default => 30,
        };
        $validated['due_date'] = now()->addDays($daysToAdd)->toDateString();

        // Calculate totals
        $subtotal = collect($validated['items'])->sum('line_total');
        $taxAmount = $subtotal * 0.1; // 10% tax
        $totalAmount = $subtotal + $taxAmount;

        $validated['subtotal'] = $subtotal;
        $validated['tax_amount'] = $taxAmount;
        $validated['total_amount'] = $totalAmount;
        $validated['paid_amount'] = 0;
        $validated['outstanding_amount'] = $totalAmount;

        $invoice = Invoice::create($validated);

        // Create invoice items
        foreach ($validated['items'] as $item) {
            $invoice->items()->create($item);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'user', 'items.cable']);

        return Inertia::render('invoices/show', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load(['items.cable']);
        $customers = Customer::active()->orderBy('name')->get(['id', 'name', 'company', 'payment_terms']);
        $cables = Cable::active()->orderBy('name')->get(['id', 'name', 'barcode', 'unit_price']);

        return Inertia::render('invoices/edit', [
            'invoice' => $invoice,
            'customers' => $customers,
            'cables' => $cables,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $validated = $request->validated();

        // Recalculate totals if items changed
        if (isset($validated['items'])) {
            $subtotal = collect($validated['items'])->sum('line_total');
            $taxAmount = $subtotal * 0.1; // 10% tax
            $totalAmount = $subtotal + $taxAmount;
            $outstandingAmount = $totalAmount - $invoice->paid_amount;

            $validated['subtotal'] = $subtotal;
            $validated['tax_amount'] = $taxAmount;
            $validated['total_amount'] = $totalAmount;
            $validated['outstanding_amount'] = $outstandingAmount;

            // Update items
            $invoice->items()->delete();
            foreach ($validated['items'] as $item) {
                $invoice->items()->create($item);
            }
        }

        $invoice->update($validated);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }
}