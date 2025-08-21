<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Cable;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movements = StockMovement::with(['cable', 'user'])
            ->when(request('cable_id'), function ($query, $cableId) {
                $query->where('cable_id', $cableId);
            })
            ->when(request('type'), function ($query, $type) {
                $query->where('type', $type);
            })
            ->when(request('date_from'), function ($query, $dateFrom) {
                $query->where('movement_date', '>=', $dateFrom);
            })
            ->when(request('date_to'), function ($query, $dateTo) {
                $query->where('movement_date', '<=', $dateTo);
            })
            ->orderBy('movement_date', 'desc')
            ->paginate(20)
            ->withQueryString();

        $cables = Cable::active()->orderBy('name')->get(['id', 'name', 'barcode']);

        return Inertia::render('inventory/stock-movements/index', [
            'movements' => $movements,
            'cables' => $cables,
            'filters' => request()->only(['cable_id', 'type', 'date_from', 'date_to']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cables = Cable::active()->orderBy('name')->get(['id', 'name', 'barcode', 'stock_quantity']);

        return Inertia::render('inventory/stock-movements/create', [
            'cables' => $cables,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockMovementRequest $request)
    {
        $validated = $request->validated();
        $cable = Cable::findOrFail($validated['cable_id']);

        // Set previous stock
        $validated['previous_stock'] = $cable->stock_quantity;
        
        // Calculate new stock based on movement type
        if ($validated['type'] === 'out') {
            $validated['quantity'] = -abs($validated['quantity']);
        } else {
            $validated['quantity'] = abs($validated['quantity']);
        }
        
        $validated['current_stock'] = max(0, $cable->stock_quantity + $validated['quantity']);
        $validated['user_id'] = auth()->id();
        $validated['movement_date'] = now();

        // Create stock movement
        $movement = StockMovement::create($validated);

        // Update cable stock
        $cable->update(['stock_quantity' => $validated['current_stock']]);

        return redirect()->route('stock-movements.index')
            ->with('success', 'Stock movement recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load(['cable', 'user']);

        return Inertia::render('inventory/stock-movements/show', [
            'movement' => $stockMovement,
        ]);
    }
}