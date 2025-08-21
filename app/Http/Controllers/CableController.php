<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCableRequest;
use App\Http\Requests\UpdateCableRequest;
use App\Models\Cable;
use Inertia\Inertia;

class CableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cables = Cable::with(['stockMovements' => function ($query) {
            $query->latest()->limit(5);
        }])
        ->when(request('search'), function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
        })
        ->when(request('type'), function ($query, $type) {
            $query->where('type', $type);
        })
        ->when(request('status'), function ($query, $status) {
            $query->where('status', $status);
        })
        ->when(request('low_stock'), function ($query) {
            $query->lowStock();
        })
        ->orderBy('name')
        ->paginate(20)
        ->withQueryString();

        $stats = [
            'total_cables' => Cable::count(),
            'active_cables' => Cable::active()->count(),
            'low_stock_count' => Cable::lowStock()->count(),
            'total_value' => Cable::sum(\DB::raw('stock_quantity * unit_price')),
        ];

        return Inertia::render('inventory/cables/index', [
            'cables' => $cables,
            'stats' => $stats,
            'filters' => request()->only(['search', 'type', 'status', 'low_stock']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('inventory/cables/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCableRequest $request)
    {
        $cable = Cable::create($request->validated());

        return redirect()->route('cables.show', $cable)
            ->with('success', 'Cable created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cable $cable)
    {
        $cable->load(['stockMovements.user', 'invoiceItems.invoice.customer']);

        return Inertia::render('inventory/cables/show', [
            'cable' => $cable,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cable $cable)
    {
        return Inertia::render('inventory/cables/edit', [
            'cable' => $cable,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCableRequest $request, Cable $cable)
    {
        $cable->update($request->validated());

        return redirect()->route('cables.show', $cable)
            ->with('success', 'Cable updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cable $cable)
    {
        $cable->delete();

        return redirect()->route('cables.index')
            ->with('success', 'Cable deleted successfully.');
    }
}