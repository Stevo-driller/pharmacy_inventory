<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display all medicines.
     */
    public function index()
    {
        $medicines = Medicine::with('category')
            ->latest()
            ->get();

        return view('medicines.index', compact('medicines'));
    }

    /**
     * Show the form for creating a medicine.
     */
    public function create()
    {
        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        return view('medicines.create', compact('categories'));
    }

    /**
     * Store a newly created medicine.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'dosage_form' => 'nullable|string|max:100',
            'strength' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:255|unique:medicines,barcode',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
            'prescription_required' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $validated['prescription_required'] =
            $request->boolean('prescription_required');

        $validated['status'] = true;

        Medicine::create($validated);

        return redirect()
            ->route('medicines.index')
            ->with('success', 'Medicine added successfully.');
    }

    /**
     * Display a specific medicine.
     */
    public function show(Medicine $medicine)
    {
        $medicine->load([
            'category',
            'batches.supplier',
        ]);

        return view('medicines.show', compact('medicine'));
    }

    /**
     * Show the form for editing a medicine.
     */
    public function edit(Medicine $medicine)
    {
        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        return view('medicines.edit', compact(
            'medicine',
            'categories'
        ));
    }

    /**
     * Update a medicine.
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'dosage_form' => 'nullable|string|max:100',
            'strength' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:255|unique:medicines,barcode,' . $medicine->id,
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
            'prescription_required' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $validated['prescription_required'] =
            $request->boolean('prescription_required');

        $medicine->update($validated);

        return redirect()
            ->route('medicines.index')
            ->with('success', 'Medicine updated successfully.');
    }

    /**
     * Delete a medicine.
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()
            ->route('medicines.index')
            ->with('success', 'Medicine deleted successfully.');
    }
}