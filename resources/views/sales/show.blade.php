@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')

<div class="header">


<div>

    <h2>Sale Details</h2>

    <p style="color:#666;">
        {{ $sale->sale_number }}
    </p>

</div>

<div class="actions">

    <a
        href="{{ route('sales.history') }}"
        class="btn btn-secondary"
    >
        ← Back to Sales
    </a>

    <a
        href="{{ route('sales.receipt', $sale) }}"
        class="btn btn-primary"
        target="_blank"
    >
        Print Receipt
    </a>

</div>


</div>

{{-- ========================================================= --}}
{{-- CUSTOMER / PATIENT PROFILE --}}
{{-- ========================================================= --}}

<div class="customer-profile-card">


<div class="customer-profile-header">

    <div>

        <span class="profile-label">
            CUSTOMER / PATIENT
        </span>

        <h3>

            @if($sale->customer)

                {{ $sale->customer->name }}

            @else

                {{ $sale->customer_name ?: 'Walk-in Customer' }}

            @endif

        </h3>

        <p>

            Phone:

            <strong>

                @if($sale->customer)

                    {{ $sale->customer->phone ?: 'Not provided' }}

                @else

                    {{ $sale->customer_phone ?: 'Not provided' }}

                @endif

            </strong>

        </p>

    </div>


    @if($sale->customer)

        <a
            href="{{ route(
                'sales.history',
                ['customer_id' => $sale->customer->id]
            ) }}"
            class="btn btn-secondary"
        >
            View Purchase History
        </a>

    @endif

</div>


</div>

{{-- ========================================================= --}}
{{-- SALE INFORMATION --}}
{{-- ========================================================= --}}

<div class="form-container">

<div class="form-grid">

    <div>

        <strong>
            Sale Number
        </strong>

        <p>
            {{ $sale->sale_number }}
        </p>

    </div>


    <div>

        <strong>
            Date
        </strong>

        <p>
            {{ $sale->created_at->format('d M Y H:i:s') }}
        </p>

    </div>


    <div>

        <strong>
            Payment Method
        </strong>

        <p>
            {{ strtoupper($sale->payment_method) }}
        </p>

    </div>


    <div>

        <strong>
            Status
        </strong>

        <p>
            {{ ucfirst($sale->status) }}
        </p>

    </div>

</div>


</div>

<br>

{{-- ========================================================= --}}
{{-- MEDICINES --}}
{{-- ========================================================= --}}

<div class="table-container">


<table>

    <thead>

        <tr>

            <th>Medicine</th>

            <th>Type</th>

            <th>Batch</th>

            <th>Quantity</th>

            <th>Unit Price</th>

            <th>Subtotal</th>

        </tr>

    </thead>


    <tbody>

    @foreach($sale->items as $item)

        <tr>

            {{-- MEDICINE --}}

            <td>

                <strong>

                    {{ $item->medicine->name ?? 'Unknown Medicine' }}

                </strong>


                @if($item->medicine && $item->medicine->strength)

                    <div class="medicine-strength">

                        {{ $item->medicine->strength }}

                    </div>

                @endif

            </td>


            {{-- TYPE --}}

            <td>

                @if(
                    $item->medicine &&
                    $item->medicine->prescription_required
                )

                    <span class="pom-badge">
                        POM — Prescription
                    </span>

                @else

                    <span class="otc-badge">
                        OTC — Non-Prescription
                    </span>

                @endif

            </td>


            {{-- BATCH --}}

            <td>

                {{ $item->medicineBatch->batch_number ?? '-' }}

            </td>


            {{-- QUANTITY --}}

            <td>

                {{ $item->quantity }}

            </td>


            {{-- UNIT PRICE --}}

            <td>

                KSh
                {{ number_format(
                    $item->unit_price,
                    2
                ) }}

            </td>


            {{-- SUBTOTAL --}}

            <td>

                <strong>

                    KSh
                    {{ number_format(
                        $item->subtotal,
                        2
                    ) }}

                </strong>

            </td>

        </tr>

    @endforeach

    </tbody>

</table>


</div>

<br>

{{-- ========================================================= --}}
{{-- PRESCRIPTION DETAILS --}}
{{-- ========================================================= --}}

@if($sale->prescription)


<div class="prescription-card">

    <div class="prescription-header">

        <div>

            <span class="profile-label">
                PRESCRIPTION / DISPENSING
            </span>

            <h3>
                💊 Prescription Details
            </h3>

        </div>

        <span class="pom-badge">
            POM
        </span>

    </div>


    {{-- ================================================= --}}
    {{-- PRESCRIPTION INFORMATION --}}
    {{-- ================================================= --}}

    <div class="prescription-grid">

        {{-- PRESCRIPTION NUMBER --}}

        <div>

            <span>
                Prescription No.
            </span>

            <strong>
                {{ $sale->prescription->prescription_number ?? '-' }}
            </strong>

        </div>


        {{-- PRESCRIBER --}}

        <div>

            <span>
                Prescriber
            </span>

            <strong>
                {{ $sale->prescription->prescriber_name ?? '-' }}
            </strong>

        </div>


        {{-- PRESCRIPTION DATE --}}

        <div>

            <span>
                Prescription Date
            </span>

            <strong>

                @if($sale->prescription->prescription_date)

                    {{ $sale->prescription->prescription_date->format('d M Y') }}

                @else

                    -

                @endif

            </strong>

        </div>


        {{-- PATIENT --}}

        <div>

            <span>
                Patient
            </span>

            <strong>

                @if($sale->prescription->customer)

                    {{ $sale->prescription->customer->name }}

                @elseif($sale->customer)

                    {{ $sale->customer->name }}

                @else

                    {{ $sale->customer_name ?: 'Walk-in Customer' }}

                @endif

            </strong>

        </div>


        {{-- VERIFICATION STATUS --}}

        <div>

            <span>
                Verification
            </span>

            <strong>

                @if($sale->prescription->verified)

                    <span class="verified-badge">
                        ✓ Verified
                    </span>

                @else

                    <span class="unverified-badge">
                        Not Verified
                    </span>

                @endif

            </strong>

        </div>


        {{-- VERIFIED BY --}}

        <div>

            <span>
                Verified By
            </span>

            <strong>
                {{ $sale->prescription->verified_by ?? '-' }}
            </strong>

        </div>


        {{-- VERIFIED AT --}}

        <div>

            <span>
                Verified At
            </span>

            <strong>

                @if($sale->prescription->verified_at)

                    {{ $sale->prescription->verified_at->format('d M Y H:i') }}

                @else

                    -

                @endif

            </strong>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- PRESCRIPTION ITEMS --}}
    {{-- ================================================= --}}

    @if($sale->prescription->items->count())

        <div class="prescription-items">

            <h4>
                Prescribed Medicines
            </h4>


            <div class="table-container">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Medicine
                            </th>

                            <th>
                                Dosage
                            </th>

                            <th>
                                Frequency
                            </th>

                            <th>
                                Duration
                            </th>

                            <th>
                                Quantity Prescribed
                            </th>

                            <th>
                                Dispensed
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @foreach(
                        $sale->prescription->items
                        as $prescriptionItem
                    )

                        <tr>

                            {{-- MEDICINE --}}

                            <td>

                                <strong>

                                    {{ $prescriptionItem->medicine->name
                                        ?? 'Unknown Medicine' }}

                                </strong>


                                @if(
                                    $prescriptionItem->medicine &&
                                    $prescriptionItem->medicine->strength
                                )

                                    <small class="medicine-strength">

                                        {{ $prescriptionItem->medicine->strength }}

                                    </small>

                                @endif

                            </td>


                            {{-- DOSAGE --}}

                            <td>

                                {{ $prescriptionItem->dosage ?? '-' }}

                            </td>


                            {{-- FREQUENCY --}}

                            <td>

                                {{ $prescriptionItem->frequency ?? '-' }}

                            </td>


                            {{-- DURATION --}}

                            <td>

                                {{ $prescriptionItem->duration ?? '-' }}

                            </td>


                            {{-- QUANTITY PRESCRIBED --}}

                            <td>

                                {{ $prescriptionItem->quantity_prescribed ?? '-' }}

                            </td>


                            {{-- QUANTITY DISPENSED --}}

                            <td>

                                {{ $prescriptionItem->quantity_dispensed ?? '-' }}

                            </td>

                        </tr>


                        {{-- PRESCRIPTION ITEM NOTES --}}

                        @if(!empty($prescriptionItem->notes))

                            <tr>

                                <td colspan="6">

                                    <div class="prescription-item-notes">

                                        <strong>
                                            Notes:
                                        </strong>

                                        {{ $prescriptionItem->notes }}

                                    </div>

                                </td>

                            </tr>

                        @endif

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @else

        <div class="no-prescription-items">

            <p>
                A prescription is attached to this sale,
                but no prescription medicines were recorded.
            </p>

        </div>

    @endif


    {{-- ================================================= --}}
    {{-- PRESCRIPTION NOTES --}}
    {{-- ================================================= --}}

    @if(!empty($sale->prescription->notes))

        <div class="prescription-notes">

            <span>
                Prescription Notes
            </span>

            <p>
                {{ $sale->prescription->notes }}
            </p>

        </div>

    @endif

</div>


@else


{{-- ================================================= --}}
{{-- OTC INFORMATION --}}
{{-- ================================================= --}}

<div class="otc-information">

    <div>

        <span class="otc-badge">
            OTC
        </span>

        <h3>
            Over-the-Counter Sale
        </h3>

        <p>
            No prescription was attached to this sale.
        </p>

    </div>

</div>


@endif

<br>

{{-- ========================================================= --}}
{{-- SALE TOTALS --}}
{{-- ========================================================= --}}

<div class="form-container">

<div style="max-width:400px;margin-left:auto;">

    <p>

        <strong>
            Subtotal:
        </strong>

        KSh
        {{ number_format(
            $sale->subtotal,
            2
        ) }}

    </p>


    <p>

        <strong>
            Discount:
        </strong>

        KSh
        {{ number_format(
            $sale->discount,
            2
        ) }}

    </p>


    <hr>


    <h2>

        Total:

        KSh
        {{ number_format(
            $sale->total_amount,
            2
        ) }}

    </h2>


    <p>

        <strong>
            Amount Paid:
        </strong>

        KSh
        {{ number_format(
            $sale->amount_paid,
            2
        ) }}

    </p>


    <p>

        <strong>
            Change:
        </strong>

        KSh
        {{ number_format(
            $sale->change_amount,
            2
        ) }}

    </p>

</div>


</div>

{{-- ========================================================= --}}
{{-- STYLES --}}
{{-- ========================================================= --}}

<style>

.customer-profile-card {

    background: white;

    padding: 20px;

    margin-bottom: 20px;

    border-radius: 8px;

    box-shadow:
        0 2px 8px rgba(0,0,0,0.08);

    border-left:
        4px solid #1e3a8a;

}


.customer-profile-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 20px;

}


.profile-label {

    display: block;

    color: #1e3a8a;

    font-size: 11px;

    font-weight: bold;

    margin-bottom: 5px;

}


.customer-profile-header h3 {

    margin: 0;

    font-size: 22px;

}


.customer-profile-header p {

    margin: 6px 0 0;

    color: #666;

}


.medicine-strength {

    display: block;

    color: #777;

    font-size: 12px;

    margin-top: 3px;

}


/* ========================================================= */
/* POM / OTC BADGES */
/* ========================================================= */

.pom-badge {

    display: inline-block;

    background: #fee2e2;

    color: #991b1b;

    padding: 5px 8px;

    border-radius: 5px;

    font-size: 10px;

    font-weight: bold;

}


.otc-badge {

    display: inline-block;

    background: #dcfce7;

    color: #166534;

    padding: 5px 8px;

    border-radius: 5px;

    font-size: 10px;

    font-weight: bold;

}


/* ========================================================= */
/* PRESCRIPTION CARD */
/* ========================================================= */

.prescription-card {

    background: #fff7ed;

    border:
        1px solid #fed7aa;

    border-radius: 8px;

    padding: 20px;

    box-shadow:
        0 2px 8px rgba(0,0,0,0.05);

}


.prescription-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 20px;

}


.prescription-header h3 {

    margin: 0;

    color: #1e3a8a;

}


/* ========================================================= */
/* PRESCRIPTION GRID */
/* ========================================================= */

.prescription-grid {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 12px;

}


.prescription-grid > div {

    background: white;

    padding: 12px;

    border-radius: 5px;

    border:
        1px solid #f1f1f1;

}


.prescription-grid span {

    display: block;

    color: #777;

    font-size: 11px;

    margin-bottom: 5px;

}


.prescription-grid strong {

    display: block;

    font-size: 13px;

}


.verified-badge {

    display: inline-block;

    background: #dcfce7;

    color: #166534;

    padding: 4px 7px;

    border-radius: 4px;

    font-size: 11px;

}


.unverified-badge {

    display: inline-block;

    background: #fee2e2;

    color: #991b1b;

    padding: 4px 7px;

    border-radius: 4px;

    font-size: 11px;

}


/* ========================================================= */
/* PRESCRIPTION ITEMS */
/* ========================================================= */

.prescription-items {

    margin-top: 20px;

}


.prescription-items h4 {

    margin:
        0 0 12px;

    color: #1e3a8a;

}


.prescription-item-notes {

    background: #fff;

    padding: 8px 10px;

    border-radius: 5px;

    color: #666;

    font-size: 12px;

}


.no-prescription-items {

    background: white;

    padding: 12px;

    margin-top: 15px;

    border-radius: 5px;

    color: #777;

}


/* ========================================================= */
/* PRESCRIPTION NOTES */
/* ========================================================= */

.prescription-notes {

    margin-top: 15px;

    background: white;

    padding: 12px;

    border-radius: 5px;

}


.prescription-notes span {

    display: block;

    color: #777;

    font-size: 11px;

    margin-bottom: 5px;

}


.prescription-notes p {

    margin: 0;

    font-size: 13px;

}


/* ========================================================= */
/* OTC INFORMATION */
/* ========================================================= */

.otc-information {

    background: white;

    padding: 18px 20px;

    border-radius: 8px;

    border-left:
        4px solid #16a34a;

    box-shadow:
        0 2px 8px rgba(0,0,0,0.06);

}


.otc-information h3 {

    margin:
        8px 0 5px;

    color: #166534;

}


.otc-information p {

    margin: 0;

    color: #777;

}


/* ========================================================= */
/* RESPONSIVE */
/* ========================================================= */

@media (max-width: 1000px) {

    .prescription-grid {

        grid-template-columns:
            repeat(2, 1fr);

    }

}


@media (max-width: 900px) {

    .customer-profile-header {

        flex-direction: column;

        align-items: flex-start;

    }

}


@media (max-width: 650px) {

    .prescription-grid {

        grid-template-columns: 1fr;

    }

}

</style>

@endsection
