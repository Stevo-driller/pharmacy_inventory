<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;

class PharmacyQueueController extends Controller
{
    /**
     * Show prescriptions awaiting or requiring dispensing.
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'patient_number' => ['nullable', 'string', 'max:100'],
            'patient_name' => ['nullable', 'string', 'max:100'],
            'prescription_number' => ['nullable', 'string', 'max:100'],
        ]);

        $today = now()->toDateString();

        $prescriptions = Prescription::query()
            ->with([
                'customer',
                'items.medicine.batches' => function ($query) use ($today) {
                    $query
                        ->where('status', true)
                        ->where('quantity', '>', 0)
                        ->whereDate('expiry_date', '>=', $today)
                        ->orderBy('expiry_date')
                        ->orderBy('id');
                },
            ])
            ->whereIn('status', [
                'pending',
                'verified',
                'partially_dispensed',
            ])
            ->whereHas('items', function ($query) {
                $query->whereColumn(
                    'quantity_dispensed',
                    '<',
                    'quantity_prescribed'
                );
            })
            ->when(
                !empty($filters['patient_number']),
                function ($query) use ($filters) {
                    $query->whereHas('customer', function ($customerQuery) use ($filters) {
                        $customerQuery->where(
                            'patient_number',
                            'like',
                            '%' . $filters['patient_number'] . '%'
                        );
                    });
                }
            )
            ->when(
                !empty($filters['patient_name']),
                function ($query) use ($filters) {
                    $query->whereHas('customer', function ($customerQuery) use ($filters) {
                        $customerQuery->where(
                            'name',
                            'like',
                            '%' . $filters['patient_name'] . '%'
                        );
                    });
                }
            )
            ->when(
                !empty($filters['prescription_number']),
                function ($query) use ($filters) {
                    $query->where(
                        'prescription_number',
                        'like',
                        '%' . $filters['prescription_number'] . '%'
                    );
                }
            )
            ->orderByDesc('prescription_date')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('pharmacy.queue.index', [
            'prescriptions' => $prescriptions,
            'filters' => $filters,
            'today' => $today,
        ]);
    }

    /**
     * Show the details of a prescription that still has
     * medicine quantities outstanding.
     */
    public function show(int $prescription)
    {
        $today = now()->toDateString();

        $prescription = Prescription::query()
            ->with([
                'customer',
                'items.medicine.batches' => function ($query) use ($today) {
                    $query
                        ->where('status', true)
                        ->where('quantity', '>', 0)
                        ->whereDate('expiry_date', '>=', $today)
                        ->orderBy('expiry_date')
                        ->orderBy('id');
                },
            ])
            ->whereIn('status', [
                'pending',
                'verified',
                'partially_dispensed',
            ])
            ->whereHas('items', function ($query) {
                $query->whereColumn(
                    'quantity_dispensed',
                    '<',
                    'quantity_prescribed'
                );
            })
            ->findOrFail($prescription);

        return view('pharmacy.queue.show', [
            'prescription' => $prescription,
            'today' => $today,
        ]);
    }
}