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

class SaleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SALE LIST / POS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $medicines = Medicine::with([
            'batches' => function ($query) {
                $query->where('quantity', '>', 0)
                    ->where('status', true)
                    ->whereDate('expiry_date', '>=', now()->toDateString())
                    ->orderBy('expiry_date');
            }
        ])
            ->whereHas('batches', function ($query) {
                $query->where('quantity', '>', 0)
                    ->where('status', true)
                    ->whereDate('expiry_date', '>=', now()->toDateString());
            })
            ->orderBy('name')
            ->get();

        return view('sales.index', compact('medicines'));
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER SEARCH
    |--------------------------------------------------------------------------
    */

    public function searchCustomers(Request $request)
    {
        $search = $request->input('search');

        $customers = Customer::where('name', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%")
            ->limit(10)
            ->get([
                'id',
                'name',
                'phone'
            ]);

        return response()->json($customers);
    }


    /*
    |--------------------------------------------------------------------------
    | SALE HISTORY
    |--------------------------------------------------------------------------
    */

    public function history(Request $request)
    {
        $customerSearch = $request->input('customer');

        $query = Sale::with([
            'customer',
            'user',
            'items.medicine',
            'items.medicineBatch',
            'prescription.items',
            'prescription.customer',
        ])
            ->where('status', 'completed')
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER SEARCH
        |--------------------------------------------------------------------------
        */

        if ($customerSearch) {
            $query->where(function ($q) use ($customerSearch) {
                $q->where('customer_name', 'like', "%{$customerSearch}%")
                    ->orWhere('customer_phone', 'like', "%{$customerSearch}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($customerSearch) {
                        $customerQuery
                            ->where('name', 'like', "%{$customerSearch}%")
                            ->orWhere('phone', 'like', "%{$customerSearch}%");
                    });
            });
        }

        $sales = $query->paginate(20)->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER PROFILE / SUMMARY
        |--------------------------------------------------------------------------
        */

        $customer = null;
        $customerStats = null;

        if ($customerSearch) {
            $customer = Customer::where('name', 'like', "%{$customerSearch}%")
                ->orWhere('phone', 'like', "%{$customerSearch}%")
                ->first();

            if ($customer) {
                $customerStats = [
                    'total_sales' => Sale::where('customer_id', $customer->id)
                        ->where('status', 'completed')
                        ->count(),

                    'total_purchased' => Sale::where('customer_id', $customer->id)
                        ->where('status', 'completed')
                        ->sum('total_amount'),

                    'last_purchase' => Sale::where('customer_id', $customer->id)
                        ->where('status', 'completed')
                        ->latest()
                        ->first(),
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | GENERAL SALES SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalSales = Sale::where('status', 'completed')->count();

        $totalPurchased = Sale::where('status', 'completed')
            ->sum('total_amount');

        $lastPurchase = Sale::where('status', 'completed')
            ->latest()
            ->first();

        return view('sales.history', compact(
            'sales',
            'customer',
            'customerStats',
            'totalSales',
            'totalPurchased',
            'lastPurchase'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW SALE
    |--------------------------------------------------------------------------
    */

    public function show(Sale $sale)
    {
        $sale->load([
            'items.medicine',
            'items.medicineBatch',
            'user',
            'customer',
            'prescription.items',
            'prescription.customer',
        ]);

        return view('sales.show', compact('sale'));
    }


    /*
    |--------------------------------------------------------------------------
    | RECEIPT
    |--------------------------------------------------------------------------
    */

    public function receipt(Sale $sale)
    {
        $sale->load([
            'items.medicine',
            'items.medicineBatch',
            'user',
            'customer',
            'prescription.items',
            'prescription.customer',
        ]);

        return view('sales.receipt', compact('sale'));
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE SALE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'customer_id' => [
                'nullable',
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

            /*
            |--------------------------------------------------------------------------
            | PAYMENT METHOD
            |--------------------------------------------------------------------------
            */

            'payment_method' => [
                'required',
                'in:cash,mpesa,card,split',
            ],

            /*
            |--------------------------------------------------------------------------
            | NORMAL PAYMENT AMOUNT
            |--------------------------------------------------------------------------
            */

            'amount_paid' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | SPLIT PAYMENT AMOUNTS
            |--------------------------------------------------------------------------
            */

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

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.medicine_id' => [
                'required',
                'exists:medicines,id',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.selling_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | DISPENSING INFORMATION
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | PRESCRIPTION INFORMATION
            |--------------------------------------------------------------------------
            */

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
                'max:255',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */

        try {

            $sale = DB::transaction(function () use ($validated, $request) {

                /*
                |--------------------------------------------------------------------------
                | CUSTOMER
                |--------------------------------------------------------------------------
                */

                $customerId = $validated['customer_id'] ?? null;

                if (!$customerId && !empty($validated['customer_phone'])) {

                    $customer = Customer::firstOrCreate(
                        [
                            'phone' => $validated['customer_phone'],
                        ],
                        [
                            'name' => $validated['customer_name'] ?? 'Walk-in Customer',
                        ]
                    );

                    $customerId = $customer->id;
                }


                /*
                |--------------------------------------------------------------------------
                | SUBTOTAL
                |--------------------------------------------------------------------------
                */

                $subtotal = 0;

                foreach ($validated['items'] as $item) {

                    $subtotal +=
                        $item['selling_price'] *
                        $item['quantity'];
                }


                /*
                |--------------------------------------------------------------------------
                | DISCOUNT
                |--------------------------------------------------------------------------
                */

                $discount = $validated['discount'] ?? 0;

                if ($discount > $subtotal) {
                    throw new \Exception(
                        'Discount cannot be greater than the subtotal.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | TOTAL
                |--------------------------------------------------------------------------
                */

                $totalAmount =
                    $subtotal - $discount;


                /*
                |--------------------------------------------------------------------------
                | PAYMENT
                |--------------------------------------------------------------------------
                */

                $paymentMethod =
                    $validated['payment_method'];


                /*
                |--------------------------------------------------------------------------
                | DEFAULT PAYMENT AMOUNTS
                |--------------------------------------------------------------------------
                */

                $cashAmount = 0;

                $mpesaAmount = 0;

                $cardAmount = 0;


                /*
                |--------------------------------------------------------------------------
                | SINGLE PAYMENT
                |--------------------------------------------------------------------------
                */

                if ($paymentMethod === 'cash') {

                    $cashAmount =
                        $validated['amount_paid'] ?? 0;

                } elseif ($paymentMethod === 'mpesa') {

                    $mpesaAmount =
                        $validated['amount_paid'] ?? 0;

                } elseif ($paymentMethod === 'card') {

                    $cardAmount =
                        $validated['amount_paid'] ?? 0;

                } elseif ($paymentMethod === 'split') {

                    $cashAmount =
                        $validated['cash_amount'] ?? 0;

                    $mpesaAmount =
                        $validated['mpesa_amount'] ?? 0;

                    $cardAmount =
                        $validated['card_amount'] ?? 0;
                }


                /*
                |--------------------------------------------------------------------------
                | TOTAL AMOUNT PAID
                |--------------------------------------------------------------------------
                */

                $amountPaid =
                    $cashAmount +
                    $mpesaAmount +
                    $cardAmount;


                /*
                |--------------------------------------------------------------------------
                | CHECK PAYMENT
                |--------------------------------------------------------------------------
                */

                if ($amountPaid < $totalAmount) {

                    throw new \Exception(
                        'Amount paid is less than the total amount.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | CHANGE
                |--------------------------------------------------------------------------
                */

                $changeAmount =
                    max(
                        0,
                        $amountPaid - $totalAmount
                    );


                /*
                |--------------------------------------------------------------------------
                | SALE NUMBER
                |--------------------------------------------------------------------------
                */

                $saleNumber =
                    'SALE-' .
                    now()->format('YmdHis') .
                    '-' .
                    strtoupper(
                        substr(uniqid(), -4)
                    );


                /*
                |--------------------------------------------------------------------------
                | CREATE SALE
                |--------------------------------------------------------------------------
                */

                $sale = Sale::create([

                    'sale_number' =>
                        $saleNumber,

                    'user_id' =>
                        auth()->id(),

                    'customer_id' =>
                        $customerId,

                    'customer_name' =>
                        $validated['customer_name'] ?? null,

                    'customer_phone' =>
                        $validated['customer_phone'] ?? null,

                    'subtotal' =>
                        $subtotal,

                    'discount' =>
                        $discount,

                    'total_amount' =>
                        $totalAmount,

                    'payment_method' =>
                        $paymentMethod,

                    'amount_paid' =>
                        $amountPaid,

                    'cash_amount' =>
                        $cashAmount,

                    'mpesa_amount' =>
                        $mpesaAmount,

                    'card_amount' =>
                        $cardAmount,

                    'change_amount' =>
                        $changeAmount,

                    'status' =>
                        'completed',
                ]);


                /*
                |--------------------------------------------------------------------------
                | PRESCRIPTION FLAG
                |--------------------------------------------------------------------------
                */

                $hasPrescriptionItems = false;


                /*
                |--------------------------------------------------------------------------
                | PROCESS EACH SALE ITEM
                |--------------------------------------------------------------------------
                */

                foreach ($validated['items'] as $item) {

                    /*
                    |--------------------------------------------------------------------------
                    | MEDICINE
                    |--------------------------------------------------------------------------
                    */

                    $medicine = Medicine::findOrFail(
                        $item['medicine_id']
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | PRESCRIPTION CHECK
                    |--------------------------------------------------------------------------
                    */

                    $isPrescriptionRequired =
                        (bool) $medicine->prescription_required;


                    if ($isPrescriptionRequired) {

                        $hasPrescriptionItems = true;

                        /*
                        |--------------------------------------------------------------------------
                        | REQUIRED PRESCRIPTION DETAILS
                        |--------------------------------------------------------------------------
                        */

                        if (
                            empty($validated['prescriber_name']) ||
                            empty($item['dosage']) ||
                            empty($item['frequency']) ||
                            empty($item['duration'])
                        ) {
                            throw new \Exception(
                                'Prescription details are required for ' .
                                $medicine->name .
                                '.'
                            );
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | FEFO BATCH SELECTION
                    |--------------------------------------------------------------------------
                    */

                    $remainingQuantity =
                        $item['quantity'];


                    $batches = MedicineBatch::where(
                        'medicine_id',
                        $medicine->id
                    )
                        ->where('quantity', '>', 0)
                        ->where('status', true)
                        ->whereDate(
                            'expiry_date',
                            '>=',
                            now()->toDateString()
                        )
                        ->orderBy('expiry_date')
                        ->lockForUpdate()
                        ->get();


                    /*
                    |--------------------------------------------------------------------------
                    | CHECK AVAILABLE STOCK
                    |--------------------------------------------------------------------------
                    */

                    $availableQuantity =
                        $batches->sum('quantity');


                    if (
                        $availableQuantity <
                        $remainingQuantity
                    ) {
                        throw new \Exception(
                            'Insufficient stock for ' .
                            $medicine->name .
                            '. Available: ' .
                            $availableQuantity .
                            ', requested: ' .
                            $remainingQuantity .
                            '.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | DISPENSE USING FEFO
                    |--------------------------------------------------------------------------
                    */

                    foreach ($batches as $batch) {

                        if ($remainingQuantity <= 0) {
                            break;
                        }


                        $quantityFromBatch =
                            min(
                                $remainingQuantity,
                                $batch->quantity
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | CREATE SALE ITEM
                        |--------------------------------------------------------------------------
                        */
SaleItem::create([

    'sale_id' =>
        $sale->id,

    'medicine_id' =>
        $medicine->id,

    'medicine_batch_id' =>
        $batch->id,

    'quantity' =>
        $quantityFromBatch,

    'unit_price' =>
        $item['selling_price'],

    'subtotal' =>
        $item['selling_price'] *
        $quantityFromBatch,

    'dosage' =>
        $item['dosage'] ?? null,

    'frequency' =>
        $item['frequency'] ?? null,

    'duration' =>
        $item['duration'] ?? null,

    'route' =>
        $item['route'] ?? null,

    'instructions' =>
        $item['instructions'] ?? null,
]);


                        /*
                        |--------------------------------------------------------------------------
                        | REDUCE STOCK
                        |--------------------------------------------------------------------------
                        */

                        $batch->quantity -=
                            $quantityFromBatch;

                        $batch->save();


                        /*
                        |--------------------------------------------------------------------------
                        | STOCK MOVEMENT
                        |--------------------------------------------------------------------------
                        */

                        StockMovement::create([

                            'medicine_id' =>
                                $medicine->id,

                            'medicine_batch_id' =>
                                $batch->id,

                            'type' =>
                                'stock_out',

                            'quantity' =>
                                $quantityFromBatch,

                            'reference_type' =>
                                Sale::class,

                            'reference_id' =>
                                $sale->id,

                            'notes' =>
                                'Sale ' .
                                $sale->sale_number,
                        ]);


                        /*
                        |--------------------------------------------------------------------------
                        | UPDATE REMAINING QUANTITY
                        |--------------------------------------------------------------------------
                        */

                        $remainingQuantity -=
                            $quantityFromBatch;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | CREATE PRESCRIPTION
                |--------------------------------------------------------------------------
                */

                if ($hasPrescriptionItems) {

                    $prescription =
                        Prescription::create([

                            'sale_id' =>
                                $sale->id,

                            'customer_id' =>
                                $customerId,

                            'prescriber_name' =>
                                $validated['prescriber_name'],

                            'prescription_date' =>
                                $validated['prescription_date']
                                ?? now()->toDateString(),

                            'prescription_number' =>
                                $validated['prescription_number']
                                ?? null,
                        ]);


                    /*
                    |--------------------------------------------------------------------------
                    | PRESCRIPTION ITEMS
                    |--------------------------------------------------------------------------
                    */

                    foreach ($validated['items'] as $item) {

                        $medicine =
                            Medicine::find(
                                $item['medicine_id']
                            );


                        if (
                            !$medicine ||
                            !$medicine->prescription_required
                        ) {
                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | PRESCRIBED QUANTITY
                        |--------------------------------------------------------------------------
                        */

                        $quantityPrescribed =
                            $item['quantity'];


                        /*
                        |--------------------------------------------------------------------------
                        | CREATE PRESCRIPTION ITEM
                        |--------------------------------------------------------------------------
                        */

                        PrescriptionItem::create([

                            'prescription_id' =>
                                $prescription->id,

                            'medicine_id' =>
                                $medicine->id,

                            'quantity_prescribed' =>
                                $quantityPrescribed,

                            'dosage' =>
                                $item['dosage'] ?? null,

                            'frequency' =>
                                $item['frequency'] ?? null,

                            'duration' =>
                                $item['duration'] ?? null,

                            'route' =>
                                $item['route'] ?? null,

                            'instructions' =>
                                $item['instructions'] ?? null,
                        ]);
                    }
                }


                return $sale;
            });


            /*
            |--------------------------------------------------------------------------
            | LOAD SALE RELATIONSHIPS
            |--------------------------------------------------------------------------
            */

            $sale->load([
                'items.medicine',
                'items.medicineBatch',
                'user',
                'customer',
                'prescription.items',
                'prescription.customer',
            ]);


            /*
            |--------------------------------------------------------------------------
            | SUCCESS RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' =>
                    true,

                'message' =>
                    'Sale completed successfully.',

                'sale' =>
                    $sale,

                'payment' => [

                    'method' =>
                        $sale->payment_method,

                    'amount_paid' =>
                        $sale->amount_paid,

                    'cash_amount' =>
                        $sale->cash_amount,

                    'mpesa_amount' =>
                        $sale->mpesa_amount,

                    'card_amount' =>
                        $sale->card_amount,

                    'change_amount' =>
                        $sale->change_amount,
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | ERROR HANDLING
        |--------------------------------------------------------------------------
        */

        catch (\Exception $e) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    $e->getMessage(),

            ], 422);
        }
    }
}

