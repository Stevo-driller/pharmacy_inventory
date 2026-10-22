@extends('layouts.app')

@section('content')
<div class="doctor-page">

    <div class="page-header">

        <div>

            <div class="breadcrumb">
                Doctor / Prescriptions / {{ $prescription->prescription_number }}
            </div>

            <h1>
                Prescription {{ $prescription->prescription_number }}
            </h1>

            <p>
                Prescription details and dispensing status.
            </p>

        </div>

        <div class="header-actions">

            <a
                href="{{ route('doctor.dashboard') }}"
                class="btn btn-secondary"
            >
                ← Dashboard
            </a>

            <a
                href="{{ route('doctor.prescriptions.create', ['patient_id' => $prescription->customer_id]) }}"
                class="btn btn-primary"
            >
                + New Prescription
            </a>

        </div>

    </div>


    <div class="status-banner status-{{ str_replace('_', '-', $prescription->status) }}">

        <div>

            <span class="status-label">
                Prescription Status
            </span>

            <strong>
                {{ ucfirst(str_replace('_', ' ', $prescription->status)) }}
            </strong>

        </div>

        @if($prescription->verified)

            <div class="verified-info">
                ✓ Verified
            </div>

        @endif

    </div>


    <div class="details-grid">

        <div class="info-card">

            <div class="card-title">
                Patient Information
            </div>

            <div class="info-row">

                <span>Name</span>

                <strong>
                    {{ $prescription->customer->name ?? 'N/A' }}
                </strong>

            </div>

            <div class="info-row">

                <span>Patient Number</span>

                <strong>
                    {{ $prescription->customer->patient_number ?? 'N/A' }}
                </strong>

            </div>

            <div class="info-row">

                <span>Phone</span>

                <strong>
                    {{ $prescription->customer->phone ?? 'N/A' }}
                </strong>

            </div>

            <div class="info-row">

                <span>Email</span>

                <strong>
                    {{ $prescription->customer->email ?? 'N/A' }}
                </strong>

            </div>

        </div>


        <div class="info-card">

            <div class="card-title">
                Prescription Information
            </div>

            <div class="info-row">

                <span>Prescription Number</span>

                <strong>
                    {{ $prescription->prescription_number }}
                </strong>

            </div>

            <div class="info-row">

                <span>Prescribing Doctor</span>

                <strong>
                    Dr. {{ $prescription->prescriber_name }}
                </strong>

            </div>

            <div class="info-row">

                <span>Date</span>

                <strong>
                    {{ $prescription->prescription_date?->format('d M Y') ?? 'N/A' }}
                </strong>

            </div>

            <div class="info-row">

                <span>Created</span>

                <strong>
                    {{ $prescription->created_at?->format('d M Y H:i') ?? 'N/A' }}
                </strong>

            </div>

        </div>

    </div>


    <div class="info-card medicines-card">

        <div class="card-heading">

            <div>

                <h2>Prescribed Medicines</h2>

                <p>
                    Medicines prescribed for this patient.
                </p>

            </div>

            <span class="medicine-count">
                {{ $prescription->items->count() }}
                {{ Str::plural('medicine', $prescription->items->count()) }}
            </span>

        </div>


        @forelse($prescription->items as $item)

            @php

                $quantityPrescribed =
                    (int) $item->quantity_prescribed;

                $quantityDispensed =
                    (int) ($item->quantity_dispensed ?? 0);

                $remaining =
                    max(
                        0,
                        $quantityPrescribed -
                        $quantityDispensed
                    );

                $percentage =
                    $quantityPrescribed > 0
                        ? min(
                            100,
                            ($quantityDispensed / $quantityPrescribed) * 100
                        )
                        : 0;

            @endphp

            <div class="medicine-item">

                <div class="medicine-item-header">

                    <div>

                        <h3>
                            {{ $item->medicine->name ?? 'Medicine' }}
                        </h3>

                        <div class="medicine-meta">

                            @if($item->medicine?->generic_name)
                                <span>
                                    {{ $item->medicine->generic_name }}
                                </span>
                            @endif

                            @if($item->medicine?->strength)
                                <span>
                                    {{ $item->medicine->strength }}
                                </span>
                            @endif

                            @if($item->medicine?->dosage_form)
                                <span>
                                    {{ $item->medicine->dosage_form }}
                                </span>
                            @endif

                        </div>

                    </div>

                    <div class="quantity-summary">

                        <span>
                            {{ $quantityDispensed }}
                            / {{ $quantityPrescribed }}
                        </span>

                        <small>
                            dispensed
                        </small>

                    </div>

                </div>


                <div class="progress-container">

                    <div
                        class="progress-bar"
                        style="width: {{ $percentage }}%;"
                    ></div>

                </div>


                <div class="quantity-details">

                    <div>
                        <span>Prescribed</span>
                        <strong>
                            {{ $quantityPrescribed }}
                        </strong>
                    </div>

                    <div>
                        <span>Dispensed</span>
                        <strong>
                            {{ $quantityDispensed }}
                        </strong>
                    </div>

                    <div>
                        <span>Remaining</span>
                        <strong>
                            {{ $remaining }}
                        </strong>
                    </div>

                </div>


                <div class="instructions-grid">

                    <div>

                        <span>Dosage</span>

                        <strong>
                            {{ $item->dosage ?? 'Not specified' }}
                        </strong>

                    </div>

                    <div>

                        <span>Frequency</span>

                        <strong>
                            {{ $item->frequency ?? 'Not specified' }}
                        </strong>

                    </div>

                    <div>

                        <span>Duration</span>

                        <strong>
                            {{ $item->duration ?? 'Not specified' }}
                        </strong>

                    </div>

                    <div>

                        <span>Route</span>

                        <strong>
                            {{ $item->route ?? 'Not specified' }}
                        </strong>

                    </div>

                </div>


                @if($item->instructions)

                    <div class="instructions-box">

                        <strong>
                            Instructions:
                        </strong>

                        {{ $item->instructions }}

                    </div>

                @endif

            </div>

        @empty

            <div class="empty-state">
                No medicines have been added to this prescription.
            </div>

        @endforelse

    </div>


    @if($prescription->notes)

        <div class="info-card">

            <div class="card-title">
                Prescription Notes
            </div>

            <div class="notes">
                {{ $prescription->notes }}
            </div>

        </div>

    @endif


    <div class="bottom-actions">

        <a
            href="{{ route('doctor.dashboard') }}"
            class="btn btn-secondary"
        >
            ← Back to Dashboard
        </a>

        <a
            href="{{ route('doctor.prescriptions.create', ['patient_id' => $prescription->customer_id]) }}"
            class="btn btn-primary"
        >
            Create Another Prescription
        </a>

    </div>

</div>


<style>

    .doctor-page {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 22px;
    }

    .breadcrumb {
        color: #6b7280;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .page-header h1 {
        margin: 0;
        color: #111827;
        font-size: 28px;
    }

    .page-header p {
        margin: 6px 0 0;
        color: #6b7280;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 11px 17px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
    }

    .btn-primary {
        background: #2563eb;
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .status-banner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 18px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid;
    }

    .status-label {
        display: block;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .status-pending {
        background: #fffbeb;
        border-color: #fde68a;
        color: #92400e;
    }

    .status-verified {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1e40af;
    }

    .status-partially-dispensed {
        background: #fff7ed;
        border-color: #fed7aa;
        color: #9a3412;
    }

    .status-dispensed {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: #065f46;
    }

    .verified-info {
        font-weight: 700;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .info-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .details-grid .info-card {
        margin-bottom: 0;
    }

    .card-title {
        font-size: 17px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 17px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 11px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row span {
        color: #6b7280;
        font-size: 13px;
    }

    .info-row strong {
        color: #111827;
        text-align: right;
        font-size: 14px;
    }

    .card-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .card-heading h2 {
        margin: 0;
        color: #111827;
        font-size: 20px;
    }

    .card-heading p {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 13px;
    }

    .medicine-count {
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 999px;
        padding: 7px 12px;
        font-size: 13px;
        font-weight: 700;
    }

    .medicine-item {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 15px;
    }

    .medicine-item:last-child {
        margin-bottom: 0;
    }

    .medicine-item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
    }

    .medicine-item-header h3 {
        margin: 0;
        color: #111827;
        font-size: 17px;
    }

    .medicine-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin-top: 7px;
    }

    .medicine-meta span {
        background: #f3f4f6;
        color: #4b5563;
        border-radius: 5px;
        padding: 4px 8px;
        font-size: 12px;
    }

    .quantity-summary {
        text-align: right;
    }

    .quantity-summary span {
        display: block;
        color: #111827;
        font-weight: 700;
        font-size: 17px;
    }

    .quantity-summary small {
        color: #6b7280;
    }

    .progress-container {
        height: 8px;
        background: #f3f4f6;
        border-radius: 999px;
        overflow: hidden;
        margin: 18px 0;
    }

    .progress-bar {
        height: 100%;
        background: #2563eb;
        border-radius: inherit;
    }

    .quantity-details {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 18px;
    }

    .quantity-details div {
        background: #f9fafb;
        border-radius: 7px;
        padding: 10px;
        text-align: center;
    }

    .quantity-details span {
        display: block;
        color: #6b7280;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .quantity-details strong {
        color: #111827;
        font-size: 16px;
    }

    .instructions-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 15px;
    }

    .instructions-grid div {
        background: #f9fafb;
        border-radius: 7px;
        padding: 11px;
    }

    .instructions-grid span {
        display: block;
        color: #6b7280;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .instructions-grid strong {
        color: #374151;
        font-size: 13px;
    }

    .instructions-box {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e3a8a;
        border-radius: 8px;
        padding: 12px;
        font-size: 13px;
    }

    .instructions-box strong {
        margin-right: 5px;
    }

    .notes {
        white-space: pre-line;
        color: #374151;
        line-height: 1.6;
    }

    .empty-state {
        text-align: center;
        padding: 35px 15px;
        color: #6b7280;
        border: 1px dashed #d1d5db;
        border-radius: 10px;
        background: #f9fafb;
    }

    .bottom-actions {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 10px;
        padding-bottom: 20px;
    }

    @media (max-width: 900px) {

        .details-grid {
            grid-template-columns: 1fr;
        }

        .instructions-grid {
            grid-template-columns: repeat(2, 1fr);
        }

    }

    @media (max-width: 650px) {

        .doctor-page {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            flex: 1;
        }

        .status-banner {
            align-items: flex-start;
            flex-direction: column;
        }

        .info-row {
            align-items: flex-start;
            flex-direction: column;
            gap: 4px;
        }

        .info-row strong {
            text-align: left;
        }

        .medicine-item-header {
            flex-direction: column;
        }

        .quantity-summary {
            text-align: left;
        }

        .quantity-details {
            grid-template-columns: 1fr;
        }

        .instructions-grid {
            grid-template-columns: 1fr;
        }

        .bottom-actions {
            flex-direction: column;
        }

        .bottom-actions .btn {
            width: 100%;
        }

    }

</style>
@endsection