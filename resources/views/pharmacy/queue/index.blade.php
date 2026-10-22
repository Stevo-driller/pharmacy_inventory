@extends('layouts.app')

@section('title', 'Hospital Prescription')

@section('content')
<style>
    .hospital-queue,
    .hospital-queue * {
        box-sizing: border-box;
    }

    .hospital-queue {
        width: 100%;
        color: #172033;
    }

    .hospital-queue .queue-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 26px;
    }

    .hospital-queue .queue-header h2 {
        margin: 0;
        font-size: 27px;
        font-weight: 750;
        letter-spacing: -0.7px;
        color: #172033;
    }

    .hospital-queue .queue-subtitle {
        margin: 7px 0 0;
        color: #718096;
        font-size: 14px;
    }

    .hospital-queue .queue-count {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border: 1px solid #dbeafe;
        border-radius: 10px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .hospital-queue .queue-search {
        margin-bottom: 30px;
        padding: 22px;
        background: #ffffff;
        border: 1px solid #e5eaf1;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.035);
    }

    .hospital-queue .search-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
    }

    .hospital-queue .search-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        flex-shrink: 0;
        border-radius: 10px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 18px;
    }

    .hospital-queue .search-heading h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 750;
    }

    .hospital-queue .search-heading p {
        margin: 3px 0 0;
        color: #718096;
        font-size: 12px;
    }

    .hospital-queue .search-fields {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 13px;
    }

    .hospital-queue .search-field label {
        display: block;
        margin-bottom: 7px;
        color: #475569;
        font-size: 12px;
        font-weight: 650;
    }

    .hospital-queue .search-field input {
        display: block;
        width: 100%;
        min-width: 0;
        height: 43px;
        padding: 0 12px;
        border: 1px solid #d9e1ec;
        border-radius: 8px;
        background: #ffffff;
        color: #172033;
        font-size: 13px;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .hospital-queue .search-field input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .hospital-queue .search-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 17px;
    }

    .hospital-queue .search-button,
    .hospital-queue .clear-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 9px 15px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s;
    }

    .hospital-queue .search-button {
        border: 1px solid #1d4ed8;
        background: #2563eb;
        color: #ffffff;
        cursor: pointer;
    }

    .hospital-queue .search-button:hover {
        background: #1d4ed8;
    }

    .hospital-queue .clear-button {
        border: 1px solid #d9e1ec;
        background: #ffffff;
        color: #475569;
    }

    .hospital-queue .clear-button:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .hospital-queue .section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 15px;
    }

    .hospital-queue .section-heading h3 {
        margin: 0;
        color: #172033;
        font-size: 19px;
        font-weight: 750;
    }

    .hospital-queue .section-heading p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 13px;
    }

    .hospital-queue .patient-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .hospital-queue .patient-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        padding: 21px;
        background: #ffffff;
        border: 1px solid #e3e9f2;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.035);
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .hospital-queue .patient-card:hover {
        border-color: #bfdbfe;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
    }

    .hospital-queue .patient-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 17px;
    }

    .hospital-queue .patient-identity {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .hospital-queue .patient-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        border-radius: 12px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 17px;
        font-weight: 800;
    }

    .hospital-queue .patient-name {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 750;
        overflow-wrap: anywhere;
    }

    .hospital-queue .patient-number {
        margin-top: 5px;
        color: #718096;
        font-size: 12px;
        overflow-wrap: anywhere;
    }

    .hospital-queue .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
        padding: 7px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 750;
        white-space: nowrap;
    }

    .hospital-queue .status-verified {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .hospital-queue .status-partial {
        background: #fff7ed;
        color: #c2410c;
    }

    .hospital-queue .prescription-number {
        padding: 12px 13px;
        margin-bottom: 14px;
        border: 1px solid #edf0f5;
        border-radius: 9px;
        background: #f8fafc;
    }

    .hospital-queue .prescription-number-label {
        display: block;
        margin-bottom: 4px;
        color: #718096;
        font-size: 11px;
        font-weight: 650;
        text-transform: uppercase;
        letter-spacing: 0.35px;
    }

    .hospital-queue .prescription-number strong {
        color: #263449;
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .hospital-queue .patient-meta {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 12px;
        margin-bottom: 16px;
    }

    .hospital-queue .meta-label {
        display: block;
        margin-bottom: 5px;
        color: #718096;
        font-size: 11px;
    }

    .hospital-queue .meta-value {
        display: block;
        color: #334155;
        font-size: 12px;
        font-weight: 650;
        overflow-wrap: anywhere;
    }

    .hospital-queue .medicine-summary {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px;
        margin-bottom: 18px;
        border: 1px solid #dbeafe;
        border-radius: 9px;
        background: #f5f9ff;
    }

    .hospital-queue .medicine-summary-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 31px;
        height: 31px;
        flex-shrink: 0;
        border-radius: 8px;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 15px;
    }

    .hospital-queue .medicine-summary-content {
        min-width: 0;
    }

    .hospital-queue .medicine-summary-title {
        margin: 0;
        color: #1e3a8a;
        font-size: 13px;
        font-weight: 750;
    }

    .hospital-queue .medicine-summary-text {
        margin: 5px 0 0;
        color: #475569;
        font-size: 12px;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .hospital-queue .card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding-top: 15px;
        margin-top: auto;
        border-top: 1px solid #edf0f5;
    }

    .hospital-queue .prescriber {
        min-width: 0;
        color: #718096;
        font-size: 11px;
        line-height: 1.5;
    }

    .hospital-queue .prescriber strong {
        display: block;
        color: #475569;
        font-size: 12px;
        overflow-wrap: anywhere;
    }

    .hospital-queue .open-prescription {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 41px;
        padding: 10px 13px;
        border: 1px solid #1d4ed8;
        border-radius: 8px;
        background: #2563eb;
        color: #ffffff;
        font-size: 12px;
        font-weight: 750;
        text-decoration: none;
        white-space: nowrap;
        transition: background 0.15s;
    }

    .hospital-queue .open-prescription:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    .hospital-queue .empty-state {
        padding: 48px 20px;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        background: #ffffff;
        text-align: center;
    }

    .hospital-queue .empty-state-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 56px;
        height: 56px;
        margin-bottom: 15px;
        border-radius: 16px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 24px;
    }

    .hospital-queue .empty-state h4 {
        margin: 0 0 8px;
        color: #172033;
        font-size: 17px;
        font-weight: 750;
    }

    .hospital-queue .empty-state p {
        max-width: 430px;
        margin: 0 auto;
        color: #718096;
        font-size: 13px;
        line-height: 1.6;
    }

    .hospital-queue .pagination-wrapper {
        margin-top: 24px;
    }

    .hospital-queue .pagination-wrapper nav {
        display: flex;
        justify-content: center;
    }

    .hospital-queue .pagination-wrapper p {
        color: #718096;
        font-size: 12px;
    }

    @media (max-width: 1000px) {
        .hospital-queue .patient-list {
            grid-template-columns: 1fr;
        }

        .hospital-queue .search-fields {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .hospital-queue .queue-header {
            flex-direction: column;
        }

        .hospital-queue .queue-header h2 {
            font-size: 23px;
        }

        .hospital-queue .queue-search {
            padding: 16px;
        }

        .hospital-queue .patient-card {
            padding: 16px;
        }

        .hospital-queue .patient-card-top {
            flex-direction: column;
        }

        .hospital-queue .card-footer {
            align-items: stretch;
            flex-direction: column;
        }

        .hospital-queue .open-prescription {
            width: 100%;
        }

        .hospital-queue .search-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .hospital-queue .search-button,
        .hospital-queue .clear-button {
            width: 100%;
        }
    }
</style>

<div class="hospital-queue">

    <div class="queue-header">
        <div>
            <h2>Hospital Prescription</h2>

            <p class="queue-subtitle">
                Prescriptions received from the doctors
            </p>
        </div>

        <div class="queue-count">
            <span aria-hidden="true">&#128203;</span>
            {{ $prescriptions->total() }}
            waiting prescription(s)
        </div>
    </div>

    <div class="queue-search">
        <div class="search-heading">
            <div class="search-icon" aria-hidden="true">
                &#128269;
            </div>

            <div>
                <h3>Search for a patient</h3>

                <p>
                    Find a prescription using the patient's details.
                </p>
            </div>
        </div>

        <form method="GET" action="{{ route('pharmacy.queue') }}">
            <div class="search-fields">
                <div class="search-field">
                    <label for="patient_name">
                        Patient name
                    </label>

                    <input
                        type="text"
                        id="patient_name"
                        name="patient_name"
                        value="{{ $filters['patient_name'] ?? '' }}"
                        placeholder="Enter patient name"
                    >
                </div>

                <div class="search-field">
                    <label for="patient_number">
                        Patient number
                    </label>

                    <input
                        type="text"
                        id="patient_number"
                        name="patient_number"
                        value="{{ $filters['patient_number'] ?? '' }}"
                        placeholder="e.g. HSP-2026-FY8MGJ"
                    >
                </div>

                <div class="search-field">
                    <label for="prescription_number">
                        Prescription number
                    </label>

                    <input
                        type="text"
                        id="prescription_number"
                        name="prescription_number"
                        value="{{ $filters['prescription_number'] ?? '' }}"
                        placeholder="e.g. RX-2026-000008"
                    >
                </div>
            </div>

            <div class="search-actions">
                <button type="submit" class="search-button">
                    <span aria-hidden="true">&#128269;</span>
                    Search prescriptions
                </button>

                <a
                    href="{{ route('pharmacy.queue') }}"
                    class="clear-button"
                >
                    Clear search
                </a>
            </div>
        </form>
    </div>

    <div class="section-heading">
        <div>
            <h3>Patients waiting for medicine</h3>

            <p>
                Open a prescription to review instructions and dispense
                the outstanding medicines.
            </p>
        </div>
    </div>

    @if ($prescriptions->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon" aria-hidden="true">
                &#128203;
            </div>

            <h4>No prescriptions found</h4>

            <p>
                There are no verified prescriptions with outstanding
                medicines matching your search. Try another search or
                clear the filters.
            </p>
        </div>
    @else
        <div class="patient-list">
            @foreach ($prescriptions as $prescription)
                @php
                    $customer = $prescription->customer;

                    $outstandingItems = $prescription->items->filter(
                        fn ($item) => $item->remainingQuantity() > 0
                    );

                    $outstandingCount = $outstandingItems->count();

                    $firstOutstandingItem = $outstandingItems->first();

                    $firstMedicine = $firstOutstandingItem?->medicine;

                    $firstMedicineName = $firstMedicine?->name
                        ?? 'Medicine unavailable';

                    $firstMedicineStrength = $firstMedicine?->strength;

                    $firstRemaining = $firstOutstandingItem
                        ? $firstOutstandingItem->remainingQuantity()
                        : 0;

                    $patientName = $customer?->name
                        ?? 'Patient details unavailable';

                    $nameParts = preg_split(
                        '/\s+/',
                        trim($patientName)
                    );

                    $patientInitials = collect($nameParts)
                        ->filter()
                        ->take(2)
                        ->map(fn ($part) => mb_strtoupper(
                            mb_substr($part, 0, 1)
                        ))
                        ->implode('');

                    $statusLabel = $prescription->status === 'partially_dispensed'
                        ? 'Partially dispensed'
                        : 'Awaiting dispensing';
                @endphp

                <article class="patient-card">
                    <div class="patient-card-top">
                        <div class="patient-identity">
                            <div class="patient-avatar">
                                {{ $patientInitials ?: 'P' }}
                            </div>

                            <div>
                                <h4 class="patient-name">
                                    {{ $patientName }}
                                </h4>

                                <div class="patient-number">
                                    {{ $customer?->patient_number
                                        ?: 'Patient number not recorded' }}
                                </div>
                            </div>
                        </div>

                        <span class="status-badge {{ $prescription->status === 'partially_dispensed' ? 'status-partial' : 'status-verified' }}">
                            <span aria-hidden="true">&#9679;</span>
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="prescription-number">
                        <span class="prescription-number-label">
                            Prescription number
                        </span>

                        <strong>
                            {{ $prescription->prescription_number }}
                        </strong>
                    </div>

                    <div class="patient-meta">
                        <div>
                            <span class="meta-label">
                                Prescription date
                            </span>

                            <span class="meta-value">
                                {{ $prescription->prescription_date
                                    ? $prescription->prescription_date->format('d M Y')
                                    : 'Not recorded' }}
                            </span>
                        </div>

                        <div>
                            <span class="meta-label">
                                Medicines outstanding
                            </span>

                            <span class="meta-value">
                                {{ $outstandingCount }}
                                {{ $outstandingCount === 1 ? 'medicine' : 'medicines' }}
                            </span>
                        </div>
                    </div>

                    <div class="medicine-summary">
                        <div class="medicine-summary-icon" aria-hidden="true">
                            &#128138;
                        </div>

                        <div class="medicine-summary-content">
                            @if ($outstandingCount === 1)
                                <p class="medicine-summary-title">
                                    1 medicine remaining
                                </p>

                                <p class="medicine-summary-text">
                                    {{ $firstMedicineName }}

                                    @if ($firstMedicineStrength)
                                        {{ $firstMedicineStrength }}
                                    @endif

                                    &middot;
                                    {{ $firstRemaining }} remaining
                                </p>
                            @elseif ($outstandingCount > 1)
                                <p class="medicine-summary-title">
                                    {{ $outstandingCount }} medicines outstanding
                                </p>

                                <p class="medicine-summary-text">
                                    {{ $outstandingCount }} medicine items
                                    still need dispensing. Open the
                                    prescription to review each medicine
                                    and its remaining quantity.
                                </p>
                            @else
                                <p class="medicine-summary-title">
                                    No outstanding medicines
                                </p>

                                <p class="medicine-summary-text">
                                    This prescription has no medicine
                                    quantities remaining to dispense.
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="prescriber">
                            Prescribed by

                            <strong>
                                {{ $prescription->prescriber_name ?: 'Not recorded' }}
                            </strong>
                        </div>

                        <a
                            href="{{ route('pharmacy.queue.show', $prescription) }}"
                            class="open-prescription"
                        >
                            Open prescription
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $prescriptions->links() }}
        </div>
    @endif

</div>
@endsection