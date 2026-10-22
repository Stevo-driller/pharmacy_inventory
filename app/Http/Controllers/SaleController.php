<?php

namespace App\Http\Controllers;

use App\Models\Customer;

use App\Models\Medicine;

use App\Models\MedicineBatch;

use App\Models\Prescription;

use App\Models\PrescriptionItem;

use App\Models\Sale;

use App\Models\SaleItem;

use App\Models\StockMovement;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\URL;

class SaleController extends Controller

{

    public function index()

    {

        $medicines = Medicine::with([

            'batches' => function ($query) {

                $query->where('status', true)

                    ->where('quantity', '>', 0)

                    ->whereDate('expiry_date', '>=', now())

                    ->orderBy('expiry_date', 'asc');

            },

        ])

            ->orderBy('name')

            ->get();

        return view('sales.index', compact('medicines'));

    }

    public function verifyPrescription(Request $request)

    {

        $validated = $request->validate([

            'prescription_number' => [

                'required',

                'string',

                'max:100',

            ],

        ]);

        $prescription = DB::transaction(function () use ($validated) {

            $prescription = Prescription::where(

                'prescription_number',

                trim($validated['prescription_number'])

            )

                ->lockForUpdate()

                ->first();

            if (!$prescription) {

                return null;

            }

            $prescription->load([

                'customer',

                'items.medicine',

            ]);

            if (!in_array(

                $prescription->status,

                [

                    'pending',

                    'verified',

                    'partially_dispensed',

                    'dispensed',

                ],

                true

            )) {

                return null;

            }

            if ($prescription->status === 'dispensed') {

                return $prescription;

            }

            if ($prescription->items->isEmpty()) {

                return $prescription;

            }

            if (!in_array(

                $prescription->status,

                ['verified', 'partially_dispensed'],

                true

            )) {

                $prescription->status = 'verified';

            }

            if (!$prescription->verified) {

                $prescription->verified = true;

            }

            if (!$prescription->verified_by) {

                $prescription->verified_by =

                    auth()->user()?->name ?? 'System';

            }

            if (!$prescription->verified_at) {

                $prescription->verified_at = now();

            }

            $prescription->save();

            return $prescription;

        });

        if (!$prescription) {

            return response()->json([

                'success' => false,

                'message' => 'Prescription not found or its status is invalid.',

            ], 404);

        }

        if ($prescription->status === 'dispensed') {

            return response()->json([

                'success' => false,

                'message' => 'This prescription has already been fully dispensed.',

            ], 422);

        }

        if ($prescription->items->isEmpty()) {

            return response()->json([

                'success' => false,

                'message' => 'This prescription has no medicines.',

            ], 422);

        }

        return response()->json([

            'success' => true,

            'message' => 'Prescription verified successfully.',

            'prescription' => [

                'id' => $prescription->id,

                'prescription_number' => $prescription->prescription_number,

                'customer_id' => $prescription->customer_id,

                'customer_name' => $prescription->customer?->name,

                'customer_phone' => $prescription->customer?->phone,

                'prescriber_name' => $prescription->prescriber_name,

                'prescription_date' => $prescription->prescription_date,

                'status' => $prescription->status,

                'verified' => (bool) $prescription->verified,

                'verified_by' => $prescription->verified_by,

                'verified_at' => $prescription->verified_at,

                'items' => $prescription->items->map(function ($item) {

                    return [

                        'id' => $item->id,

                        'medicine_id' => $item->medicine_id,

                        'medicine_name' => $item->medicine?->name,

                        'quantity_prescribed' => (int) $item->quantity_prescribed,

                        'quantity_dispensed' => (int) $item->quantity_dispensed,

                        'quantity_remaining' => max(

                            0,

                            (int) $item->quantity_prescribed

                                - (int) $item->quantity_dispensed

                        ),

                        'dosage' => $item->dosage,

                        'frequency' => $item->frequency,

                        'duration' => $item->duration,

                        'route' => $item->route,

                        'instructions' => $item->instructions,

                    ];

                })->values(),

            ],

        ]);

    }

    public function searchCustomers(Request $request)

    {

        $search = trim(

            $request->input('customer', $request->input('search', ''))

        );

        if ($search === '') {

            return response()->json([

                'customers' => [],

            ]);

        }

        $customers = Customer::query()

            ->where(function ($query) use ($search) {

                $query->where('name', 'like', '%' . $search . '%')

                    ->orWhere('phone', 'like', '%' . $search . '%');

            })

            ->orderBy('name')

            ->limit(10)

            ->get([

                'id',

                'name',

                'phone',

            ]);

        return response()->json([

            'customers' => $customers,

        ]);

    }

    public function history(Request $request)

    {

        $query = Sale::with([

            'customer',

            'user',

            'items.medicine',

        ])

            ->where('status', 'completed')

            ->latest();

        if ($request->filled('customer')) {

            $customerSearch = trim($request->input('customer'));

            $query->where(function ($saleQuery) use ($customerSearch) {

                $saleQuery->where(

                    'customer_name',

                    'like',

                    '%' . $customerSearch . '%'

                )

                    ->orWhere(

                        'customer_phone',

                        'like',

                        '%' . $customerSearch . '%'

                    )

                    ->orWhereHas(

                        'customer',

                        function ($customerQuery) use ($customerSearch) {

                            $customerQuery->where(

                                'name',

                                'like',

                                '%' . $customerSearch . '%'

                            )

                                ->orWhere(

                                    'phone',

                                    'like',

                                    '%' . $customerSearch . '%'

                                );

                        }

                    );

            });

        }

        $sales = $query->paginate(20)->withQueryString();

        $statsQuery = Sale::where('status', 'completed');

        if ($request->filled('customer')) {

            $customerSearch = trim($request->input('customer'));

            $statsQuery->where(function ($saleQuery) use ($customerSearch) {

                $saleQuery->where(

                    'customer_name',

                    'like',

                    '%' . $customerSearch . '%'

                )

                    ->orWhere(

                        'customer_phone',

                        'like',

                        '%' . $customerSearch . '%'

                    )

                    ->orWhereHas(

                        'customer',

                        function ($customerQuery) use ($customerSearch) {

                            $customerQuery->where(

                                'name',

                                'like',

                                '%' . $customerSearch . '%'

                            )

                                ->orWhere(

                                    'phone',

                                    'like',

                                    '%' . $customerSearch . '%'

                                );

                        }

                    );

            });

        }

        $stats = [

            'total_sales' => $statsQuery->count(),

            'total_revenue' => $statsQuery->sum('total_amount'),

        ];

        return view('sales.history', compact('sales', 'stats'));

    }

    public function show(Sale $sale)

    {

        $sale->load([

            'customer',

            'user',

            'items.medicine',

            'items.medicineBatch',

            'prescription.items.medicine',

        ]);

        return view('sales.show', compact('sale'));

    }

    public function receipt(Sale $sale)

    {

        $sale->load([

            'customer',

            'user',

            'items.medicine',

            'items.medicineBatch',

            'prescription.items.medicine',

        ]);

        $mobileReceiptUrl = URL::temporarySignedRoute(

            'sales.mobile-receipt',

            now()->addMinutes(30),

            ['sale' => $sale]

        );

        return view('sales.receipt', compact(

            'sale',

            'mobileReceiptUrl'

        ));

    }

    public function mobileReceipt(Sale $sale)

    {

        $sale->load([

            'customer',

            'user',

            'items.medicine',

            'items.medicineBatch',

            'prescription.items.medicine',

        ]);

        return view('sales.mobile-receipt', compact('sale'));

    }

    public function store(Request $request)

    {
        // The dispensing page sends these payloads as JSON hidden fields.
        // Convert them to arrays before Laravel validates them.
        foreach (['items', 'unavailable_items'] as $payloadKey) {
            $payload = $request->input($payloadKey);

            // Some forms submit these hidden fields as JSON strings; others submit arrays.
            // Decode JSON strings before Laravel's array validation runs.
            if (is_string($payload)) {
                $decodedPayload = json_decode($payload, true);

                // Handle a JSON string that has accidentally been encoded twice.
                if (is_string($decodedPayload)) {
                    $decodedPayload = json_decode($decodedPayload, true);
                }

                if (json_last_error() !== JSON_ERROR_NONE || !is_array($decodedPayload)) {
                    return response()->json([
                        'success' => false,
                        'message' => "The {$payloadKey} payload is invalid. Please refresh the page and try again.",
                    ], 422);
                }

                $request->merge([$payloadKey => $decodedPayload]);
            } elseif ($payload === null || $payload === '') {
                $request->merge([$payloadKey => []]);
            } elseif (!is_array($payload)) {
                return response()->json([
                    'success' => false,
                    'message' => "The {$payloadKey} payload must be a valid list.",
                ], 422);
            }
        }

        // The pharmacy queue form does not send a POS subtotal. The controller
        // calculates the real subtotal from the selected FEFO batches below.
        if (!$request->has('subtotal') || $request->input('subtotal') === '') {
            $request->merge(['subtotal' => 0]);
        }

        $validated = $request->validate([

            'customer_id' => [

                'nullable',

                'integer',

                'exists:customers,id',

            ],

            'customer_name' => [

                'nullable',

                'string',

                'max:255',

            ],

            'customer_phone' => [

                'nullable',

                'string',

                'max:50',

            ],

            'sale_type' => [

                'nullable',

                'string',

                'in:walk_in,hospital_prescription,external_prescription',

            ],

            'prescription_id' => [

                'nullable',

                'integer',

                'exists:prescriptions,id',

            ],

            'prescription_file' => [

                'nullable',

                'required_if:sale_type,external_prescription',

                'file',

                'mimes:jpg,jpeg,png,webp,pdf',

                'max:10240',

            ],

            'subtotal' => [

                'required',

                'numeric',

                'min:0',

            ],

            'discount' => [

                'nullable',

                'numeric',

                'min:0',

            ],

            'payment_method' => [

                'required',

                'string',

                'in:cash,mpesa,card,split',

            ],

            'amount_paid' => [

                'required',

                'numeric',

                'min:0',

            ],

            'cash_amount' => [

                'nullable',

                'numeric',

                'min:0',

            ],

            'mpesa_amount' => [

                'nullable',

                'numeric',

                'min:0',

            ],

            'card_amount' => [

                'nullable',

                'numeric',

                'min:0',

            ],

            'prescriber_name' => [

                'nullable',

                'string',

                'max:255',

            ],

            'prescription_date' => [

                'nullable',

                'date',

            ],

            'prescription_number' => [

                'nullable',

                'string',

                'max:100',

            ],

            'items' => [

                'nullable',

                'array',

            ],

            'items.*.medicine_id' => [

                'required',

                'integer',

                'exists:medicines,id',

            ],

            'items.*.quantity' => [

                'required',

                'integer',

                'min:1',

            ],

            'items.*.prescription_item_id' => [

                'nullable',

                'integer',

                'exists:prescription_items,id',

            ],

            'items.*.dosage' => [

                'nullable',

                'string',

                'max:255',

            ],

            'items.*.frequency' => [

                'nullable',

                'string',

                'max:255',

            ],

            'items.*.duration' => [

                'nullable',

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
            'unavailable_items' => [
                'nullable',
                'array',
            ],
            'unavailable_items.*.prescription_item_id' => [
                'required',
                'integer',
                'exists:prescription_items,id',
            ],
            'unavailable_items.*.reason' => [
                'required',
                'string',
                'max:1000',
            ],

        ]);

        $validated['items'] = $validated['items'] ?? [];
        $validated['unavailable_items'] = $validated['unavailable_items'] ?? [];

        if (empty($validated['items']) && empty($validated['unavailable_items'])) {
            return response()->json([
                'success' => false,
                'message' => 'Add at least one medicine to dispense or record as unavailable.',
            ], 422);
        }

        $storedPrescriptionFile = null;

        try {

            $sale = DB::transaction(function () use (

                $request,

                $validated,

                &$storedPrescriptionFile

            ) {

                $prescription = null;

                $saleType = $validated['sale_type'] ?? 'walk_in';

                if (

                    $saleType === 'hospital_prescription' &&

                    empty($validated['prescription_id'])

                ) {

                    abort(response()->json([

                        'success' => false,

                        'message' => 'Verify and select a hospital prescription before completing this sale.',

                    ], 422));

                }

                if (

                    $saleType !== 'hospital_prescription' &&

                    !empty($validated['prescription_id'])

                ) {

                    abort(response()->json([

                        'success' => false,

                        'message' => 'A hospital prescription can only be attached to a hospital prescription sale.',

                    ], 422));

                }

                if (!empty($validated['prescription_id'])) {

                    $prescription = Prescription::where(

                        'id',

                        $validated['prescription_id']

                    )

                        ->lockForUpdate()

                        ->firstOrFail();

                    $prescription->load([

                        'customer',

                        'items',

                    ]);

                    if (!in_array(

                        $prescription->status,

                        ['verified', 'partially_dispensed'],

                        true

                    )) {

                        abort(response()->json([

                            'success' => false,

                            'message' => $prescription->status === 'dispensed'

                                ? 'This prescription has already been fully dispensed.'

                                : 'The hospital prescription must be verified before completing the sale.',

                        ], 422));

                    }

                    if (!$prescription->verified) {

                        abort(response()->json([

                            'success' => false,

                            'message' => 'Verify the hospital prescription before dispensing.',

                        ], 422));

                    }

                    if ($prescription->items->isEmpty()) {

                        abort(response()->json([

                            'success' => false,

                            'message' => 'This prescription has no medicines.',

                        ], 422));

                    }

                }

                $customerId = $validated['customer_id'] ?? null;

                if ($prescription) {

                    $customerId = $prescription->customer_id;

                }

                if (

                    !$customerId &&

                    !empty($validated['customer_phone'])

                ) {

                    $existingCustomer = Customer::where(

                        'phone',

                        $validated['customer_phone']

                    )->first();

                    if ($existingCustomer) {

                        $customerId = $existingCustomer->id;

                    } else {

                        $customer = Customer::create([

                            'name' => $validated['customer_name'] ?? 'Customer',

                            'phone' => $validated['customer_phone'],

                        ]);

                        $customerId = $customer->id;

                    }

                }

                if ($prescription && !$customerId) {

                    abort(response()->json([

                        'success' => false,

                        'message' => 'The hospital prescription is not linked to a customer.',

                    ], 422));

                }

                // Unavailable records are only valid for a verified hospital prescription.
                $unavailableItemIds = [];

                foreach ($validated['unavailable_items'] as $unavailableItem) {
                    if (!$prescription || $saleType !== 'hospital_prescription') {
                        abort(response()->json([
                            'success' => false,
                            'message' => 'Unavailable medicines can only be recorded against a hospital prescription.',
                        ], 422));
                    }

                    $unavailablePrescriptionItem = $prescription->items->firstWhere(
                        'id',
                        (int) $unavailableItem['prescription_item_id']
                    );

                    if (!$unavailablePrescriptionItem) {
                        abort(response()->json([
                            'success' => false,
                            'message' => 'An unavailable item does not belong to the selected hospital prescription.',
                        ], 422));
                    }

                    $unavailablePrescriptionItem = PrescriptionItem::where(
                        'id',
                        $unavailablePrescriptionItem->id
                    )->lockForUpdate()->firstOrFail();

                    $remainingPrescribed = (int) $unavailablePrescriptionItem->quantity_prescribed
                        - (int) $unavailablePrescriptionItem->quantity_dispensed;

                    if ($remainingPrescribed <= 0) {
                        abort(response()->json([
                            'success' => false,
                            'message' => 'A fully dispensed medicine cannot be marked unavailable.',
                        ], 422));
                    }

                    if (in_array($unavailablePrescriptionItem->id, $unavailableItemIds, true)) {
                        abort(response()->json([
                            'success' => false,
                            'message' => 'A prescription item cannot be marked unavailable more than once in the same submission.',
                        ], 422));
                    }

                    $unavailableItemIds[] = $unavailablePrescriptionItem->id;
                }

                foreach ($validated['items'] as $dispensingItem) {
                    if (
                        !empty($dispensingItem['prescription_item_id']) &&
                        in_array((int) $dispensingItem['prescription_item_id'], $unavailableItemIds, true)
                    ) {
                        abort(response()->json([
                            'success' => false,
                            'message' => 'The same prescription item cannot be dispensed and marked unavailable in one submission.',
                        ], 422));
                    }
                }

                foreach ($validated['unavailable_items'] as $unavailableItem) {
                    $unavailablePrescriptionItem = $prescription->items->firstWhere(
                        'id',
                        (int) $unavailableItem['prescription_item_id']
                    );

                    DB::table('prescription_unavailability_records')->insert([
                        'prescription_id' => $prescription->id,
                        'prescription_item_id' => $unavailablePrescriptionItem->id,
                        'medicine_id' => $unavailablePrescriptionItem->medicine_id,
                        'reason' => trim($unavailableItem['reason']),
                        'recorded_by' => auth()->id(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // If nothing can be dispensed, save the unavailability records without creating a zero-value sale.
                if (empty($validated['items'])) {
                    return [
                        'unavailable_only' => true,
                        'prescription_id' => $prescription->id,
                        'message' => 'Unavailable medicines and reasons have been recorded. No sale was created because no medicine was dispensed.',
                    ];
                }

                /*

                 * Validate every hospital prescription item before

                 * creating the sale or deducting any stock.

                 */

                $processedPrescriptionItems = [];

                $subtotal = 0;

                foreach ($validated['items'] as $item) {

                    $medicine = Medicine::findOrFail(

                        $item['medicine_id']

                    );

                    $prescriptionItem = null;

                    if ($prescription) {

                        if (empty($item['prescription_item_id'])) {

                            abort(response()->json([

                                'success' => false,

                                'message' => "Every medicine sold against a hospital prescription must be linked to its prescribed item: {$medicine->name}.",

                            ], 422));

                        }

                        $prescriptionItem = $prescription->items->firstWhere(

                            'id',

                            (int) $item['prescription_item_id']

                        );

                        if (!$prescriptionItem) {

                            abort(response()->json([

                                'success' => false,

                                'message' => 'The selected prescription item does not belong to this hospital prescription.',

                            ], 422));

                        }

                        $prescriptionItem = PrescriptionItem::where(

                            'id',

                            $prescriptionItem->id

                        )

                            ->lockForUpdate()

                            ->firstOrFail();

                        if (

                            (int) $prescriptionItem->medicine_id

                            !== (int) $medicine->id

                        ) {

                            abort(response()->json([

                                'success' => false,

                                'message' => 'The selected medicine does not match the prescription.',

                            ], 422));

                        }

                        if (in_array(

                            $prescriptionItem->id,

                            $processedPrescriptionItems,

                            true

                        )) {

                            abort(response()->json([

                                'success' => false,

                                'message' => 'A prescription item cannot be added more than once.',

                            ], 422));

                        }

                        $remainingPrescribed =

                            (int) $prescriptionItem->quantity_prescribed

                            - (int) $prescriptionItem->quantity_dispensed;

                        if ($remainingPrescribed <= 0) {

                            abort(response()->json([

                                'success' => false,

                                'message' => "There is no remaining prescribed quantity for {$medicine->name}.",

                            ], 422));

                        }

                        if ((int) $item['quantity'] > $remainingPrescribed) {

                            abort(response()->json([

                                'success' => false,

                                'message' => "Quantity exceeds the remaining prescribed quantity for {$medicine->name}.",

                            ], 422));

                        }

                        $processedPrescriptionItems[] =

                            $prescriptionItem->id;

                    }

                    $batch = MedicineBatch::where(

                        'medicine_id',

                        $medicine->id

                    )

                        ->where('status', true)

                        ->where('quantity', '>', 0)

                        ->whereDate('expiry_date', '>=', now())

                        ->orderBy('expiry_date', 'asc')

                        ->first();

                    if (!$batch) {

                        abort(response()->json([

                            'success' => false,

                            'message' => "No available batch found for {$medicine->name}.",

                        ], 422));

                    }

                    if (

                        $medicine->prescription_required &&

                        !$prescriptionItem &&

                        !$request->hasFile('prescription_file')

                    ) {

                        abort(response()->json([

                            'success' => false,

                            'message' => "A prescription is required for {$medicine->name}.",

                        ], 422));

                    }

                    $subtotal +=

                        (float) $batch->selling_price

                        * (int) $item['quantity'];

                }

                $discount = (float) ($validated['discount'] ?? 0);

                if ($discount > $subtotal) {

                    abort(response()->json([

                        'success' => false,

                        'message' => 'Discount cannot be greater than the subtotal.',

                    ], 422));

                }

                $totalAmount = $subtotal - $discount;

                $paymentMethod = $validated['payment_method'];

                $cashAmount = 0;

                $mpesaAmount = 0;

                $cardAmount = 0;

                if ($paymentMethod === 'cash') {

                    $cashAmount = (float) $validated['amount_paid'];

                } elseif ($paymentMethod === 'mpesa') {

                    $mpesaAmount = (float) $validated['amount_paid'];

                } elseif ($paymentMethod === 'card') {

                    $cardAmount = (float) $validated['amount_paid'];

                } else {

                    $cashAmount = (float) ($validated['cash_amount'] ?? 0);

                    $mpesaAmount = (float) ($validated['mpesa_amount'] ?? 0);

                    $cardAmount = (float) ($validated['card_amount'] ?? 0);

                }

                $amountPaid = $cashAmount + $mpesaAmount + $cardAmount;

                if ($paymentMethod === 'split') {

                    if (abs($amountPaid - $totalAmount) > 0.01) {

                        abort(response()->json([

                            'success' => false,

                            'message' => 'Split payment must equal the total amount exactly.',

                        ], 422));

                    }

                    $changeAmount = 0;

                } else {

                    if ($amountPaid < $totalAmount) {

                        abort(response()->json([

                            'success' => false,

                            'message' => 'Amount paid is less than the total amount.',

                        ], 422));

                    }

                    $changeAmount = max(0, $amountPaid - $totalAmount);

                }

                $sale = Sale::create([

                    'sale_number' => 'SALE-'

                        . now()->format('YmdHis')

                        . '-'

                        . strtoupper(substr(uniqid(), -4)),

                    'user_id' => auth()->id(),

                    'customer_id' => $customerId,

                    'prescription_id' => $prescription?->id,

                    'customer_name' => $validated['customer_name']

                        ?? $prescription?->customer?->name

                        ?? 'Walk-in Customer',

                    'customer_phone' => $validated['customer_phone']

                        ?? $prescription?->customer?->phone,

                    'subtotal' => $subtotal,

                    'discount' => $discount,

                    'total_amount' => $totalAmount,

                    'payment_method' => $paymentMethod,

                    'amount_paid' => $amountPaid,

                    'cash_amount' => $cashAmount,

                    'mpesa_amount' => $mpesaAmount,

                    'card_amount' => $cardAmount,

                    'change_amount' => $changeAmount,

                    'status' => 'completed',

                ]);

                foreach ($validated['items'] as $item) {

                    $medicine = Medicine::findOrFail(

                        $item['medicine_id']

                    );

                    $quantityToSell = (int) $item['quantity'];

                    $prescriptionItem = null;

                    if ($prescription) {

                        $prescriptionItem = $prescription->items->firstWhere(

                            'id',

                            (int) $item['prescription_item_id']

                        );

                        $prescriptionItem = PrescriptionItem::where(

                            'id',

                            $prescriptionItem->id

                        )

                            ->lockForUpdate()

                            ->firstOrFail();

                    } elseif (!empty($item['prescription_item_id'])) {

                        abort(response()->json([

                            'success' => false,

                            'message' => 'A hospital prescription item cannot be used for this sale.',

                        ], 422));

                    }

                    $remainingQuantityToSell = $quantityToSell;

                    while ($remainingQuantityToSell > 0) {

                        $batch = MedicineBatch::where(

                            'medicine_id',

                            $medicine->id

                        )

                            ->where('status', true)

                            ->where('quantity', '>', 0)

                            ->whereDate('expiry_date', '>=', now())

                            ->orderBy('expiry_date', 'asc')

                            ->lockForUpdate()

                            ->first();

                        if (!$batch) {

                            abort(response()->json([

                                'success' => false,

                                'message' => "Insufficient stock for {$medicine->name}.",

                            ], 422));

                        }

                        $quantityFromBatch = min(

                            $remainingQuantityToSell,

                            (int) $batch->quantity

                        );

                        $itemSubtotal =

                            (float) $batch->selling_price

                            * $quantityFromBatch;

                        SaleItem::create([

                            'sale_id' => $sale->id,

                            'medicine_id' => $medicine->id,

                            'medicine_batch_id' => $batch->id,

                            'quantity' => $quantityFromBatch,

                            'unit_price' => $batch->selling_price,

                            'subtotal' => $itemSubtotal,

                            'prescription_item_id' => $prescriptionItem?->id,

                            'dosage' => $item['dosage']

                                ?? $prescriptionItem?->dosage,

                            'frequency' => $item['frequency']

                                ?? $prescriptionItem?->frequency,

                            'duration' => $item['duration']

                                ?? $prescriptionItem?->duration,

                            'route' => $item['route']

                                ?? $prescriptionItem?->route,

                            'instructions' => $item['instructions']

                                ?? $prescriptionItem?->instructions,

                        ]);

                        $batch->quantity -= $quantityFromBatch;

                        $batch->save();

                        StockMovement::create([

                            'medicine_id' => $medicine->id,

                            'medicine_batch_id' => $batch->id,

                            'type' => 'stock_out',

                            'quantity' => $quantityFromBatch,

                            'reference_type' => Sale::class,

                            'reference_id' => $sale->id,

                            'notes' => 'Sale ' . $sale->sale_number,

                            'user_id' => auth()->id(),

                        ]);

                        $remainingQuantityToSell -= $quantityFromBatch;

                    }

                    if ($prescriptionItem) {

                        $prescriptionItem->quantity_dispensed =

                            (int) $prescriptionItem->quantity_dispensed

                            + $quantityToSell;

                        $prescriptionItem->save();

                    }

                }

                if ($prescription) {

                    $prescription->refresh();

                    $totalPrescriptionItems = $prescription->items()->count();

                    $fullyDispensedItems = $prescription->items()

                        ->whereColumn(

                            'quantity_dispensed',

                            '>=',

                            'quantity_prescribed'

                        )

                        ->count();

                    $hasDispensedQuantity = $prescription->items()

                        ->where('quantity_dispensed', '>', 0)

                        ->exists();

                    if (

                        $totalPrescriptionItems > 0 &&

                        $fullyDispensedItems === $totalPrescriptionItems

                    ) {

                        $prescription->status = 'dispensed';

                    } elseif ($hasDispensedQuantity) {

                        $prescription->status = 'partially_dispensed';

                    } else {

                        $prescription->status = 'verified';

                    }

                    $prescription->save();

                }

                $hasPrescriptionRequiredMedicine = false;

                foreach ($validated['items'] as $item) {

                    $medicine = Medicine::findOrFail(

                        $item['medicine_id']

                    );

                    if ($medicine->prescription_required) {

                        $hasPrescriptionRequiredMedicine = true;

                        break;

                    }

                }

                $hasExternalPrescriptionFile = $request->hasFile(

                    'prescription_file'

                );

                $shouldSaveExternalPrescription =

                    !$prescription &&

                    (

                        $hasPrescriptionRequiredMedicine ||

                        (

                            $saleType === 'external_prescription' &&

                            $hasExternalPrescriptionFile

                        )

                    );

                if ($shouldSaveExternalPrescription) {

                    if (!$hasExternalPrescriptionFile) {

                        abort(response()->json([

                            'success' => false,

                            'message' => $saleType === 'external_prescription'

                                ? 'Please upload or capture the external prescription before completing the sale.'

                                : 'A prescription file is required for prescription medicines.',

                        ], 422));

                    }

                    $storedPrescriptionFile = $request

                        ->file('prescription_file')

                        ->store('prescriptions', 'public');

                    $externalPrescription = Prescription::create([

                        'customer_id' => $customerId,

                        'sale_id' => $sale->id,

                        'prescription_number' =>

                            $validated['prescription_number'] ?? null,

                        'prescriber_name' =>

                            !empty(trim($validated['prescriber_name'] ?? ''))
                                ? trim($validated['prescriber_name'])
                                : 'Unknown Prescriber',

                        'prescription_date' =>

                            $validated['prescription_date'] ?? null,

                        'prescription_file' => $storedPrescriptionFile,

                        'status' => 'dispensed',

                    ]);

                    foreach ($validated['items'] as $item) {

                        $medicine = Medicine::findOrFail(

                            $item['medicine_id']

                        );

                        if (!$medicine->prescription_required) {

                            continue;

                        }

                        PrescriptionItem::create([

                            'prescription_id' => $externalPrescription->id,

                            'medicine_id' => $medicine->id,

                            'quantity_prescribed' => $item['quantity'],

                            'quantity_dispensed' => $item['quantity'],

                            'dosage' => $item['dosage'] ?? null,

                            'frequency' => $item['frequency'] ?? null,

                            'duration' => $item['duration'] ?? null,

                            'route' => $item['route'] ?? null,

                            'instructions' => $item['instructions'] ?? null,

                        ]);

                    }

                    $sale->prescription_id = $externalPrescription->id;

                    $sale->save();

                }

                return $sale->load([

                    'customer',

                    'user',

                    'items.medicine',

                    'items.medicineBatch',

                    'prescription.items.medicine',

                ]);

            });

            if (is_array($sale) && !empty($sale['unavailable_only'])) {
                return response()->json([
                    'success' => true,
                    'message' => $sale['message'],
                    'prescription_id' => $sale['prescription_id'],
                    'redirect' => route('pharmacy.queue.show', $sale['prescription_id']),
                ]);
            }

            return response()->json([

                'success' => true,

                'message' => 'Sale completed successfully.',

                'sale' => $sale,

                'sale_id' => $sale->id,

                'receipt_url' => route('sales.receipt', $sale),

                'redirect' => route('sales.receipt', $sale),

                'payment' => [

                    'method' => $sale->payment_method,

                    'amount_paid' => $sale->amount_paid,

                    'cash_amount' => $sale->cash_amount,

                    'mpesa_amount' => $sale->mpesa_amount,

                    'card_amount' => $sale->card_amount,

                    'change_amount' => $sale->change_amount,

                ],

            ]);

        } catch (\Throwable $e) {

            if ($storedPrescriptionFile) {

                Storage::disk('public')->delete(

                    $storedPrescriptionFile

                );

            }

            // abort(response()->json(...)) throws HttpResponseException.
            // Return its original response instead of swallowing it and showing
            // an empty exception message in the browser.
            if ($e instanceof \Illuminate\Http\Exceptions\HttpResponseException) {
                return $e->getResponse();
            }

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                throw $e;
            }

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                throw $e;
            }

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

            ], 422);

        }

    }

}
