<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Supplier;
use Illuminate\Http\Request;

class MedicineBatchController extends Controller
{
    /**
     * Display stock batches.
     */
    public function index()
    {
        $batches = MedicineBatch::with([
            'medicine',
            'supplier',
        ])
            ->latest()
            ->get();

        return view('stock.index', compact('batches'));
    }

    /**
     * Show receive stock form.
     */
    public function create()
    {
        $medicines = Medicine::where('status', true)
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::where('status', true)
            ->orderBy('name')
            ->get();

        return view('stock.create', compact(
            'medicines',
            'suppliers'
        ));
    }

    /**
     * Receive new stock.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'batch_number' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'manufactured_date' => 'nullable|date',
            'expiry_date' => 'required|date|after:today',
            'received_date' => 'nullable|date',
        ]);

        $validated['received_date'] =
            $validated['received_date'] ?? now()->toDateString();

        $validated['status'] = true;

        MedicineBatch::create($validated);

        return redirect()
            ->route('stock.index')
            ->with('success', 'Stock received successfully.');
    }

    /**
     * Display a stock batch.
     */
    public function show(MedicineBatch $stock)
    {
        $stock->load([
            'medicine',
            'supplier',
        ]);

        return view('stock.show', compact('stock'));
    }

    /**
     * Show edit stock form.
     */
    public function edit(MedicineBatch $stock)
    {
        $medicines = Medicine::where('status', true)
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::where('status', true)
            ->orderBy('name')
            ->get();

        return view('stock.edit', compact(
            'stock',
            'medicines',
            'suppliers'
        ));
    }

    /**
     * Update stock batch.
     *
     * Expiry dates must be in the future when editing a batch.
     * The status field is deliberately not accepted from the form.
     */
    public function update(
        Request $request,
        MedicineBatch $stock
    ) {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'batch_number' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'manufactured_date' => 'nullable|date',
            'expiry_date' => 'required|date|after:today',
            'received_date' => 'nullable|date',
        ]);

        $stock->update($validated);

        return redirect()
            ->route('stock.index')
            ->with('success', 'Stock batch updated successfully.');
    }

    /**
     * Deactivate a stock batch without deleting its history.
     */
    public function deactivate(MedicineBatch $stock)
    {
        if (! $stock->status) {
            return redirect()
                ->route('stock.index')
                ->with('info', 'This stock batch is already inactive.');
        }

        $stock->update([
            'status' => false,
        ]);

        return redirect()
            ->route('stock.index')
            ->with(
                'success',
                'Stock batch deactivated successfully. Its sales history has been preserved.'
            );
    }

    /**
     * Reactivate a stock batch only if it has not expired.
     */
    public function activate(MedicineBatch $stock)
    {
        if ($stock->expiry_date->isPast() &&
            ! $stock->expiry_date->isToday()) {
            return redirect()
                ->route('stock.index')
                ->with(
                    'error',
                    'This batch has expired and cannot be reactivated.'
                );
        }

        if ($stock->status) {
            return redirect()
                ->route('stock.index')
                ->with('info', 'This stock batch is already active.');
        }

        $stock->update([
            'status' => true,
        ]);

        return redirect()
            ->route('stock.index')
            ->with('success', 'Stock batch activated successfully.');
    }

    /**
     * Delete a batch only when it has never been used in a sale.
     */
    public function destroy(MedicineBatch $stock)
    {
        if ($stock->saleItems()->exists()) {
            return redirect()
                ->route('stock.index')
                ->with(
                    'error',
                    'This batch cannot be deleted because it has been used in sales. Please deactivate it instead.'
                );
        }

        $stock->delete();

        return redirect()
            ->route('stock.index')
            ->with('success', 'Stock batch deleted successfully.');
    }
}