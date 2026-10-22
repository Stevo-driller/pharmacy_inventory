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

{{-- CUSTOMER / PATIENT PROFILE --}}
<div class="customer-profile-card">
    <div class="customer-profile-header">
        <div>
            <span class="profile-label">CUSTOMER / PATIENT</span>

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
                href="{{ route('sales.history', ['customer_id' => $sale->customer->id]) }}"
                class="btn btn-secondary"
            >
                View Purchase History
            </a>
        @endif
    </div>
</div>

{{-- SALE INFORMATION --}}
<div class="form-container">
    <div class="form-grid">
        <div>
            <strong>Sale Number</strong>
            <p>{{ $sale->sale_number }}</p>
        </div>

        <div>
            <strong>Date</strong>
            <p>{{ $sale->created_at->format('d M Y H:i:s') }}</p>
        </div>

        <div>
            <strong>Payment Method</strong>
            <p>{{ strtoupper($sale->payment_method) }}</p>
        </div>

        <div>
            <strong>Status</strong>
            <p>{{ ucfirst($sale->status) }}</p>
        </div>

        <div>
            <strong>Sale Type</strong>
            <p>
                @switch($sale->sale_type ?? 'walk_in')
                    @case('hospital_prescription')
                        Hospital Prescription
                        @break

                    @case('external_prescription')
                        External Prescription
                        @break

                    @default
                        Walk-in Customer
                @endswitch
            </p>
        </div>
    </div>
</div>

<br>

{{-- MEDICINES DISPENSED IN THIS SALE --}}
<div class="dispensed-card">
    <div class="section-heading">
        <div>
            <span class="profile-label">COMPLETED SALE</span>
            <h3>Medicines Dispensed</h3>
            <p>These are the medicines recorded against this sale.</p>
        </div>

        <span class="dispensed-badge">
            {{ $sale->items->sum('quantity') }} units
        </span>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Type</th>
                    <th>Batch</th>
                    <th>Quantity Dispensed</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @forelse($sale->items as $item)
                    <tr>
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

                        <td>
                            @if($item->medicine && $item->medicine->prescription_required)
                                <span class="pom-badge">
                                    POM — Prescription
                                </span>
                            @else
                                <span class="otc-badge">
                                    OTC — Non-Prescription
                                </span>
                            @endif
                        </td>

                        <td>
                            {{ $item->medicineBatch->batch_number ?? '-' }}
                        </td>

                        <td>
                            <strong>{{ $item->quantity }}</strong>
                        </td>

                        <td>
                            KSh {{ number_format($item->unit_price, 2) }}
                        </td>

                        <td>
                            <strong>
                                KSh {{ number_format($item->subtotal, 2) }}
                            </strong>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            No medicines were recorded for this sale.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<br>

{{-- PRESCRIPTION ATTACHMENT --}}
@if($sale->prescription && $sale->prescription->prescription_file)
    @php
        $prescriptionPath = $sale->prescription->prescription_file;
        $prescriptionExtension = strtolower(
            pathinfo($prescriptionPath, PATHINFO_EXTENSION)
        );

        $prescriptionUrl = \Illuminate\Support\Facades\Storage::disk('public')
            ->url($prescriptionPath);

        $isPrescriptionImage = in_array(
            $prescriptionExtension,
            ['jpg', 'jpeg', 'png', 'webp']
        );

        $isPrescriptionPdf = $prescriptionExtension === 'pdf';
    @endphp

    <div class="prescription-attachment-card">
        <div class="section-heading">
            <div>
                <span class="profile-label">UPLOADED DOCUMENT</span>
                <h3>External Prescription Attachment</h3>
                <p>
                    The prescription image or document associated with this sale.
                </p>
            </div>
        </div>

        @if($isPrescriptionImage)
            <div class="prescription-preview">
                <a
                    href="{{ $prescriptionUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    title="Open prescription image in a new tab"
                >
                    <img
                        src="{{ $prescriptionUrl }}"
                        alt="Uploaded external prescription"
                        loading="lazy"
                    >
                </a>

                <p>
                    Select the image to open it at full size.
                </p>
            </div>
        @elseif($isPrescriptionPdf)
            <div class="prescription-pdf-preview">
                <iframe
                    src="{{ $prescriptionUrl }}"
                    title="Uploaded external prescription PDF"
                    loading="lazy"
                ></iframe>

                <a
                    href="{{ $prescriptionUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-primary"
                >
                    Open PDF in New Tab
                </a>
            </div>
        @else
            <p class="attachment-notice">
                An attachment is saved, but its file type cannot be previewed here.
            </p>

            <a
                href="{{ $prescriptionUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                class="btn btn-primary"
            >
                Open Prescription Attachment
            </a>
        @endif
    </div>

    <br>
@endif

{{-- PRESCRIPTION DETAILS --}}
@if($sale->prescription)
    <div class="prescription-card">
        <div class="prescription-header">
            <div>
                <span class="profile-label">PRESCRIPTION / DISPENSING</span>
                <h3>💊 Prescription Details</h3>
            </div>

            <span class="pom-badge">POM</span>
        </div>

        <div class="prescription-grid">
            {{-- SHOW THE NUMBER ONLY FOR HOSPITAL PRESCRIPTIONS --}}
            @if(($sale->sale_type ?? null) === 'hospital_prescription')
                <div>
                    <span>Hospital Prescription No.</span>
                    <strong>
                        {{ $sale->prescription->prescription_number ?? '-' }}
                    </strong>
                </div>
            @endif

            <div>
                <span>Prescriber</span>
                <strong>
                    {{ $sale->prescription->prescriber_name ?: '-' }}
                </strong>
            </div>

            <div>
                <span>Prescription Date</span>
                <strong>
                    @if($sale->prescription->prescription_date)
                        {{ \Illuminate\Support\Carbon::parse($sale->prescription->prescription_date)->format('d M Y') }}
                    @else
                        -
                    @endif
                </strong>
            </div>

            <div>
                <span>Patient</span>
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

            <div>
                <span>Prescription Status</span>
                <strong>
                    {{ ucfirst(str_replace('_', ' ', $sale->prescription->status ?? 'pending')) }}
                </strong>
            </div>

            <div>
                <span>Verification</span>
                <strong>
                    @if($sale->prescription->verified)
                        <span class="verified-badge">✓ Verified</span>
                    @else
                        <span class="unverified-badge">Not Verified</span>
                    @endif
                </strong>
            </div>

            <div>
                <span>Verified By</span>
                <strong>
                    {{ $sale->prescription->verified_by ?: '-' }}
                </strong>
            </div>

            <div>
                <span>Verified At</span>
                <strong>
                    @if($sale->prescription->verified_at)
                        {{ \Illuminate\Support\Carbon::parse($sale->prescription->verified_at)->format('d M Y H:i') }}
                    @else
                        -
                    @endif
                </strong>
            </div>
        </div>

        {{-- PRESCRIPTION ITEMS AND DISPENSING TOTALS --}}
        @if($sale->prescription->items->count())
            <div class="prescription-items">
                <h4>Prescription Quantities and Dispensing Progress</h4>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Dosage</th>
                                <th>Frequency</th>
                                <th>Duration</th>
                                <th>Quantity Prescribed</th>
                                <th>Total Dispensed</th>
                                <th>Remaining</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($sale->prescription->items as $prescriptionItem)
                                <tr>
                                    <td>
                                        <strong>
                                            {{ $prescriptionItem->medicine->name ?? 'Unknown Medicine' }}
                                        </strong>

                                        @if($prescriptionItem->medicine && $prescriptionItem->medicine->strength)
                                            <small class="medicine-strength">
                                                {{ $prescriptionItem->medicine->strength }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>{{ $prescriptionItem->dosage ?: '-' }}</td>
                                    <td>{{ $prescriptionItem->frequency ?: '-' }}</td>
                                    <td>{{ $prescriptionItem->duration ?: '-' }}</td>
                                    <td>
                                        {{ $prescriptionItem->quantity_prescribed ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $prescriptionItem->quantity_dispensed ?? 0 }}
                                    </td>
                                    <td>
                                        @if($prescriptionItem->quantity_prescribed !== null)
                                            {{ max(
                                                0,
                                                (int) $prescriptionItem->quantity_prescribed
                                                - (int) ($prescriptionItem->quantity_dispensed ?? 0)
                                            ) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>

                                @if(!empty($prescriptionItem->notes))
                                    <tr>
                                        <td colspan="7">
                                            <div class="prescription-item-notes">
                                                <strong>Notes:</strong>
                                                {{ $prescriptionItem->notes }}
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="dispensing-note">
                    The medicines table above shows quantities sold in this particular sale.
                    The prescription table shows the overall dispensing progress recorded
                    against each prescription item.
                </p>
            </div>
        @else
            <div class="no-prescription-items">
                <p>
                    A prescription is attached to this sale,
                    but no prescription items were recorded.
                </p>
            </div>
        @endif

        @if(!empty($sale->prescription->notes))
            <div class="prescription-notes">
                <span>Prescription Notes</span>
                <p>{{ $sale->prescription->notes }}</p>
            </div>
        @endif
    </div>
@else
    <div class="otc-information">
        <div>
            <span class="otc-badge">NO LINKED PRESCRIPTION</span>

            <h3>
                @if(($sale->sale_type ?? null) === 'external_prescription')
                    External Prescription Sale
                @else
                    Over-the-Counter / Walk-in Sale
                @endif
            </h3>

            <p>
                @if(($sale->sale_type ?? null) === 'external_prescription')
                    No prescription record is linked to this sale.
                    If a document was uploaded, it must be saved and linked to a
                    prescription record before it can appear above.
                @else
                    No prescription record was attached to this sale.
                @endif
            </p>
        </div>
    </div>
@endif

<br>

{{-- SALE TOTALS --}}
<div class="form-container">
    <div style="max-width:400px;margin-left:auto;">
        <p>
            <strong>Subtotal:</strong>
            KSh {{ number_format($sale->subtotal, 2) }}
        </p>

        <p>
            <strong>Discount:</strong>
            KSh {{ number_format($sale->discount, 2) }}
        </p>

        <hr>

        <h2>
            Total: KSh {{ number_format($sale->total_amount, 2) }}
        </h2>

        <p>
            <strong>Amount Paid:</strong>
            KSh {{ number_format($sale->amount_paid, 2) }}
        </p>

        <p>
            <strong>Change:</strong>
            KSh {{ number_format($sale->change_amount, 2) }}
        </p>
    </div>
</div>

{{-- STYLES --}}
<style>
    .customer-profile-card,
    .dispensed-card,
    .prescription-attachment-card,
    .prescription-card,
    .otc-information {
        background: #fff;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .customer-profile-card {
        border-left: 4px solid #1e3a8a;
    }

    .customer-profile-header,
    .section-heading,
    .prescription-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .customer-profile-header h3,
    .section-heading h3,
    .prescription-header h3 {
        margin: 0;
    }

    .customer-profile-header p,
    .section-heading p {
        margin: 6px 0 0;
        color: #666;
    }

    .profile-label {
        display: block;
        color: #1e3a8a;
        font-size: 11px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .medicine-strength {
        display: block;
        color: #777;
        font-size: 12px;
        margin-top: 3px;
    }

    .dispensed-card {
        border-left: 4px solid #16a34a;
    }

    .dispensed-badge {
        display: inline-block;
        background: #dcfce7;
        color: #166534;
        padding: 7px 10px;
        border-radius: 5px;
        font-weight: bold;
        white-space: nowrap;
    }

    .pom-badge,
    .otc-badge {
        display: inline-block;
        padding: 5px 8px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: bold;
    }

    .pom-badge {
        background: #fee2e2;
        color: #991b1b;
    }

    .otc-badge {
        background: #dcfce7;
        color: #166534;
    }

    .prescription-attachment-card {
        border: 1px solid #bfdbfe;
        background: #f8fbff;
    }

    .prescription-preview {
        margin-top: 20px;
        text-align: center;
    }

    .prescription-preview img {
        display: block;
        max-width: 100%;
        max-height: 700px;
        width: auto;
        height: auto;
        margin: 0 auto;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: white;
        object-fit: contain;
    }

    .prescription-preview p {
        color: #6b7280;
        font-size: 13px;
        margin-top: 10px;
    }

    .prescription-pdf-preview {
        margin-top: 20px;
    }

    .prescription-pdf-preview iframe {
        display: block;
        width: 100%;
        height: 650px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: white;
        margin-bottom: 15px;
    }

    .attachment-notice,
    .dispensing-note {
        color: #6b7280;
        font-size: 13px;
        line-height: 1.6;
    }

    .prescription-card {
        background: #fff7ed;
        border: 1px solid #fed7aa;
    }

    .prescription-header {
        margin-bottom: 20px;
    }

    .prescription-header h3 {
        color: #1e3a8a;
    }

    .prescription-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .prescription-grid > div {
        background: white;
        padding: 12px;
        border-radius: 5px;
        border: 1px solid #f1f1f1;
        overflow-wrap: anywhere;
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

    .verified-badge,
    .unverified-badge {
        display: inline-block;
        padding: 4px 7px;
        border-radius: 4px;
        font-size: 11px;
    }

    .verified-badge {
        background: #dcfce7;
        color: #166534;
    }

    .unverified-badge {
        background: #fee2e2;
        color: #991b1b;
    }

    .prescription-items {
        margin-top: 20px;
    }

    .prescription-items h4 {
        margin: 0 0 12px;
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

    .otc-information {
        border-left: 4px solid #16a34a;
    }

    .otc-information h3 {
        margin: 8px 0 5px;
        color: #166534;
    }

    .otc-information p {
        margin: 0;
        color: #777;
    }

    @media (max-width: 1000px) {
        .prescription-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .customer-profile-header,
        .section-heading,
        .prescription-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 650px) {
        .prescription-grid {
            grid-template-columns: 1fr;
        }

        .prescription-pdf-preview iframe {
            height: 450px;
        }
    }
</style>

@endsection