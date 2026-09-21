<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display all suppliers.
     */
    public function index()
    {
        $suppliers = Supplier::latest()->get();

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store supplier.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'license_number' => 'nullable|string|max:255',
        ]);

        $validated['status'] = true;

        Supplier::create($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier added successfully.');
    }

    /**
     * Display supplier.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('batches.medicine');

        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show edit form.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update supplier.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'license_number' => 'nullable|string|max:255',
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Delete supplier.
     */
    public function destroy(Supplier $supplier)
    {
        if ($supplier->batches()->exists()) {

            return redirect()
                ->route('suppliers.index')
                ->with(
                    'error',
                    'This supplier cannot be deleted because it has stock batches.'
                );
        }

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}