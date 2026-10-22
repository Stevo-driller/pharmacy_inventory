<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DoctorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Doctor Dashboard
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $doctorName = auth()->user()->name;

        /*
        |--------------------------------------------------------------------------
        | Today's Patients
        |--------------------------------------------------------------------------
        */

        $todayPatients = Prescription::query()
            ->where('prescriber_name', $doctorName)
            ->whereDate('prescription_date', today())
            ->distinct('customer_id')
            ->count('customer_id');

        /*
        |--------------------------------------------------------------------------
        | Today's Prescriptions
        |--------------------------------------------------------------------------
        */

        $todayPrescriptions = Prescription::query()
            ->where('prescriber_name', $doctorName)
            ->whereDate('prescription_date', today())
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Pending Prescriptions
        |--------------------------------------------------------------------------
        */

        $pendingCount = Prescription::query()
            ->where('prescriber_name', $doctorName)
            ->where('status', 'pending')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Partially Dispensed Prescriptions
        |--------------------------------------------------------------------------
        */

        $partiallyDispensedCount = Prescription::query()
            ->where('prescriber_name', $doctorName)
            ->where('status', 'partially_dispensed')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Total Patients Seen By This Doctor
        |--------------------------------------------------------------------------
        */

        $totalPatients = Prescription::query()
            ->where('prescriber_name', $doctorName)
            ->whereNotNull('customer_id')
            ->distinct('customer_id')
            ->count('customer_id');

        /*
        |--------------------------------------------------------------------------
        | Recent Prescriptions
        |--------------------------------------------------------------------------
        */

        $recentPrescriptions = Prescription::with([
            'customer',
            'items.medicine',
        ])
            ->where('prescriber_name', $doctorName)
            ->latest()
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Patients
        |--------------------------------------------------------------------------
        */

        $recentPatients = Customer::query()
            ->whereHas('prescriptions', function ($query) use ($doctorName) {
                $query->where('prescriber_name', $doctorName);
            })
            ->with([
                'prescriptions' => function ($query) use ($doctorName) {
                    $query
                        ->where('prescriber_name', $doctorName)
                        ->latest();
                },
            ])
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        return view(
            'doctor.dashboard',
            compact(
                'recentPrescriptions',
                'recentPatients',
                'pendingCount',
                'partiallyDispensedCount',
                'todayPatients',
                'todayPrescriptions',
                'totalPatients'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Prescription Page
    |--------------------------------------------------------------------------
    */

    public function createPrescription(Request $request)
    {
        $medicines = Medicine::query()
            ->where('status', true)
            ->orderBy('name')
            ->limit(30)
            ->get([
                'id',
                'name',
                'generic_name',
                'brand_name',
                'dosage_form',
                'strength',
                'prescription_required',
            ]);

        $selectedPatient = null;

        if ($request->filled('patient_id')) {
            $selectedPatient = Customer::find(
                $request->integer('patient_id')
            );
        }

        return view(
            'doctor.prescriptions.create',
            compact('medicines', 'selectedPatient')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Route Compatibility: Create Prescription
    |--------------------------------------------------------------------------
    |
    | The route uses DoctorController@create.
    | Keep createPrescription() for existing callers.
    |
    */

    public function create(Request $request)
    {
        return $this->createPrescription($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Search Patients
    |--------------------------------------------------------------------------
    */

    public function searchPatients(Request $request)
    {
        $search = trim(
            $request->input(
                'q',
                $request->input('search', '')
            )
        );

        if ($search === '') {
            return response()->json([
                'patients' => [],
            ]);
        }

        $patients = Customer::query()
            ->where(function ($query) use ($search) {
                $query
                    ->where('patient_number', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->limit(10)
            ->get([
                'id',
                'patient_number',
                'name',
                'phone',
                'address',
                'notes',
            ]);

        return response()->json([
            'patients' => $patients,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Search Prescriptions
    |--------------------------------------------------------------------------
    */

    public function searchPrescriptions(Request $request)
    {
        $search = trim(
            $request->input(
                'q',
                $request->input('search', '')
            )
        );

        if ($search === '') {
            return response()->json([
                'prescriptions' => [],
            ]);
        }

        $prescriptions = Prescription::query()
            ->with([
                'customer:id,patient_number,name,phone',
            ])
            ->where('prescriber_name', auth()->user()->name)
            ->where(function ($query) use ($search) {
                $query->where(
                    'prescription_number',
                    'like',
                    '%' . $search . '%'
                );

                $query->orWhereHas(
                    'customer',
                    function ($customerQuery) use ($search) {
                        $customerQuery
                            ->where('patient_number', 'like', '%' . $search . '%')
                            ->orWhere('name', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%');
                    }
                );
            })
            ->latest()
            ->limit(10)
            ->get([
                'id',
                'customer_id',
                'prescription_number',
                'prescriber_name',
                'prescription_date',
                'status',
                'verified',
                'created_at',
            ]);

        return response()->json([
            'prescriptions' => $prescriptions,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Register Patient
    |--------------------------------------------------------------------------
    */

    public function registerPatient(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'phone' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
                'address' => [
                    'nullable',
                    'string',
                    'max:500',
                ],
                'notes' => [
                    'nullable',
                    'string',
                    'max:2000',
                ],
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Please correct the patient information.',
                'errors' => $exception->errors(),
            ], 422);
        }

        $patient = Customer::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Patient registered successfully.',
            'patient' => [
                'id' => $patient->id,
                'patient_number' => $patient->patient_number,
                'name' => $patient->name,
                'phone' => $patient->phone,
                'address' => $patient->address,
                'notes' => $patient->notes,
            ],
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | Search Medicines
    |--------------------------------------------------------------------------
    */

    public function searchMedicines(Request $request)
    {
        $search = trim(
            $request->input(
                'q',
                $request->input('search', '')
            )
        );

        $query = Medicine::query()
            ->where('status', true);

        if ($search !== '') {
            $query->where(function ($medicineQuery) use ($search) {
                $medicineQuery
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('generic_name', 'like', '%' . $search . '%')
                    ->orWhere('brand_name', 'like', '%' . $search . '%')
                    ->orWhere('strength', 'like', '%' . $search . '%')
                    ->orWhere('dosage_form', 'like', '%' . $search . '%');
            });
        }

        $medicines = $query
            ->orderBy('name')
            ->limit(30)
            ->get([
                'id',
                'name',
                'generic_name',
                'brand_name',
                'dosage_form',
                'strength',
                'prescription_required',
            ]);

        return response()->json([
            'success' => true,
            'medicines' => $medicines,
            'count' => $medicines->count(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Store Prescription
    |--------------------------------------------------------------------------
    */

    public function storePrescription(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => [
                'required',
                'integer',
                'exists:customers,id',
            ],
            'prescription_date' => [
                'required',
                'date',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'items' => [
                'required',
                'array',
                'min:1',
            ],
            'items.*.medicine_id' => [
                'required',
                'integer',
                'exists:medicines,id',
            ],
            'items.*.quantity' => [
                'nullable',
                'required_without:items.*.quantity_prescribed',
                'integer',
                'min:1',
            ],
            'items.*.quantity_prescribed' => [
                'nullable',
                'required_without:items.*.quantity',
                'integer',
                'min:1',
            ],
            'items.*.dosage' => [
                'required',
                'string',
                'max:255',
            ],
            'items.*.frequency' => [
                'required',
                'string',
                'max:255',
            ],
            'items.*.duration' => [
                'required',
                'string',
                'max:255',
            ],
            'items.*.route' => [
                'nullable',
                'string',
                'max:255',
            ],
            'items.*.instructions' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $prescription = DB::transaction(function () use ($validated) {
            $patient = Customer::findOrFail(
                $validated['customer_id']
            );

            $prescription = Prescription::create([
                'customer_id' => $patient->id,
                'prescriber_name' => auth()->user()->name,
                'prescription_date' => $validated['prescription_date'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
                'verified' => false,
            ]);

            foreach ($validated['items'] as $item) {
                $medicine = Medicine::query()
                    ->where('status', true)
                    ->findOrFail($item['medicine_id']);

                $quantity = $item['quantity_prescribed']
                    ?? $item['quantity']
                    ?? null;

                if (!$quantity || $quantity < 1) {
                    throw ValidationException::withMessages([
                        'items' => [
                            'Every medicine must have a valid quantity.',
                        ],
                    ]);
                }

                $prescription->items()->create([
                    'medicine_id' => $medicine->id,
                    'quantity_prescribed' => $quantity,
                    'quantity_dispensed' => 0,
                    'dosage' => $item['dosage'],
                    'frequency' => $item['frequency'],
                    'duration' => $item['duration'],
                    'route' => $item['route'] ?? null,
                    'instructions' => $item['instructions'] ?? null,
                ]);
            }

            return $prescription->load([
                'customer',
                'items.medicine',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Prescription created successfully and is pending pharmacy verification.',
            'prescription' => [
                'id' => $prescription->id,
                'prescription_number' => $prescription->prescription_number,
                'status' => $prescription->status,
                'status_label' => 'Pending Pharmacy Verification',
                'patient' => [
                    'id' => $prescription->customer->id,
                    'patient_number' => $prescription->customer->patient_number,
                    'name' => $prescription->customer->name,
                ],
                'items' => $prescription->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'medicine_id' => $item->medicine_id,
                        'medicine_name' => $item->medicine->name,
                        'quantity_prescribed' => $item->quantity_prescribed,
                    ];
                })->values(),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Show Prescription
    |--------------------------------------------------------------------------
    */

    public function showPrescription(Prescription $prescription)
    {
        $prescription->load([
            'customer',
            'items.medicine',
        ]);

        /*
        |--------------------------------------------------------------------------
        | A doctor can only view prescriptions created by that doctor.
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $prescription->prescriber_name === auth()->user()->name,
            403
        );

        return view(
            'doctor.prescriptions.show',
            compact('prescription')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Route Compatibility: Show Prescription
    |--------------------------------------------------------------------------
    |
    | The route uses DoctorController@show.
    | Keep showPrescription() for existing callers.
    |
    */

    public function show(Prescription $prescription)
    {
        return $this->showPrescription($prescription);
    }
}
