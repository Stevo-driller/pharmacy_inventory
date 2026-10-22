@extends('layouts.app')

@section('content')

<style>
    .doctor-dashboard {
        padding: 24px;
    }

    .doctor-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .doctor-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
    }

    .doctor-header p {
        margin: 6px 0 0;
        color: #6b7280;
    }

    .doctor-header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .doctor-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }

    .doctor-btn-primary {
        background: #2563eb;
        color: white;
    }

    .doctor-btn-primary:hover {
        background: #1d4ed8;
        color: white;
    }

    .doctor-btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .doctor-btn-secondary:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .doctor-stats {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .doctor-stat-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px;
    }

    .doctor-stat-label {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .doctor-stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
    }

    .doctor-stat-description {
        margin-top: 5px;
        font-size: 12px;
        color: #9ca3af;
    }

    .doctor-search-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .doctor-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
    }

    .doctor-card h2 {
        margin: 0 0 6px;
        font-size: 18px;
        font-weight: 700;
    }

    .doctor-card-description {
        margin: 0 0 16px;
        color: #6b7280;
        font-size: 14px;
    }

    .doctor-search-row {
        display: flex;
        gap: 10px;
    }

    .doctor-search-input {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        font-size: 14px;
    }

    .doctor-search-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .search-results {
        margin-top: 12px;
    }

    .search-result-item {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 8px;
        background: #fafafa;
    }

    .search-result-item:last-child {
        margin-bottom: 0;
    }

    .search-result-main {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .search-result-title {
        font-weight: 700;
        color: #111827;
    }

    .search-result-meta {
        margin-top: 4px;
        color: #6b7280;
        font-size: 13px;
    }

    .search-result-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .small-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 10px;
        border-radius: 6px;
        border: none;
        text-decoration: none;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
    }

    .small-btn-primary {
        background: #2563eb;
        color: white;
    }

    .small-btn-primary:hover {
        background: #1d4ed8;
        color: white;
    }

    .small-btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }

    .small-btn-secondary:hover {
        background: #d1d5db;
        color: #111827;
    }

    .empty-search {
        padding: 14px;
        text-align: center;
        color: #6b7280;
        font-size: 14px;
        border: 1px dashed #d1d5db;
        border-radius: 8px;
    }

    .doctor-content-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 20px;
    }

    .doctor-table-wrapper {
        overflow-x: auto;
    }

    .doctor-table {
        width: 100%;
        border-collapse: collapse;
    }

    .doctor-table th,
    .doctor-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
    }

    .doctor-table th {
        color: #6b7280;
        font-weight: 600;
        white-space: nowrap;
    }

    .doctor-table td {
        color: #374151;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-verified {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-partially-dispensed {
        background: #ede9fe;
        color: #6d28d9;
    }

    .status-dispensed {
        background: #dcfce7;
        color: #166534;
    }

    .patient-card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 14px;
        margin-bottom: 10px;
    }

    .patient-card:last-child {
        margin-bottom: 0;
    }

    .patient-name {
        font-weight: 700;
        color: #111827;
    }

    .patient-number {
        color: #2563eb;
        font-size: 13px;
        margin-top: 3px;
    }

    .patient-phone {
        color: #6b7280;
        font-size: 13px;
        margin-top: 3px;
    }

    .patient-actions {
        margin-top: 10px;
    }

    .loading-text {
        color: #6b7280;
        font-size: 14px;
        padding: 10px 0;
    }

    @media (max-width: 1200px) {
        .doctor-stats {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .doctor-content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 800px) {
        .doctor-search-grid {
            grid-template-columns: 1fr;
        }

        .doctor-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 500px) {
        .doctor-dashboard {
            padding: 14px;
        }

        .doctor-stats {
            grid-template-columns: 1fr;
        }

        .doctor-search-row {
            flex-direction: column;
        }

        .search-result-main {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="doctor-dashboard">

    {{-- Header --}}
    <div class="doctor-header">

        <div>
            <h1>
                Welcome, Dr. {{ auth()->user()->name }}
            </h1>

            <p>
                Manage your patients and prescriptions from here.
            </p>
        </div>

        <div class="doctor-header-actions">

            <a
                href="{{ route('doctor.prescriptions.create') }}"
                class="doctor-btn doctor-btn-primary"
            >
                + Create Prescription
            </a>

        </div>

    </div>


    {{-- Statistics --}}
    <div class="doctor-stats">

        <div class="doctor-stat-card">

            <div class="doctor-stat-label">
                Today's Patients
            </div>

            <div class="doctor-stat-value">
                {{ $todayPatients }}
            </div>

            <div class="doctor-stat-description">
                Unique patients today
            </div>

        </div>


        <div class="doctor-stat-card">

            <div class="doctor-stat-label">
                Today's Prescriptions
            </div>

            <div class="doctor-stat-value">
                {{ $todayPrescriptions }}
            </div>

            <div class="doctor-stat-description">
                Prescriptions created today
            </div>

        </div>


        <div class="doctor-stat-card">

            <div class="doctor-stat-label">
                Pending
            </div>

            <div class="doctor-stat-value">
                {{ $pendingCount }}
            </div>

            <div class="doctor-stat-description">
                Awaiting pharmacy verification
            </div>

        </div>


        <div class="doctor-stat-card">

            <div class="doctor-stat-label">
                Partially Dispensed
            </div>

            <div class="doctor-stat-value">
                {{ $partiallyDispensedCount }}
            </div>

            <div class="doctor-stat-description">
                Prescriptions with remaining medicine
            </div>

        </div>


        <div class="doctor-stat-card">

            <div class="doctor-stat-label">
                Total Patients
            </div>

            <div class="doctor-stat-value">
                {{ $totalPatients }}
            </div>

            <div class="doctor-stat-description">
                Patients seen by you
            </div>

        </div>

    </div>


    {{-- Search Section --}}
    <div class="doctor-search-grid">

        {{-- Patient Search --}}
        <div class="doctor-card">

            <h2>
                Search Patient
            </h2>

            <p class="doctor-card-description">
                Search using patient number, name or phone number.
            </p>

            <div class="doctor-search-row">

                <input
                    type="text"
                    id="patientSearch"
                    class="doctor-search-input"
                    placeholder="Search patient..."
                    autocomplete="off"
                >

            </div>

            <div
                id="patientSearchResults"
                class="search-results"
            ></div>

        </div>


        {{-- Prescription Search --}}
        <div class="doctor-card">

            <h2>
                Search Prescription
            </h2>

            <p class="doctor-card-description">
                Search using prescription number, patient number,
                patient name or phone number.
            </p>

            <div class="doctor-search-row">

                <input
                    type="text"
                    id="prescriptionSearch"
                    class="doctor-search-input"
                    placeholder="e.g. RX-2026-000001"
                    autocomplete="off"
                >

            </div>

            <div
                id="prescriptionSearchResults"
                class="search-results"
            ></div>

        </div>

    </div>


    {{-- Recent Data --}}
    <div class="doctor-content-grid">

        {{-- Recent Prescriptions --}}
        <div class="doctor-card">

            <h2>
                Recent Prescriptions
            </h2>

            <p class="doctor-card-description">
                Your latest prescriptions.
            </p>

            <div class="doctor-table-wrapper">

                @if ($recentPrescriptions->count())

                    <table class="doctor-table">

                        <thead>
                            <tr>
                                <th>
                                    Prescription
                                </th>

                                <th>
                                    Patient
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Medicines
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($recentPrescriptions as $prescription)

                                <tr>

                                    <td>
                                        <strong>
                                            {{ $prescription->prescription_number }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $prescription->customer->name ?? 'Unknown Patient' }}

                                        @if ($prescription->customer)
                                            <br>

                                            <small>
                                                {{ $prescription->customer->patient_number }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                        {{ optional($prescription->prescription_date)->format('d M Y') }}
                                    </td>

                                    <td>
                                        {{ $prescription->items->count() }}
                                    </td>

                                    <td>

                                        <span
                                            class="status-badge status-{{ str_replace('_', '-', $prescription->status) }}"
                                        >
                                            {{ str_replace('_', ' ', $prescription->status) }}
                                        </span>

                                    </td>

                                    <td>

                                        <a
                                            href="{{ route('doctor.prescriptions.show', $prescription) }}"
                                            class="small-btn small-btn-primary"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                @else

                    <div class="empty-search">
                        You have not created any prescriptions yet.
                    </div>

                @endif

            </div>

        </div>


        {{-- Recent Patients --}}
        <div class="doctor-card">

            <h2>
                Recent Patients
            </h2>

            <p class="doctor-card-description">
                Patients you have recently prescribed for.
            </p>

            @if ($recentPatients->count())

                @foreach ($recentPatients as $patient)

                    <div class="patient-card">

                        <div class="patient-name">
                            {{ $patient->name }}
                        </div>

                        <div class="patient-number">
                            {{ $patient->patient_number }}
                        </div>

                        @if ($patient->phone)

                            <div class="patient-phone">
                                {{ $patient->phone }}
                            </div>

                        @endif

                        <div class="patient-actions">

                            <a
                                href="{{ route('doctor.prescriptions.create', ['patient_id' => $patient->id]) }}"
                                class="small-btn small-btn-primary"
                            >
                                Create Prescription
                            </a>

                        </div>

                    </div>

                @endforeach

            @else

                <div class="empty-search">
                    No patients found yet.
                </div>

            @endif

        </div>

    </div>

</div>


<script>
    const patientSearchInput =
        document.getElementById('patientSearch');

    const patientSearchResults =
        document.getElementById('patientSearchResults');

    const prescriptionSearchInput =
        document.getElementById('prescriptionSearch');

    const prescriptionSearchResults =
        document.getElementById('prescriptionSearchResults');


    /*
    |--------------------------------------------------------------------------
    | Patient Search
    |--------------------------------------------------------------------------
    */

    let patientSearchTimeout = null;

    patientSearchInput.addEventListener(
        'input',
        function () {

            clearTimeout(
                patientSearchTimeout
            );

            const search =
                this.value.trim();

            patientSearchResults.innerHTML = '';

            if (search.length < 2) {
                return;
            }

            patientSearchResults.innerHTML =
                '<div class="loading-text">Searching patients...</div>';


            patientSearchTimeout = setTimeout(
                async function () {

                    try {

                        const url =
                            "{{ route('doctor.patients.search') }}" +
                            "?search=" +
                            encodeURIComponent(search);

                        const response =
                            await fetch(url, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });

                        if (!response.ok) {
                            throw new Error(
                                'Unable to search patients.'
                            );
                        }

                        const data =
                            await response.json();

                        patientSearchResults.innerHTML = '';


                        if (
                            !data.patients ||
                            data.patients.length === 0
                        ) {

                            patientSearchResults.innerHTML =
                                '<div class="empty-search">No patients found.</div>';

                            return;
                        }


                        data.patients.forEach(
                            function (patient) {

                                const item =
                                    document.createElement('div');

                                item.className =
                                    'search-result-item';


                                const createUrl =
                                    "{{ route('doctor.prescriptions.create') }}" +
                                    "?patient_id=" +
                                    patient.id;


                                item.innerHTML = `
                                    <div class="search-result-main">

                                        <div>

                                            <div class="search-result-title">
                                                ${escapeHtml(patient.name)}
                                            </div>

                                            <div class="search-result-meta">
                                                Patient Number:
                                                ${escapeHtml(patient.patient_number ?? 'N/A')}
                                                ${patient.phone
                                                    ? ' • ' + escapeHtml(patient.phone)
                                                    : ''
                                                }
                                            </div>

                                        </div>

                                        <div class="search-result-actions">

                                            <a
                                                href="${createUrl}"
                                                class="small-btn small-btn-primary"
                                            >
                                                Create Prescription
                                            </a>

                                        </div>

                                    </div>
                                `;

                                patientSearchResults.appendChild(
                                    item
                                );

                            }
                        );

                    } catch (error) {

                        patientSearchResults.innerHTML =
                            '<div class="empty-search">Unable to search patients.</div>';

                    }

                },
                300
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Prescription Search
    |--------------------------------------------------------------------------
    */

    let prescriptionSearchTimeout = null;

    prescriptionSearchInput.addEventListener(
        'input',
        function () {

            clearTimeout(
                prescriptionSearchTimeout
            );

            const search =
                this.value.trim();

            prescriptionSearchResults.innerHTML = '';

            if (search.length < 2) {
                return;
            }

            prescriptionSearchResults.innerHTML =
                '<div class="loading-text">Searching prescriptions...</div>';


            prescriptionSearchTimeout = setTimeout(
                async function () {

                    try {

                        const url =
                            "{{ route('doctor.prescriptions.search') }}" +
                            "?search=" +
                            encodeURIComponent(search);

                        const response =
                            await fetch(url, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });

                        if (!response.ok) {
                            throw new Error(
                                'Unable to search prescriptions.'
                            );
                        }

                        const data =
                            await response.json();

                        prescriptionSearchResults.innerHTML = '';


                        if (
                            !data.prescriptions ||
                            data.prescriptions.length === 0
                        ) {

                            prescriptionSearchResults.innerHTML =
                                '<div class="empty-search">No prescriptions found.</div>';

                            return;
                        }


                        data.prescriptions.forEach(
                            function (prescription) {

                                const item =
                                    document.createElement('div');

                                item.className =
                                    'search-result-item';


                                const viewUrl =
                                    "{{ url('/doctor/prescriptions') }}" +
                                    "/" +
                                    prescription.id;


                                const patientName =
                                    prescription.customer
                                        ? prescription.customer.name
                                        : 'Unknown Patient';


                                const patientNumber =
                                    prescription.customer
                                        ? prescription.customer.patient_number
                                        : 'N/A';


                                item.innerHTML = `
                                    <div class="search-result-main">

                                        <div>

                                            <div class="search-result-title">
                                                ${escapeHtml(
                                                    prescription.prescription_number
                                                )}
                                            </div>

                                            <div class="search-result-meta">
                                                Patient:
                                                ${escapeHtml(patientName)}
                                                •
                                                ${escapeHtml(patientNumber)}
                                                •
                                                ${escapeHtml(
                                                    prescription.status
                                                        .replaceAll('_', ' ')
                                                )}
                                            </div>

                                        </div>

                                        <div class="search-result-actions">

                                            <a
                                                href="${viewUrl}"
                                                class="small-btn small-btn-primary"
                                            >
                                                View
                                            </a>

                                        </div>

                                    </div>
                                `;

                                prescriptionSearchResults.appendChild(
                                    item
                                );

                            }
                        );

                    } catch (error) {

                        prescriptionSearchResults.innerHTML =
                            '<div class="empty-search">Unable to search prescriptions.</div>';

                    }

                },
                300
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Basic HTML Escaping
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }
</script>

@endsection