

@extends('layouts.app')



@section('title', 'Dispense Hospital Prescription')



@section('content')

<style>

    .dispensing-page,

    .dispensing-page * {

        box-sizing: border-box;

    }



    .dispensing-page {

        max-width: 1250px;

        margin: 0 auto;

        color: #172033;

    }



    .dispensing-page .back-link {

        display: inline-block;

        margin-bottom: 20px;

        color: #2563eb;

        font-weight: 650;

        text-decoration: none;

    }



    .dispensing-page .back-link:hover {

        text-decoration: underline;

    }



    .dispensing-header {

        display: flex;

        justify-content: space-between;

        align-items: flex-start;

        flex-wrap: wrap;

        gap: 15px;

        margin-bottom: 22px;

    }



    .dispensing-header h2 {

        margin: 0 0 7px;

        color: #172033;

        font-size: 27px;

        font-weight: 750;

    }



    .dispensing-header p {

        margin: 0;

        color: #718096;

        font-size: 14px;

    }



    .dispensing-card {

        padding: 22px;

        margin-bottom: 20px;

        border: 1px solid #e2e8f0;

        border-radius: 13px;

        background: #fff;

        box-shadow: 0 3px 12px rgba(15, 23, 42, .035);

    }



    .dispensing-card h3 {

        margin: 0 0 18px;

        color: #172033;

        font-size: 18px;

        font-weight: 750;

    }



    .patient-details {

        display: grid;

        grid-template-columns: repeat(3, minmax(0, 1fr));

        gap: 20px;

    }



    .detail-label {

        display: block;

        margin-bottom: 6px;

        color: #718096;

        font-size: 12px;

    }



    .detail-value {

        color: #263449;

        font-size: 14px;

        font-weight: 650;

        overflow-wrap: anywhere;

    }



    .prescription-status {

        display: inline-flex;

        align-items: center;

        padding: 7px 11px;

        border-radius: 20px;

        background: #eff6ff;

        color: #1d4ed8;

        font-size: 12px;

        font-weight: 750;

    }



    .prescription-notes {

        margin-top: 20px;

        padding: 14px;

        border-radius: 9px;

        background: #f8fafc;

        color: #475569;

        font-size: 13px;

        line-height: 1.6;

    }



    .dispensing-intro {

        margin: -7px 0 20px;

        color: #718096;

        font-size: 13px;

        line-height: 1.6;

    }



    .medicine-list {

        display: grid;

        gap: 17px;

    }



    .medicine-item {

        padding: 19px;

        border: 1px solid #e2e8f0;

        border-radius: 12px;

        background: #fff;

    }



    .medicine-item.is-unavailable {

        border-color: #fecaca;

        background: #fffafa;

    }



    .medicine-item.is-complete {

        border-color: #bbf7d0;

        background: #f7fef9;

    }



    .medicine-top {

        display: flex;

        justify-content: space-between;

        align-items: flex-start;

        gap: 12px;

        margin-bottom: 16px;

    }



    .medicine-name {

        margin: 0 0 5px;

        color: #172033;

        font-size: 16px;

        font-weight: 750;

    }



    .medicine-strength {

        color: #718096;

        font-size: 12px;

    }



    .medicine-remaining {

        display: inline-block;

        padding: 6px 9px;

        border-radius: 7px;

        background: #fff7ed;

        color: #9a3412;

        font-size: 12px;

        font-weight: 750;

        white-space: nowrap;

    }



    .medicine-complete-badge {

        display: inline-block;

        padding: 6px 9px;

        border-radius: 7px;

        background: #dcfce7;

        color: #166534;

        font-size: 12px;

        font-weight: 750;

    }



    .medicine-directions {

        display: grid;

        grid-template-columns: repeat(2, minmax(0, 1fr));

        gap: 10px;

        padding: 13px;

        margin-bottom: 16px;

        border: 1px solid #edf0f5;

        border-radius: 9px;

        background: #f8fafc;

    }



    .direction-label {

        display: block;

        margin-bottom: 4px;

        color: #718096;

        font-size: 11px;

    }



    .direction-value {

        display: block;

        color: #334155;

        font-size: 12px;

        line-height: 1.5;

        overflow-wrap: anywhere;

    }



    .medicine-stock-grid {

        display: grid;

        grid-template-columns: repeat(3, minmax(0, 1fr));

        gap: 12px;

        margin-bottom: 17px;

    }



    .stock-box {

        padding: 12px;

        border: 1px solid #e2e8f0;

        border-radius: 9px;

        background: #fff;

    }



    .stock-box-label {

        display: block;

        margin-bottom: 6px;

        color: #718096;

        font-size: 11px;

    }



    .stock-box-value {

        display: block;

        color: #263449;

        font-size: 14px;

        font-weight: 750;

        overflow-wrap: anywhere;

    }



    .stock-box-value.stock-good {

        color: #15803d;

    }



    .stock-box-value.stock-low {

        color: #c2410c;

    }



    .stock-box-value.stock-none {

        color: #b91c1c;

    }



    .dispense-controls {

        display: grid;

        grid-template-columns: minmax(150px, .7fr) minmax(220px, 1.3fr);

        gap: 14px;

        align-items: start;

    }



    .form-field label {

        display: block;

        margin-bottom: 7px;

        color: #475569;

        font-size: 12px;

        font-weight: 700;

    }



    .form-field input,

    .form-field select,

    .form-field textarea {

        width: 100%;

        min-height: 42px;

        padding: 10px 11px;

        border: 1px solid #cbd5e1;

        border-radius: 8px;

        background: #fff;

        color: #172033;

        font: inherit;

        font-size: 13px;

    }



    .form-field input:focus,

    .form-field select:focus,

    .form-field textarea:focus {

        border-color: #3b82f6;

        outline: none;

        box-shadow: 0 0 0 3px rgba(59, 130, 246, .1);

    }



    .form-field input:disabled,

    .form-field select:disabled,

    .form-field textarea:disabled {

        background: #f1f5f9;

        color: #94a3b8;

        cursor: not-allowed;

    }



    .form-field textarea {

        min-height: 76px;

        resize: vertical;

    }



    .form-help {

        margin-top: 6px;

        color: #718096;

        font-size: 11px;

        line-height: 1.5;

    }



    .unavailable-toggle {

        display: flex;

        align-items: flex-start;

        gap: 9px;

        margin-top: 16px;

        color: #b91c1c;

        font-size: 12px;

        font-weight: 700;

        cursor: pointer;

    }



    .unavailable-toggle input {

        width: 16px;

        height: 16px;

        margin-top: 1px;

        accent-color: #dc2626;

    }



    .unavailable-reason {

        display: none;

        margin-top: 13px;

    }



    .unavailable-reason.is-visible {

        display: block;

    }



    .item-validation {

        display: none;

        margin-top: 12px;

        padding: 10px 12px;

        border-radius: 8px;

        background: #fef2f2;

        color: #b91c1c;

        font-size: 12px;

        line-height: 1.5;

    }



    .item-validation.is-visible {

        display: block;

    }



    .prescription-summary {

        display: grid;

        grid-template-columns: repeat(3, minmax(0, 1fr));

        gap: 13px;

        margin-bottom: 20px;

    }



    .summary-box {

        padding: 16px;

        border: 1px solid #e2e8f0;

        border-radius: 10px;

        background: #f8fafc;

    }



    .summary-label {

        display: block;

        margin-bottom: 7px;

        color: #718096;

        font-size: 12px;

    }



    .summary-value {

        color: #172033;

        font-size: 22px;

        font-weight: 800;

    }



    .payment-grid {

        display: grid;

        grid-template-columns: repeat(2, minmax(0, 1fr));

        gap: 16px;

    }



    .completion-notice {

        padding: 13px 15px;

        margin-bottom: 18px;

        border: 1px solid #bfdbfe;

        border-radius: 9px;

        background: #eff6ff;

        color: #1e40af;

        font-size: 12px;

        line-height: 1.6;

    }



    .completion-error {

        display: none;

        padding: 12px 14px;

        margin-bottom: 16px;

        border: 1px solid #fecaca;

        border-radius: 9px;

        background: #fef2f2;

        color: #991b1b;

        font-size: 13px;

        line-height: 1.6;

    }



    .completion-error.is-visible {

        display: block;

    }



    .completion-actions {

        display: flex;

        justify-content: space-between;

        align-items: center;

        flex-wrap: wrap;

        gap: 12px;

        margin-top: 20px;

    }



    .secondary-button,

    .complete-button {

        display: inline-flex;

        justify-content: center;

        align-items: center;

        gap: 8px;

        min-height: 43px;

        padding: 11px 16px;

        border-radius: 8px;

        font-size: 13px;

        font-weight: 750;

        text-decoration: none;

        cursor: pointer;

    }



    .secondary-button {

        border: 1px solid #cbd5e1;

        background: #fff;

        color: #475569;

    }



    .complete-button {

        border: 1px solid #1d4ed8;

        background: #2563eb;

        color: #fff;

    }



    .complete-button:hover {

        background: #1d4ed8;

    }



    .complete-button:disabled {

        opacity: .6;

        cursor: not-allowed;

    }



    .empty-message {

        padding: 25px;

        border: 1px dashed #cbd5e1;

        border-radius: 10px;

        color: #64748b;

        text-align: center;

    }



    @media (max-width: 800px) {

        .patient-details {

            grid-template-columns: repeat(2, minmax(0, 1fr));

        }



        .medicine-stock-grid {

            grid-template-columns: 1fr;

        }

    }



    @media (max-width: 550px) {

        .dispensing-card {

            padding: 15px;

        }



        .dispensing-header h2 {

            font-size: 23px;

        }



        .patient-details,

        .medicine-directions,

        .dispense-controls,

        .prescription-summary,

        .payment-grid {

            grid-template-columns: 1fr;

        }



        .medicine-top {

            flex-direction: column;

        }



        .completion-actions {

            align-items: stretch;

            flex-direction: column-reverse;

        }



        .secondary-button,

        .complete-button {

            width: 100%;

        }

    }

</style>



<div class="dispensing-page">

    <a href="{{ route('pharmacy.queue') }}" class="back-link">

        &larr; Back to Pharmacy Queue

    </a>



    <div class="dispensing-header">

        <div>

            <h2>Dispense Prescription</h2>

            <p>Review the doctor's instructions and record the medicines dispensed.</p>

        </div>



        <span class="prescription-status">

            {{ ucwords(str_replace('\_', ' ', $prescription->status)) }}

        </span>

    </div>



    <div class="dispensing-card">

        <h3>Patient and Prescription Details</h3>



        <div class="patient-details">

            <div>

                <span class="detail-label">Patient Name</span>

                <span class="detail-value">

                    {{ $prescription->customer?->name ?? 'Not recorded' }}

                </span>

            </div>



            <div>

                <span class="detail-label">Patient Number</span>

                <span class="detail-value">

                    {{ $prescription->customer?->patient_number ?? 'Not recorded' }}

                </span>

            </div>



            <div>

                <span class="detail-label">Patient Phone</span>

                <span class="detail-value">

                    {{ $prescription->customer?->phone ?? 'Not recorded' }}

                </span>

            </div>



            <div>

                <span class="detail-label">Prescription Number</span>

                <span class="detail-value">

                    {{ $prescription->prescription_number }}

                </span>

            </div>



            <div>

                <span class="detail-label">Prescriber</span>

                <span class="detail-value">

                    {{ $prescription->prescriber_name ?? 'Not recorded' }}

                </span>

            </div>



            <div>

                <span class="detail-label">Prescription Date</span>

                <span class="detail-value">

                    {{ $prescription->prescription_date?->format('d M Y') ?? 'Not recorded' }}

                </span>

            </div>

        </div>



        @if ($prescription->notes)

            <div class="prescription-notes">

                <strong>Prescription notes</strong>

                <div>{{ $prescription->notes }}</div>

            </div>

        @endif

    </div>



    @php

        $dispensableItems = $prescription->items->filter(

            fn ($item) => $item->remainingQuantity() > 0

        );



        $totalOutstanding = $dispensableItems->sum(

            fn ($item) => $item->remainingQuantity()

        );

    @endphp



    @if ($dispensableItems->isEmpty())

        <div class="dispensing-card">

            <div class="empty-message">

                <h3>Prescription fully dispensed</h3>

                All prescribed quantities have been recorded as dispensed.

                There are no remaining quantities to dispense.

            </div>

        </div>

    @else

        <form

            id="dispensing-form"

            method="POST"

            action="{{ route('sales.store') }}"

        >

            @csrf



            <input type="hidden" name="sale_type" value="hospital_prescription">

            <input type="hidden" name="prescription_id" value="{{ $prescription->id }}">

            <input type="hidden" name="customer_id" value="{{ $prescription->customer_id }}">



            <input

                type="hidden"

                name="customer_name"

                value="{{ $prescription->customer?->name ?? '' }}"

            >



            <input

                type="hidden"

                name="customer_phone"

                value="{{ $prescription->customer?->phone ?? '' }}"

            >



            <input type="hidden" name="discount" value="0">
            <input type="hidden" name="subtotal" id="dispensing-subtotal" value="0">

            <input type="hidden" name="items" id="dispensing-items-payload">



            {{-- New: send unavailable-medicine reasons to the sale endpoint. --}}

            <input

                type="hidden"

                name="unavailable_items"

                id="unavailable-items-payload"

                value="[]"

            >



            <div class="dispensing-card">

                <h3>Medicines to Dispense</h3>



                <p class="dispensing-intro">

                    Check the directions for every medicine. Enter the quantity

                    you are dispensing now. You may dispense part of the

                    remaining quantity and return to this prescription later.

                </p>



                <div class="prescription-summary">

                    <div class="summary-box">

                        <span class="summary-label">Medicine items outstanding</span>

                        <span class="summary-value">

                            {{ $dispensableItems->count() }}

                        </span>

                    </div>



                    <div class="summary-box">

                        <span class="summary-label">Units remaining</span>

                        <span class="summary-value">

                            {{ $totalOutstanding }}

                        </span>

                    </div>



                    <div class="summary-box">

                        <span class="summary-label">Units selected now</span>

                        <span class="summary-value" id="selected-units">0</span>

                    </div>

                </div>



                <div class="medicine-list">

                    @foreach ($dispensableItems as $item)

                        @php

                            $medicine = $item->medicine;



                            $prescribed = (int) $item->quantity_prescribed;

                            $dispensed = (int) $item->quantity_dispensed;

                            $remaining = max(0, $prescribed - $dispensed);



                            $eligibleBatches = $medicine?->batches ?? collect();



                            $eligibleBatches = $eligibleBatches->filter(

                                fn ($batch) =>

                                    $batch->status &&

                                    (int) $batch->quantity > 0 &&

                                    $batch->expiry_date &&

                                    $batch->expiry_date->toDateString() >= now()->toDateString()

                            )->sortBy('expiry_date')->values();



                            $availableStock = (int) $eligibleBatches->sum('quantity');

                            $earliestBatch = $eligibleBatches->first();



                            $maxDispensable = min($remaining, $availableStock);

                        @endphp



                        <section

                            class="medicine-item"

                            data-prescription-item-id="{{ $item->id }}"

                            data-medicine-id="{{ $item->medicine_id }}"

                            data-prescription-id="{{ $prescription->id }}"

                            data-remaining="{{ $remaining }}"

                            data-stock="{{ $availableStock }}"

                            data-price="{{ $earliestBatch ? $earliestBatch->selling_price : 0 }}"

                            data-medicine-name="{{ $medicine?->name ?? 'Medicine unavailable' }}"

                        >

                            <div class="medicine-top">

                                <div>

                                    <h4 class="medicine-name">

                                        {{ $medicine?->name ?? 'Medicine unavailable' }}

                                    </h4>



                                    <div class="medicine-strength">

                                        @if ($medicine?->strength)

                                            {{ $medicine->strength }}

                                        @endif



                                        @if ($medicine?->dosage_form)

                                            &middot; {{ $medicine->dosage_form }}

                                        @endif

                                    </div>

                                </div>



                                <span class="medicine-remaining">

                                    {{ $remaining }} remaining

                                </span>

                            </div>



                            <div class="medicine-directions">

                                <div>

                                    <span class="direction-label">Dosage</span>

                                    <span class="direction-value">

                                        {{ $item->dosage ?: 'Not recorded' }}

                                    </span>

                                </div>



                                <div>

                                    <span class="direction-label">Frequency</span>

                                    <span class="direction-value">

                                        {{ $item->frequency ?: 'Not recorded' }}

                                    </span>

                                </div>



                                <div>

                                    <span class="direction-label">Duration</span>

                                    <span class="direction-value">

                                        {{ $item->duration ?: 'Not recorded' }}

                                    </span>

                                </div>



                                <div>

                                    <span class="direction-label">Route</span>

                                    <span class="direction-value">

                                        {{ $item->route ?: 'Not recorded' }}

                                    </span>

                                </div>



                                <div style="grid-column: 1 / -1;">

                                    <span class="direction-label">Doctor's instructions</span>

                                    <span class="direction-value">

                                        {{ $item->instructions ?: 'No additional instructions recorded.' }}

                                    </span>

                                </div>



                                @if ($item->notes)

                                    <div style="grid-column: 1 / -1;">

                                        <span class="direction-label">Item notes</span>

                                        <span class="direction-value">

                                            {{ $item->notes }}

                                        </span>

                                    </div>

                                @endif

                            </div>



                            <div class="medicine-stock-grid">

                                <div class="stock-box">

                                    <span class="stock-box-label">Prescribed quantity</span>

                                    <span class="stock-box-value">

                                        {{ $prescribed }}

                                    </span>

                                </div>



                                <div class="stock-box">

                                    <span class="stock-box-label">Already dispensed</span>

                                    <span class="stock-box-value">

                                        {{ $dispensed }}

                                    </span>

                                </div>



                                <div class="stock-box">

                                    <span class="stock-box-label">Eligible stock available</span>

                                    <span class="stock-box-value {{ $availableStock >= $remaining ? 'stock-good' : ($availableStock > 0 ? 'stock-low' : 'stock-none') }}">

                                        {{ $availableStock }} units

                                    </span>

                                </div>

                            </div>
                            <div class="medicine-stock-grid medicine-pricing-grid">
                                <div class="stock-box">
                                    <span class="stock-box-label">Unit selling price</span>
                                    <span class="stock-box-value">
                                        @if ($earliestBatch)
                                            KSh {{ number_format((float) $earliestBatch->selling_price, 2) }}
                                        @else
                                            Not available
                                        @endif
                                    </span>
                                </div>
                                <div class="stock-box">
                                    <span class="stock-box-label">Cost for quantity selected</span>
                                    <span class="stock-box-value medicine-line-total" aria-live="polite">KSh 0.00</span>
                                    <div class="form-help">Updates when you change the quantity to dispense.</div>
                                </div>
                            </div>




                            <div class="stock-box" style="margin-bottom: 17px;">

                                <span class="stock-box-label">

                                    FEFO — earliest-expiring eligible batch

                                </span>



                                @if ($earliestBatch)

                                    <span class="stock-box-value">

                                        Batch {{ $earliestBatch->batch_number }}

                                    </span>



                                    <div class="form-help">

                                        Expires

                                        {{ $earliestBatch->expiry_date?->format('d M Y') ?? 'Not recorded' }}.

                                        Stock will be selected using the sale

                                        controller's FEFO rules.

                                    </div>

                                @else

                                    <span class="stock-box-value stock-none">

                                        No eligible stock

                                    </span>



                                    <div class="form-help">

                                        No active, unexpired batch with a positive

                                        quantity is available for this medicine.

                                    </div>

                                @endif

                            </div>



                            <div class="dispense-controls">

                                <div class="form-field">

                                    <label for="quantity-{{ $item->id }}">

                                        Quantity to dispense now

                                    </label>



                                    <input

                                        type="number"

                                        id="quantity-{{ $item->id }}"

                                        class="dispense-quantity"

                                        min="0"

                                        max="{{ $maxDispensable }}"

                                        step="1"

                                        value="0"

                                        {{ !$medicine || $availableStock <= 0 ? 'disabled' : '' }}

                                    >



                                    <div class="form-help">

                                        Maximum now: {{ $maxDispensable }} units.

                                        You can leave this at zero if you are not

                                        dispensing this medicine.

                                    </div>

                                </div>



                                <div class="form-field">

                                    <label for="item-action-{{ $item->id }}">

                                        Medicine availability

                                    </label>



                                    <select

                                        id="item-action-{{ $item->id }}"

                                        class="item-action"

                                    >

                                        <option value="dispense">

                                            Available — dispense selected quantity

                                        </option>



                                        <option value="unavailable">

                                            Mark medicine unavailable

                                        </option>

                                    </select>



                                    <div

                                        class="unavailable-reason"

                                        id="reason-wrap-{{ $item->id }}"

                                    >

                                        <label for="reason-{{ $item->id }}">

                                            Reason medicine is unavailable

                                        </label>



                                        <textarea

                                            id="reason-{{ $item->id }}"

                                            class="unavailable-reason-input"

                                            maxlength="1000"

                                            placeholder="e.g. No stock available or insufficient stock"

                                            disabled

                                        ></textarea>



                                        <div class="form-help">

                                            Record the reason for the pharmacist's

                                            dispensing record.

                                        </div>

                                    </div>

                                </div>

                            </div>



                            <div class="item-validation" role="alert"></div>

                        </section>

                    @endforeach

                </div>

            </div>



            <div class="dispensing-card">

                <h3>Payment and Completion</h3>



                <div class="completion-notice">

                    Only enter quantities that you are dispensing now.

                    The remaining prescription quantities should be retained

                    for a future dispensing. Completing a sale should deduct

                    stock and create the receipt through the existing sales

                    workflow.

                </div>



                                <div class="completion-error" id="completion-error" role="alert"></div>

                <div class="prescription-summary">
                    <div class="summary-box">
                        <span class="summary-label">Subtotal for medicines selected</span>
                        <span class="summary-value" id="dispensing-subtotal-display">KSh 0.00</span>
                    </div>
                    <div class="summary-box">
                        <span class="summary-label">Discount</span>
                        <span class="summary-value">KSh 0.00</span>
                    </div>
                    <div class="summary-box">
                        <span class="summary-label">Total payable</span>
                        <span class="summary-value" id="dispensing-total-display">KSh 0.00</span>
                    </div>
                </div>

                <div class="payment-grid">

                    <div class="form-field">

                        <label for="payment_method">Payment method</label>



                        <select name="payment_method" id="payment_method" required>

                            <option value="cash">Cash</option>

                            <option value="mpesa">M-Pesa</option>

                            <option value="card">Card</option>

                            <option value="split">Split payment</option>

                        </select>

                    </div>



                    <div class="form-field">

                        <label for="amount_paid">Amount paid</label>



                        <input

                            type="number"

                            name="amount_paid"

                            id="amount_paid"

                            min="0"

                            step="0.01"

                            value="0"

                            required

                        >



                        <div class="form-help">

                            Confirm the final total and payment handling

                            with the sale workflow.

                        </div>

                    </div>

                </div>



                <div class="completion-actions">

                    <a

                        href="{{ route('pharmacy.queue') }}"

                        class="secondary-button"

                    >

                        Cancel and return to queue

                    </a>



                    <button

                        type="submit"

                        class="complete-button"

                        id="complete-dispensing"

                    >

                        Complete dispensing

                        <span aria-hidden="true">&rarr;</span>

                    </button>

                </div>

            </div>

        </form>

    @endif

</div>



<script>

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('dispensing-form');



    if (!form) {

        return;

    }



    const itemsPayload = document.getElementById('dispensing-items-payload');

    const unavailableItemsPayload = document.getElementById('unavailable-items-payload');

    const selectedUnits = document.getElementById('selected-units');

    const subtotalInput = document.getElementById('dispensing-subtotal');
    const subtotalDisplay = document.getElementById('dispensing-subtotal-display');
    const totalDisplay = document.getElementById('dispensing-total-display');

    const completionError = document.getElementById('completion-error');

    const paymentMethod = document.getElementById('payment_method');

    const completeButton = document.getElementById('complete-dispensing');



    const medicineCards = Array.from(

        form.querySelectorAll('.medicine-item')

    );



    function showItemError(card, message) {

        const error = card.querySelector('.item-validation');



        error.textContent = message;

        error.classList.toggle('is-visible', Boolean(message));

    }



    function updateCard(card) {

        const action = card.querySelector('.item-action');

        const quantity = card.querySelector('.dispense-quantity');

        const reasonWrap = card.querySelector('.unavailable-reason');

        const reason = card.querySelector('.unavailable-reason-input');



        const isUnavailable = action.value === 'unavailable';



        reasonWrap.classList.toggle('is-visible', isUnavailable);

        reason.required = isUnavailable;



        if (isUnavailable) {

            quantity.value = '0';

            quantity.disabled = true;

            reason.disabled = false;

        } else {

            quantity.disabled = Number(card.dataset.stock) <= 0;

            reason.disabled = true;

            reason.value = '';

        }



        card.classList.toggle('is-unavailable', isUnavailable);

        showItemError(card, '');



        updateTotals();

    }



    function formatMoney(value) {
        return 'KSh ' + Number(value || 0).toLocaleString('en-KE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function updateTotals() {
        let units = 0;
        let subtotal = 0;

        medicineCards.forEach(function (card) {
            const quantity = card.querySelector('.dispense-quantity');
            const action = card.querySelector('.item-action');
            const lineTotal = card.querySelector('.medicine-line-total');
            const selectedQuantity = action.value === 'dispense'
                ? Math.max(0, Number(quantity.value) || 0)
                : 0;
            const unitPrice = Math.max(0, Number(card.dataset.price) || 0);
            const lineAmount = selectedQuantity * unitPrice;

            units += selectedQuantity;
            subtotal += lineAmount;

            if (lineTotal) {
                lineTotal.textContent = formatMoney(lineAmount);
            }
        });

        selectedUnits.textContent = String(units);
        subtotalInput.value = subtotal.toFixed(2);
        subtotalDisplay.textContent = formatMoney(subtotal);
        totalDisplay.textContent = formatMoney(subtotal);
    }

    medicineCards.forEach(function (card) {

        const action = card.querySelector('.item-action');

        const quantity = card.querySelector('.dispense-quantity');



        action.addEventListener('change', function () {

            updateCard(card);

        });



        quantity.addEventListener('input', function () {

            const maximum = Number(quantity.max) || 0;

            const current = Number(quantity.value) || 0;



            if (current > maximum) {

                quantity.setCustomValidity(

                    'The quantity cannot exceed the remaining quantity or available stock.'

                );

            } else if (current < 0) {

                quantity.setCustomValidity(

                    'The quantity cannot be negative.'

                );

            } else {

                quantity.setCustomValidity('');

            }



            showItemError(card, quantity.validationMessage);

            updateTotals();

        });



        updateCard(card);

    });



    paymentMethod.addEventListener('change', function () {

        if (paymentMethod.value === 'split') {

            completionError.textContent =

                'Split payment requires the cash, M-Pesa, and card amounts. Use the existing POS split-payment workflow for this payment method.';

            completionError.classList.add('is-visible');

        } else {

            completionError.classList.remove('is-visible');

            completionError.textContent = '';

        }

    });



    form.addEventListener('submit', function (event) {

        event.preventDefault();



        completionError.classList.remove('is-visible');

        completionError.textContent = '';



        const items = [];

        const unavailableItems = [];

        let hasError = false;



        medicineCards.forEach(function (card) {

            const action = card.querySelector('.item-action');

            const quantityInput = card.querySelector('.dispense-quantity');

            const reasonInput = card.querySelector('.unavailable-reason-input');



            const quantity = Number(quantityInput.value) || 0;

            const remaining = Number(card.dataset.remaining);

            const stock = Number(card.dataset.stock);



            showItemError(card, '');



            if (action.value === 'unavailable') {

                const reason = reasonInput.value.trim();



                if (!reason) {

                    showItemError(

                        card,

                        'Enter a reason before marking this medicine unavailable.'

                    );



                    hasError = true;

                    return;

                }



                unavailableItems.push({

                    prescription_item_id: Number(card.dataset.prescriptionItemId),

                    medicine_id: Number(card.dataset.medicineId),

                    reason: reason

                });



                return;

            }



            if (!Number.isInteger(quantity) || quantity < 0) {

                showItemError(card, 'Enter a valid whole-number quantity.');

                hasError = true;

                return;

            }



            if (quantity > remaining) {

                showItemError(

                    card,

                    'The quantity cannot exceed the prescribed quantity remaining.'

                );



                hasError = true;

                return;

            }



            if (quantity > stock) {

                showItemError(

                    card,

                    'The quantity cannot exceed the currently available stock.'

                );



                hasError = true;

                return;

            }



            if (quantity === 0) {

                return;

            }



            items.push({

                medicine_id: Number(card.dataset.medicineId),

                quantity: quantity,

                selling_price: Number(card.dataset.price) || 0,

                prescription_item_id: Number(card.dataset.prescriptionItemId),

                prescription_id: Number(card.dataset.prescriptionId)

            });

        });



        if (hasError) {

            completionError.textContent =

                'Please correct the highlighted medicine entries before continuing.';

            completionError.classList.add('is-visible');

            return;

        }



        if (items.length === 0) {

            completionError.textContent =

                'Select at least one medicine quantity to dispense. Marking a medicine unavailable does not complete a sale.';

            completionError.classList.add('is-visible');

            return;

        }



        if (paymentMethod.value === 'split') {

            completionError.textContent =

                'Split payment needs separate cash, M-Pesa, and card amounts. Please use the existing POS split-payment workflow.';

            completionError.classList.add('is-visible');

            return;

        }



        itemsPayload.value = JSON.stringify(items);



        /*

         * Submit unavailable-medicine reasons as a real form field.

         * The sale controller must still validate and persist these records.

         */

        unavailableItemsPayload.value = JSON.stringify(unavailableItems);



        completeButton.disabled = true;

        completeButton.textContent = 'Submitting dispensing...';



        /*

         * The existing sale endpoint remains responsible for rechecking

         * stock, prescription quantities, prices, and payment totals.

         */

        form.submit();

    });

});

</script>

@endsection
