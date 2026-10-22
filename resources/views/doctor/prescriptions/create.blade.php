@extends('layouts.app')

@section('content')

<div class="doctor-prescription-page">

    <div class="prescription-container">

        <!-- Page Header -->
        <div class="page-header">

            <div>
                <h1>Create Prescription</h1>

                <p>
                    Create a hospital prescription for a patient.
                </p>
            </div>

            <a
                href="{{ route('doctor.dashboard') }}"
                class="btn-secondary"
            >
                ← Back to Dashboard
            </a>

        </div>


        <!-- Alerts -->
        <div id="alertContainer"></div>


        <!-- Patient Section -->
        <section class="form-card">

            <div class="section-header">

                <div>
                    <h2>Search Patient</h2>

                    <p>
                        Search for an existing patient or register a new patient.
                    </p>
                </div>

            </div>


            <div class="patient-search-layout">

                <div class="form-group">

                    <label for="patientSearch">
                        Search Existing Patient
                    </label>

                    <input
                        type="text"
                        id="patientSearch"
                        class="form-input"
                        placeholder="Search by patient number, name or phone..."
                        autocomplete="off"
                    >

                    <div
                        id="patientSearchResults"
                        class="search-results"
                        style="display: none;"
                    ></div>

                </div>


                <div class="register-patient-button-wrapper">

                    <button
                        type="button"
                        class="btn-primary"
                        onclick="openRegisterPatientModal()"
                    >
                        + Register New Patient
                    </button>

                </div>

            </div>


            <input
                type="hidden"
                id="customerId"
                value="{{ $selectedPatient?->id ?? '' }}"
            >


            <!-- Selected Patient -->
            <div
                id="selectedPatientCard"
                class="selected-patient"
                style="{{ $selectedPatient ? '' : 'display: none;' }}"
            >

                <div class="selected-patient-info">

                    <div class="selected-patient-title">
                        Selected Patient
                    </div>

                    <div class="selected-patient-grid">

                        <div>
                            <span>Name</span>

                            <strong id="selectedPatientName">
                                {{ $selectedPatient?->name ?? '' }}
                            </strong>
                        </div>

                        <div>
                            <span>Patient Number</span>

                            <strong id="selectedPatientNumber">
                                {{ $selectedPatient?->patient_number ?? '' }}
                            </strong>
                        </div>

                        <div>
                            <span>Phone</span>

                            <strong id="selectedPatientPhone">
                                {{ $selectedPatient?->phone ?? 'Not provided' }}
                            </strong>
                        </div>

                    </div>

                </div>

                <button
                    type="button"
                    class="btn-secondary btn-small"
                    onclick="clearSelectedPatient()"
                >
                    Change
                </button>

            </div>

        </section>


        <!-- Prescription Information -->
        <section class="form-card">

            <div class="section-header">

                <div>
                    <h2>Prescription Information</h2>

                    <p>
                        Enter the basic information for this prescription.
                    </p>
                </div>

            </div>


            <div class="two-column-grid">

                <div class="form-group">

                    <label for="prescriptionDate">
                        Prescription Date
                    </label>

                    <input
                        type="date"
                        id="prescriptionDate"
                        class="form-input"
                        value="{{ now()->format('Y-m-d') }}"
                    >

                </div>


                <div class="form-group">

                    <label for="prescribingDoctor">
                        Prescribing Doctor
                    </label>

                    <input
                        type="text"
                        id="prescribingDoctor"
                        class="form-input"
                        value="{{ auth()->user()->name }}"
                        readonly
                    >

                </div>


                <div class="form-group">

                    <label for="prescriptionNumber">
                        Prescription Number
                    </label>

                    <input
                        type="text"
                        id="prescriptionNumber"
                        class="form-input"
                        value="Generated automatically"
                        readonly
                    >

                </div>


                <div class="form-group">

                    <label>
                        Status
                    </label>

                    <div class="status-field">

                        <span class="status-badge">
                            Pending Pharmacy Verification
                        </span>

                    </div>

                </div>

            </div>


            <div class="status-description">

                <strong>Prescription Status</strong>

                <span>
                    The prescription will be reviewed and verified by the
                    pharmacist before dispensing.
                </span>

            </div>


            <div class="form-group full-width">

                <label for="prescriptionNotes">
                    General Notes
                </label>

                <textarea
                    id="prescriptionNotes"
                    class="form-textarea"
                    placeholder="Enter any general notes about this prescription..."
                ></textarea>

            </div>

        </section>


        <!-- Medicines -->
        <section class="form-card">

            <div class="section-header medicines-header">

                <div>
                    <h2>Medicines</h2>

                    <p>
                        Search and add medicines to this prescription.
                    </p>
                </div>

            </div>


            <!-- Medicine Search -->
            <div class="form-group full-width">

                <label for="medicineSearch">
                    Search Medicine
                </label>

                <input
                    type="text"
                    id="medicineSearch"
                    class="form-input"
                    placeholder="Search by medicine name, generic name, brand name, strength or dosage form..."
                    autocomplete="off"
                >

                <div
                    id="medicineSearchResults"
                    class="search-results"
                    style="display: none;"
                ></div>

            </div>


            <!-- Available Medicines -->
            <div
                id="availableMedicinesSection"
                class="available-medicines-section"
            >

                <div class="catalogue-title">
                    Available Medicines
                </div>

                <div
                    id="availableMedicines"
                    class="medicine-table-wrapper"
                >

                    @if(isset($medicines) && $medicines->count() > 0)

                        <table class="medicine-table">

                            <thead>

                                <tr>
                                    <th>Medicine</th>
                                    <th>Generic Name</th>
                                    <th>Strength</th>
                                    <th>Dosage Form</th>
                                    <th>Type</th>
                                    <th></th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach($medicines as $medicine)

                                    <tr id="catalogue-medicine-{{ $medicine->id }}">

                                        <td>
                                            <strong>
                                                {{ $medicine->name }}
                                            </strong>
                                        </td>

                                        <td>
                                            {{ $medicine->generic_name ?: '—' }}
                                        </td>

                                        <td>
                                            {{ $medicine->strength ?: '—' }}
                                        </td>

                                        <td>
                                            {{ $medicine->dosage_form ?: '—' }}
                                        </td>

                                        <td>

                                            @if($medicine->prescription_required)

                                                <span class="medicine-type prescription">
                                                    Prescription
                                                </span>

                                            @else

                                                <span class="medicine-type otc">
                                                    OTC
                                                </span>

                                            @endif

                                        </td>

                                        <td class="medicine-action-cell">

                                            <button
                                                type="button"
                                                class="btn-add-medicine"
                                                data-medicine-id="{{ $medicine->id }}"
                                                onclick="addMedicineFromCatalogue(
                                                    {{ $medicine->id }},
                                                    @js($medicine->name),
                                                    @js($medicine->generic_name ?? ''),
                                                    @js($medicine->strength ?? ''),
                                                    @js($medicine->dosage_form ?? ''),
                                                    {{ $medicine->prescription_required ? 'true' : 'false' }}
                                                )"
                                            >
                                                + Add
                                            </button>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    @else

                        <div class="empty-state">

                            <div class="empty-state-icon">
                                +
                            </div>

                            <strong>
                                No medicines available
                            </strong>

                            <span>
                                Active medicines will appear here once they
                                have been added to the medicine inventory.
                            </span>

                        </div>

                    @endif

                </div>

            </div>


            <!-- Selected Medicines -->
            <div
                id="selectedMedicinesSection"
                class="selected-medicines-section"
            >

                <div class="catalogue-title">
                    Selected Medicines
                </div>

                <div
                    id="selectedMedicines"
                    class="selected-medicines-table-wrapper"
                ></div>

                <div
                    id="noMedicinesMessage"
                    class="empty-state selected-empty-state"
                >

                    <strong>
                        No medicines added yet
                    </strong>

                    <span>
                        Search for a medicine above or add one from the
                        available medicines list.
                    </span>

                </div>

            </div>

        </section>


        <!-- Form Footer -->
        <div class="form-footer">

            <a
                href="{{ route('doctor.dashboard') }}"
                class="btn-secondary"
            >
                Cancel
            </a>

            <button
                type="button"
                id="submitPrescriptionButton"
                class="btn-primary"
                onclick="submitPrescription()"
            >
                Create Prescription
            </button>

        </div>

    </div>

</div>


<!-- Register Patient Modal -->
<div
    class="modal-overlay"
    id="registerPatientModal"
    style="display: none;"
>

    <div class="patient-modal">

        <div class="modal-header">

            <div>
                <h2>Register New Patient</h2>

                <p>
                    A permanent patient number will be generated automatically.
                </p>
            </div>

            <button
                type="button"
                class="modal-close"
                onclick="closeRegisterPatientModal()"
                aria-label="Close"
            >
                ×
            </button>

        </div>


        <div class="modal-body">

            <div id="registerPatientAlert"></div>


            <div class="two-column-grid">

                <div class="form-group">

                    <label for="patientName">
                        Full Name <span class="required-mark">*</span>
                    </label>

                    <input
                        type="text"
                        id="patientName"
                        class="form-input"
                        placeholder="Enter patient's full name"
                    >

                </div>


                <div class="form-group">

                    <label for="patientPhone">
                        Phone Number
                    </label>

                    <input
                        type="text"
                        id="patientPhone"
                        class="form-input"
                        placeholder="Enter phone number"
                    >

                </div>


                <div class="form-group full-width">

                    <label for="patientAddress">
                        Address
                    </label>

                    <input
                        type="text"
                        id="patientAddress"
                        class="form-input"
                        placeholder="Enter patient's address"
                    >

                </div>


                <div class="form-group full-width">

                    <label for="patientNotes">
                        Notes
                    </label>

                    <textarea
                        id="patientNotes"
                        class="form-textarea"
                        placeholder="Any additional patient information..."
                    ></textarea>

                </div>

            </div>


            <div class="patient-number-info">

                <strong>Patient Number</strong>

                <span>
                    This will be generated automatically when the patient is
                    registered. The same patient number will be used when the
                    patient returns in the future.
                </span>

            </div>

        </div>


        <div class="modal-footer">

            <button
                type="button"
                class="btn-secondary"
                onclick="closeRegisterPatientModal()"
            >
                Cancel
            </button>

            <button
                type="button"
                id="registerPatientButton"
                class="btn-primary"
                onclick="registerPatient()"
            >
                Register Patient
            </button>

        </div>

    </div>

</div>


<style>

    :root {
        --primary: #2563eb;
        --nav: #1e3a8a;
        --bg: #f5f6fa;
        --card: #ffffff;
        --border: #e5e7eb;
        --text: #111827;
        --muted: #6b7280;
        --radius: 12px;
    }


    .doctor-prescription-page {
        min-height: calc(100vh - 70px);
        background: var(--bg);
        color: var(--text);
        padding: 0;
    }


    .prescription-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 40px 24px;
    }


    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
        margin-bottom: 32px;
    }


    .page-header h1 {
        margin: 0 0 8px;
        font-size: 32px;
        line-height: 1.2;
        font-weight: 700;
        color: var(--text);
    }


    .page-header p {
        margin: 0;
        color: var(--muted);
        font-size: 15px;
    }


    .form-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 24px;
    }


    .section-header {
        margin-bottom: 24px;
    }


    .section-header h2 {
        margin: 0 0 6px;
        font-size: 19px;
        line-height: 1.4;
        font-weight: 700;
        color: var(--text);
    }


    .section-header p {
        margin: 0;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.5;
    }


    .patient-search-layout {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 20px;
        align-items: end;
    }


    .two-column-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }


    .form-group {
        min-width: 0;
    }


    .form-group.full-width {
        grid-column: 1 / -1;
    }


    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        line-height: 1.4;
        font-weight: 600;
        color: var(--text);
    }


    .form-input,
    .form-textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        color: var(--text);
        font-family: inherit;
        font-size: 14px;
        outline: none;
        transition:
            border-color 0.15s ease,
            box-shadow 0.15s ease;
    }


    .form-input {
        height: 40px;
        padding: 0 12px;
    }


    .form-textarea {
        min-height: 100px;
        padding: 12px;
        resize: vertical;
        line-height: 1.5;
    }


    .form-input:focus,
    .form-textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }


    .form-input[readonly] {
        background: #f9fafb;
        color: #4b5563;
    }


    .btn-primary,
    .btn-secondary,
    .btn-add-medicine {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 40px;
        padding: 0 16px;
        border-radius: 8px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition:
            background 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease,
            box-shadow 0.15s ease;
        box-sizing: border-box;
    }


    .btn-primary {
        border: 1px solid var(--primary);
        background: var(--primary);
        color: #fff;
    }


    .btn-primary:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #fff;
    }


    .btn-primary:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }


    .btn-secondary {
        border: 1px solid #d1d5db;
        background: #fff;
        color: #374151;
    }


    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        color: #111827;
    }


    .btn-small {
        min-height: 36px;
        padding: 0 12px;
        flex-shrink: 0;
    }


    .register-patient-button-wrapper {
        display: flex;
        align-items: flex-end;
    }


    .register-patient-button-wrapper .btn-primary {
        white-space: nowrap;
    }


    .selected-patient {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-top: 24px;
        padding: 18px;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        background: #eff6ff;
    }


    .selected-patient-info {
        min-width: 0;
    }


    .selected-patient-title {
        margin-bottom: 14px;
        font-size: 14px;
        font-weight: 700;
        color: #1e40af;
    }


    .selected-patient-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
    }


    .selected-patient-grid span {
        display: block;
        margin-bottom: 4px;
        color: var(--muted);
        font-size: 12px;
    }


    .selected-patient-grid strong {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        word-break: break-word;
    }


    .status-field {
        min-height: 40px;
        display: flex;
        align-items: center;
    }


    .status-badge {
        display: inline-flex;
        align-items: center;
        min-height: 30px;
        padding: 0 10px;
        border-radius: 999px;
        background: #fef3c7;
        color: #92400e;
        font-size: 12px;
        font-weight: 700;
    }


    .status-description {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin: 22px 0;
        padding: 14px 16px;
        border-radius: 8px;
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
        font-size: 13px;
        line-height: 1.5;
    }


    .status-description strong {
        font-weight: 700;
    }


    .medicines-header {
        margin-bottom: 20px;
    }


    .available-medicines-section,
    .selected-medicines-section {
        margin-top: 24px;
    }


    .catalogue-title {
        margin-bottom: 12px;
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
    }


    .medicine-table-wrapper,
    .selected-medicines-table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid var(--border);
        border-radius: 10px;
    }


    .medicine-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 760px;
    }


    .medicine-table th {
        padding: 12px 14px;
        background: #f9fafb;
        border-bottom: 1px solid var(--border);
        color: #374151;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }


    .medicine-table td {
        padding: 13px 14px;
        border-bottom: 1px solid var(--border);
        color: #4b5563;
        font-size: 13px;
        vertical-align: middle;
    }


    .medicine-table tbody tr:last-child td {
        border-bottom: none;
    }


    .medicine-table tbody tr:hover {
        background: #f9fafb;
    }


    .medicine-table td strong {
        color: var(--text);
        font-weight: 600;
    }


    .medicine-action-cell {
        text-align: right;
    }


    .medicine-type {
        display: inline-flex;
        align-items: center;
        min-height: 25px;
        padding: 0 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }


    .medicine-type.prescription {
        background: #dbeafe;
        color: #1e40af;
    }


    .medicine-type.otc {
        background: #dcfce7;
        color: #166534;
    }


    .btn-add-medicine {
        min-height: 32px;
        padding: 0 11px;
        border: 1px solid #bfdbfe;
        background: #eff6ff;
        color: var(--primary);
        font-size: 12px;
    }


    .btn-add-medicine:hover {
        background: #dbeafe;
        border-color: #93c5fd;
    }


    .btn-add-medicine.added {
        border-color: #bbf7d0;
        background: #f0fdf4;
        color: #166534;
        cursor: default;
    }


    .selected-medicine-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }


    .selected-medicine-table th {
        padding: 12px;
        background: #f9fafb;
        border-bottom: 1px solid var(--border);
        color: #374151;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
    }


    .selected-medicine-table td {
        padding: 12px;
        border-bottom: 1px solid var(--border);
        vertical-align: top;
    }


    .selected-medicine-table tbody tr:last-child td {
        border-bottom: none;
    }


    .selected-medicine-table .medicine-name {
        min-width: 180px;
        font-weight: 600;
        color: var(--text);
    }


    .selected-medicine-table .medicine-subtitle {
        margin-top: 3px;
        color: var(--muted);
        font-size: 12px;
        font-weight: 400;
    }


    .table-input {
        width: 100%;
        height: 36px;
        padding: 0 9px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        color: var(--text);
        font-family: inherit;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
    }


    .table-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }


    .remove-medicine-button {
        height: 36px;
        padding: 0 10px;
        border: 1px solid #fecaca;
        border-radius: 7px;
        background: #fff;
        color: #dc2626;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }


    .remove-medicine-button:hover {
        background: #fef2f2;
        border-color: #fca5a5;
    }


    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 150px;
        padding: 30px;
        border: 1px dashed #d1d5db;
        border-radius: 10px;
        background: #fafafa;
        text-align: center;
    }


    .empty-state-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        margin-bottom: 10px;
        border-radius: 50%;
        background: #eff6ff;
        color: var(--primary);
        font-size: 22px;
        font-weight: 400;
    }


    .empty-state strong {
        margin-bottom: 5px;
        color: var(--text);
        font-size: 14px;
        font-weight: 600;
    }


    .empty-state span {
        max-width: 500px;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.5;
    }


    .selected-empty-state {
        margin-top: 0;
    }


    .search-results {
        position: relative;
        z-index: 50;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        margin-top: 8px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }


    .search-result-item {
        display: block;
        width: 100%;
        padding: 12px 14px;
        border: none;
        border-bottom: 1px solid var(--border);
        background: #fff;
        color: var(--text);
        text-align: left;
        cursor: pointer;
    }


    .search-result-item:last-child {
        border-bottom: none;
    }


    .search-result-item:hover {
        background: #f9fafb;
    }


    .search-result-name {
        display: block;
        margin-bottom: 4px;
        font-size: 14px;
        font-weight: 600;
    }


    .search-result-meta {
        display: block;
        color: var(--muted);
        font-size: 12px;
    }


    .search-result-empty {
        padding: 14px;
        color: var(--muted);
        font-size: 13px;
        text-align: center;
    }


    .search-result-error {
        padding: 14px;
        color: #dc2626;
        font-size: 13px;
    }


    .form-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        padding-bottom: 40px;
    }


    .alert-message {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        padding: 14px 16px;
        border: 1px solid;
        border-radius: 8px;
        font-size: 14px;
        line-height: 1.5;
    }


    .alert-message.success {
        border-color: #bbf7d0;
        background: #f0fdf4;
        color: #166534;
    }


    .alert-message.warning {
        border-color: #fde68a;
        background: #fffbeb;
        color: #92400e;
    }


    .alert-message.danger {
        border-color: #fecaca;
        background: #fef2f2;
        color: #991b1b;
    }


    .alert-close {
        border: none;
        background: transparent;
        color: inherit;
        font-size: 18px;
        line-height: 1;
        cursor: pointer;
    }


    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: rgba(17, 24, 39, 0.55);
    }


    .patient-modal {
        width: 100%;
        max-width: 720px;
        max-height: calc(100vh - 48px);
        overflow-y: auto;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    }


    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        padding: 24px;
        border-bottom: 1px solid var(--border);
    }


    .modal-header h2 {
        margin: 0 0 6px;
        font-size: 20px;
        font-weight: 700;
    }


    .modal-header p {
        margin: 0;
        color: var(--muted);
        font-size: 13px;
    }


    .modal-close {
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 7px;
        background: #f3f4f6;
        color: #4b5563;
        font-size: 22px;
        line-height: 1;
        cursor: pointer;
    }


    .modal-close:hover {
        background: #e5e7eb;
    }


    .modal-body {
        padding: 24px;
    }


    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 18px 24px;
        border-top: 1px solid var(--border);
        background: #fafafa;
        border-radius: 0 0 12px 12px;
    }


    .patient-number-info {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-top: 24px;
        padding: 14px 16px;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        background: #eff6ff;
        color: #1e40af;
        font-size: 13px;
        line-height: 1.5;
    }


    .patient-number-info strong {
        font-weight: 700;
    }


    .required-mark {
        color: #dc2626;
    }


    @media (max-width: 768px) {

        .prescription-container {
            padding: 28px 16px;
        }


        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }


        .page-header .btn-secondary {
            width: 100%;
        }


        .page-header h1 {
            font-size: 27px;
        }


        .patient-search-layout,
        .two-column-grid {
            grid-template-columns: 1fr;
        }


        .form-group.full-width {
            grid-column: auto;
        }


        .register-patient-button-wrapper {
            width: 100%;
        }


        .register-patient-button-wrapper .btn-primary {
            width: 100%;
        }


        .selected-patient {
            flex-direction: column;
            align-items: stretch;
        }


        .selected-patient-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }


        .selected-patient .btn-secondary {
            width: 100%;
        }


        .form-footer {
            flex-direction: column-reverse;
        }


        .form-footer .btn-primary,
        .form-footer .btn-secondary {
            width: 100%;
        }


        .patient-modal {
            max-height: calc(100vh - 24px);
        }


        .modal-overlay {
            padding: 12px;
        }

    }

</style>


<script>

    const csrfToken = '{{ csrf_token() }}';

    const initialSelectedPatient = @json($selectedPatient);

    let selectedMedicines = [];

    let patientSearchTimeout = null;

    let medicineSearchTimeout = null;


    document.addEventListener('DOMContentLoaded', function () {

        if (initialSelectedPatient) {
            selectPatient(initialSelectedPatient);
        }

        setupPatientSearch();

        setupMedicineSearch();

        updateCatalogueButtons();

    });


    /*
     * ------------------------------------------------------------
     * Patient Search
     * ------------------------------------------------------------
     */

    function setupPatientSearch() {

        const input =
            document.getElementById('patientSearch');


        input.addEventListener('input', function () {

            const query =
                this.value.trim();


            clearTimeout(patientSearchTimeout);


            if (query.length < 2) {

                hidePatientSearchResults();

                return;

            }


            patientSearchTimeout =
                setTimeout(function () {

                    searchPatients(query);

                }, 300);

        });

    }


    async function searchPatients(query) {

        const resultsContainer =
            document.getElementById('patientSearchResults');


        try {

            const url =
                `{{ route('doctor.patients.search') }}?search=${encodeURIComponent(query)}`;


            const response =
                await fetch(
                    url,
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Unable to search patients.'
                );

            }


            const data =
                await response.json();


            resultsContainer.innerHTML = '';


            if (
                !data.patients ||
                data.patients.length === 0
            ) {

                resultsContainer.innerHTML = `
                    <div class="search-result-empty">
                        No patients found.
                    </div>
                `;

                resultsContainer.style.display =
                    'block';

                return;

            }


            data.patients.forEach(function (patient) {

                const item =
                    document.createElement('button');


                item.type =
                    'button';


                item.className =
                    'search-result-item';


                item.innerHTML = `
                    <span class="search-result-name">
                        ${escapeHtml(patient.name)}
                    </span>

                    <span class="search-result-meta">
                        Patient No:
                        ${escapeHtml(
                            patient.patient_number ||
                            'Not assigned'
                        )}

                        ${
                            patient.phone
                                ? ' • ' +
                                  escapeHtml(patient.phone)
                                : ''
                        }
                    </span>
                `;


                item.addEventListener(
                    'click',
                    function () {

                        selectPatient(patient);

                        hidePatientSearchResults();

                    }
                );


                resultsContainer.appendChild(item);

            });


            resultsContainer.style.display =
                'block';


        } catch (error) {

            resultsContainer.innerHTML = `
                <div class="search-result-error">
                    Unable to search patients.
                    Please try again.
                </div>
            `;


            resultsContainer.style.display =
                'block';


            console.error(error);

        }

    }


    function selectPatient(patient) {

        document.getElementById('customerId').value =
            patient.id || '';


        document.getElementById('selectedPatientName').textContent =
            patient.name || '';


        document.getElementById('selectedPatientNumber').textContent =
            patient.patient_number ||
            'Not assigned';


        document.getElementById('selectedPatientPhone').textContent =
            patient.phone ||
            'Not provided';


        document.getElementById('selectedPatientCard').style.display =
            'flex';


        document.getElementById('patientSearch').value =
            patient.patient_number ||
            patient.name ||
            '';

    }


    function clearSelectedPatient() {

        document.getElementById('customerId').value =
            '';


        document.getElementById('selectedPatientCard').style.display =
            'none';


        document.getElementById('patientSearch').value =
            '';


        document.getElementById('patientSearch').focus();

    }


    function hidePatientSearchResults() {

        document.getElementById('patientSearchResults').style.display =
            'none';

    }


    /*
     * ------------------------------------------------------------
     * Register Patient
     * ------------------------------------------------------------
     */

    function openRegisterPatientModal() {

        clearRegisterPatientForm();


        const modal =
            document.getElementById(
                'registerPatientModal'
            );


        modal.style.display =
            'flex';


        document.body.style.overflow =
            'hidden';


        setTimeout(function () {

            document.getElementById(
                'patientName'
            ).focus();

        }, 100);

    }


    function closeRegisterPatientModal() {

        const modal =
            document.getElementById(
                'registerPatientModal'
            );


        modal.style.display =
            'none';


        document.body.style.overflow =
            '';

    }


    function clearRegisterPatientForm() {

        document.getElementById(
            'patientName'
        ).value = '';


        document.getElementById(
            'patientPhone'
        ).value = '';


        document.getElementById(
            'patientAddress'
        ).value = '';


        document.getElementById(
            'patientNotes'
        ).value = '';


        document.getElementById(
            'registerPatientAlert'
        ).innerHTML = '';

    }


    async function registerPatient() {

        const button =
            document.getElementById(
                'registerPatientButton'
            );


        const alertContainer =
            document.getElementById(
                'registerPatientAlert'
            );


        const name =
            document.getElementById(
                'patientName'
            ).value.trim();


        const phone =
            document.getElementById(
                'patientPhone'
            ).value.trim();


        const address =
            document.getElementById(
                'patientAddress'
            ).value.trim();


        const notes =
            document.getElementById(
                'patientNotes'
            ).value.trim();


        if (!name) {

            alertContainer.innerHTML = `
                <div class="alert-message danger">
                    <span>
                        Please enter the patient's full name.
                    </span>
                </div>
            `;

            return;

        }


        button.disabled =
            true;


        button.innerHTML = `
            <span>
                Registering...
            </span>
        `;


        try {

            const response =
                await fetch(
                    '{{ route('doctor.patients.store') }}',
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                csrfToken
                        },

                        body: JSON.stringify({
                            name: name,

                            phone:
                                phone || null,

                            address:
                                address || null,

                            notes:
                                notes || null
                        })
                    }
                );


            const data =
                await response.json();


            if (!response.ok) {

                let message =
                    data.message ||
                    'Unable to register patient.';


                if (data.errors) {

                    const errors = [];


                    Object.values(
                        data.errors
                    ).forEach(
                        function (fieldErrors) {

                            fieldErrors.forEach(
                                function (error) {

                                    errors.push(error);

                                }
                            );

                        }
                    );


                    if (errors.length > 0) {

                        message =
                            errors.join('<br>');

                    }

                }


                throw new Error(message);

            }


            if (!data.patient) {

                throw new Error(
                    'Patient was registered, but patient information was not returned.'
                );

            }


            selectPatient(
                data.patient
            );


            clearRegisterPatientForm();


            closeRegisterPatientModal();


            showAlert(
                'success',
                `
                    Patient registered successfully.
                    <strong>
                        Patient Number:
                        ${escapeHtml(
                            data.patient.patient_number ||
                            'Generated'
                        )}
                    </strong>
                `
            );


        } catch (error) {

            alertContainer.innerHTML = `
                <div class="alert-message danger">
                    <span>
                        ${escapeHtml(error.message)}
                    </span>
                </div>
            `;


            console.error(error);

        } finally {

            button.disabled =
                false;


            button.innerHTML =
                'Register Patient';

        }

    }


    /*
     * ------------------------------------------------------------
     * Medicine Search
     * ------------------------------------------------------------
     */

    function setupMedicineSearch() {

        const input =
            document.getElementById(
                'medicineSearch'
            );


        input.addEventListener(
            'input',
            function () {

                const query =
                    this.value.trim();


                clearTimeout(
                    medicineSearchTimeout
                );


                if (query.length < 2) {

                    hideMedicineSearchResults();

                    return;

                }


                medicineSearchTimeout =
                    setTimeout(
                        function () {

                            searchMedicines(
                                query
                            );

                        },
                        300
                    );

            }
        );

    }


    async function searchMedicines(query) {

        const resultsContainer =
            document.getElementById(
                'medicineSearchResults'
            );


        try {

            const url =
                `{{ route('doctor.medicines.search') }}?search=${encodeURIComponent(query)}`;


            const response =
                await fetch(
                    url,
                    {
                        headers: {
                            'Accept':
                                'application/json'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Unable to search medicines.'
                );

            }


            const data =
                await response.json();


            resultsContainer.innerHTML =
                '';


            if (
                !data.medicines ||
                data.medicines.length === 0
            ) {

                resultsContainer.innerHTML = `
                    <div class="search-result-empty">
                        No medicines found.
                    </div>
                `;


                resultsContainer.style.display =
                    'block';


                return;

            }


            data.medicines.forEach(
                function (medicine) {

                    const alreadyAdded =
                        selectedMedicines.some(
                            function (item) {

                                return item.id ==
                                    medicine.id;

                            }
                        );


                    const item =
                        document.createElement(
                            'button'
                        );


                    item.type =
                        'button';


                    item.className =
                        'search-result-item';


                    item.innerHTML = `
                        <div style="
                            display: flex;
                            justify-content: space-between;
                            align-items: flex-start;
                            gap: 12px;
                        ">

                            <div>

                                <span class="search-result-name">
                                    ${escapeHtml(
                                        medicine.name
                                    )}
                                </span>

                                <span class="search-result-meta">
                                    ${escapeHtml(
                                        medicine.generic_name ||
                                        ''
                                    )}

                                    ${
                                        medicine.strength
                                            ? ' • ' +
                                              escapeHtml(
                                                  medicine.strength
                                              )
                                            : ''
                                    }

                                    ${
                                        medicine.dosage_form
                                            ? ' • ' +
                                              escapeHtml(
                                                  medicine.dosage_form
                                              )
                                            : ''
                                    }
                                </span>

                            </div>

                            <span class="medicine-type ${
                                medicine.prescription_required
                                    ? 'prescription'
                                    : 'otc'
                            }">
                                ${
                                    medicine.prescription_required
                                        ? 'Prescription'
                                        : 'OTC'
                                }
                            </span>

                        </div>
                    `;


                    if (alreadyAdded) {

                        item.style.opacity =
                            '0.6';

                    }


                    item.addEventListener(
                        'click',
                        function () {

                            addMedicine(
                                medicine
                            );


                            document.getElementById(
                                'medicineSearch'
                            ).value =
                                '';


                            hideMedicineSearchResults();

                        }
                    );


                    resultsContainer.appendChild(
                        item
                    );

                }
            );


            resultsContainer.style.display =
                'block';


        } catch (error) {

            resultsContainer.innerHTML = `
                <div class="search-result-error">
                    Unable to search medicines.
                    Please try again.
                </div>
            `;


            resultsContainer.style.display =
                'block';


            console.error(error);

        }

    }


    /*
     * ------------------------------------------------------------
     * Add Medicine From Initial Catalogue
     * ------------------------------------------------------------
     */

    function addMedicineFromCatalogue(
        id,
        name,
        genericName,
        strength,
        dosageForm,
        prescriptionRequired
    ) {

        addMedicine({

            id: id,

            name: name,

            generic_name:
                genericName || '',

            strength:
                strength || '',

            dosage_form:
                dosageForm || '',

            prescription_required:
                Boolean(
                    prescriptionRequired
                )

        });

    }


    /*
     * ------------------------------------------------------------
     * Add Medicine
     * ------------------------------------------------------------
     */

    function addMedicine(medicine) {

        const exists =
            selectedMedicines.some(
                function (item) {

                    return item.id ==
                        medicine.id;

                }
            );


        if (exists) {

            showAlert(
                'warning',
                `${escapeHtml(
                    medicine.name
                )} is already added.`
            );


            return;

        }


        selectedMedicines.push({

            id:
                medicine.id,

            name:
                medicine.name,

            generic_name:
                medicine.generic_name ||
                '',

            strength:
                medicine.strength ||
                '',

            dosage_form:
                medicine.dosage_form ||
                '',

            prescription_required:
                Boolean(
                    medicine.prescription_required
                ),

            dosage:
                '',

            frequency:
                '',

            duration:
                '',

            route:
                '',

            instructions:
                '',

            quantity:
                1

        });


        renderSelectedMedicines();

        updateCatalogueButtons();

    }


    function removeMedicine(index) {

        selectedMedicines.splice(
            index,
            1
        );


        renderSelectedMedicines();

        updateCatalogueButtons();

    }


    function renderSelectedMedicines() {

        const container =
            document.getElementById(
                'selectedMedicines'
            );


        const emptyMessage =
            document.getElementById(
                'noMedicinesMessage'
            );


        container.innerHTML =
            '';


        if (
            selectedMedicines.length ===
            0
        ) {

            emptyMessage.style.display =
                'flex';


            return;

        }


        emptyMessage.style.display =
            'none';


        const table =
            document.createElement(
                'table'
            );


        table.className =
            'selected-medicine-table';


        table.innerHTML = `
            <thead>

                <tr>
                    <th>Medicine</th>
                    <th style="width: 100px;">Quantity</th>
                    <th>Dosage</th>
                    <th>Frequency</th>
                    <th>Duration</th>
                    <th>Route</th>
                    <th>Instructions</th>
                    <th style="width: 90px;">Action</th>
                </tr>

            </thead>

            <tbody></tbody>
        `;


        const tbody =
            table.querySelector(
                'tbody'
            );


        selectedMedicines.forEach(
            function (medicine, index) {

                const row =
                    document.createElement(
                        'tr'
                    );


                row.innerHTML = `

                    <td class="medicine-name">

                        ${escapeHtml(
                            medicine.name
                        )}

                        <div class="medicine-subtitle">

                            ${escapeHtml(
                                medicine.generic_name
                            )}

                            ${
                                medicine.strength
                                    ? ' • ' +
                                      escapeHtml(
                                          medicine.strength
                                      )
                                    : ''
                            }

                            ${
                                medicine.dosage_form
                                    ? ' • ' +
                                      escapeHtml(
                                          medicine.dosage_form
                                      )
                                    : ''
                            }

                        </div>

                    </td>


                    <td>

                        <input
                            type="number"
                            min="1"
                            class="table-input"
                            value="${medicine.quantity}"
                            oninput="
                                updateMedicineField(
                                    ${index},
                                    'quantity',
                                    this.value
                                )
                            "
                        >

                    </td>


                    <td>

                        <input
                            type="text"
                            class="table-input"
                            placeholder="1 tablet"
                            value="${escapeHtml(
                                medicine.dosage
                            )}"
                            oninput="
                                updateMedicineField(
                                    ${index},
                                    'dosage',
                                    this.value
                                )
                            "
                        >

                    </td>


                    <td>

                        <input
                            type="text"
                            class="table-input"
                            placeholder="3 times daily"
                            value="${escapeHtml(
                                medicine.frequency
                            )}"
                            oninput="
                                updateMedicineField(
                                    ${index},
                                    'frequency',
                                    this.value
                                )
                            "
                        >

                    </td>


                    <td>

                        <input
                            type="text"
                            class="table-input"
                            placeholder="7 days"
                            value="${escapeHtml(
                                medicine.duration
                            )}"
                            oninput="
                                updateMedicineField(
                                    ${index},
                                    'duration',
                                    this.value
                                )
                            "
                        >

                    </td>


                    <td>

                        <input
                            type="text"
                            class="table-input"
                            placeholder="Oral"
                            value="${escapeHtml(
                                medicine.route
                            )}"
                            oninput="
                                updateMedicineField(
                                    ${index},
                                    'route',
                                    this.value
                                )
                            "
                        >

                    </td>


                    <td>

                        <input
                            type="text"
                            class="table-input"
                            placeholder="After meals"
                            value="${escapeHtml(
                                medicine.instructions
                            )}"
                            oninput="
                                updateMedicineField(
                                    ${index},
                                    'instructions',
                                    this.value
                                )
                            "
                        >

                    </td>


                    <td>

                        <button
                            type="button"
                            class="remove-medicine-button"
                            onclick="
                                removeMedicine(
                                    ${index}
                                )
                            "
                        >
                            Remove
                        </button>

                    </td>

                `;


                tbody.appendChild(
                    row
                );

            }
        );


        container.appendChild(
            table
        );

    }


    function updateMedicineField(
        index,
        field,
        value
    ) {

        if (
            !selectedMedicines[index]
        ) {

            return;

        }


        if (
            field === 'quantity'
        ) {

            const quantity =
                parseInt(
                    value,
                    10
                );


            selectedMedicines[index][field] =
                Number.isFinite(
                    quantity
                ) &&
                quantity > 0
                    ? quantity
                    : 1;

        } else {

            selectedMedicines[index][field] =
                value.trim();

        }

    }


    function updateCatalogueButtons() {

        document
            .querySelectorAll(
                '.btn-add-medicine'
            )
            .forEach(
                function (button) {

                    const medicineId =
                        button.dataset.medicineId;


                    const alreadyAdded =
                        selectedMedicines.some(
                            function (medicine) {

                                return String(
                                    medicine.id
                                ) ===
                                String(
                                    medicineId
                                );

                            }
                        );


                    if (alreadyAdded) {

                        button.classList.add(
                            'added'
                        );


                        button.textContent =
                            '✓ Added';


                        button.disabled =
                            true;

                    } else {

                        button.classList.remove(
                            'added'
                        );


                        button.textContent =
                            '+ Add';


                        button.disabled =
                            false;

                    }

                }
            );

    }


    function hideMedicineSearchResults() {

        document.getElementById(
            'medicineSearchResults'
        ).style.display =
            'none';

    }


    /*
     * ------------------------------------------------------------
     * Submit Prescription
     * ------------------------------------------------------------
     */

    async function submitPrescription() {

        const button =
            document.getElementById(
                'submitPrescriptionButton'
            );


        const customerId =
            document.getElementById(
                'customerId'
            ).value;


        const prescriptionDate =
            document.getElementById(
                'prescriptionDate'
            ).value;


        const notes =
            document.getElementById(
                'prescriptionNotes'
            ).value.trim();


        if (!customerId) {

            showAlert(
                'danger',
                'Please select or register a patient before creating the prescription.'
            );


            return;

        }


        if (!prescriptionDate) {

            showAlert(
                'danger',
                'Please select the prescription date.'
            );


            return;

        }


        if (
            selectedMedicines.length ===
            0
        ) {

            showAlert(
                'danger',
                'Please add at least one medicine to the prescription.'
            );


            return;

        }


        for (
            let index = 0;
            index < selectedMedicines.length;
            index++
        ) {

            const medicine =
                selectedMedicines[index];


            if (
                !medicine.quantity ||
                medicine.quantity < 1
            ) {

                showAlert(
                    'danger',
                    `Please enter a valid quantity for ${escapeHtml(
                        medicine.name
                    )}.`
                );


                return;

            }


            if (!medicine.dosage) {

                showAlert(
                    'danger',
                    `Please enter the dosage for ${escapeHtml(
                        medicine.name
                    )}.`
                );


                return;

            }


            if (!medicine.frequency) {

                showAlert(
                    'danger',
                    `Please enter the frequency for ${escapeHtml(
                        medicine.name
                    )}.`
                );


                return;

            }


            if (!medicine.duration) {

                showAlert(
                    'danger',
                    `Please enter the duration for ${escapeHtml(
                        medicine.name
                    )}.`
                );


                return;

            }

        }


        button.disabled =
            true;


        button.innerHTML = `
            <span>
                Creating Prescription...
            </span>
        `;


        try {

            /*
             * DoctorController validates:
             *
             * items.*.quantity
             *
             * Therefore the frontend sends the exact same
             * field name: quantity.
             */

            const payload = {

                customer_id:
                    customerId,

                prescription_date:
                    prescriptionDate,

                notes:
                    notes || null,

                items:
                    selectedMedicines.map(
                        function (medicine) {

                            return {

                                medicine_id:
                                    medicine.id,

                                quantity:
                                    medicine.quantity,

                                dosage:
                                    medicine.dosage,

                                frequency:
                                    medicine.frequency,

                                duration:
                                    medicine.duration,

                                route:
                                    medicine.route ||
                                    null,

                                instructions:
                                    medicine.instructions ||
                                    null

                            };

                        }
                    )

            };


            console.log(
                'Prescription payload:',
                payload
            );


            const response =
                await fetch(
                    '{{ route('doctor.prescriptions.store') }}',
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                csrfToken
                        },

                        body:
                            JSON.stringify(
                                payload
                            )
                    }
                );


            const data =
                await response.json();


            if (!response.ok) {

                let message =
                    data.message ||
                    'Unable to create prescription.';


                if (data.errors) {

                    const errors = [];


                    Object.values(
                        data.errors
                    ).forEach(
                        function (fieldErrors) {

                            fieldErrors.forEach(
                                function (error) {

                                    errors.push(
                                        error
                                    );

                                }
                            );

                        }
                    );


                    if (
                        errors.length > 0
                    ) {

                        message =
                            errors.join(
                                '<br>'
                            );

                    }

                }


                throw new Error(
                    message
                );

            }


            showAlert(
                'success',
                `
                    Prescription created successfully.
                    <strong>
                        Pending Pharmacy Verification
                    </strong>
                `
            );


            setTimeout(
                function () {

                    if (
                        data.prescription &&
                        data.prescription.id
                    ) {

                        window.location.href =
                            `/doctor/prescriptions/${data.prescription.id}`;

                    } else {

                        window.location.href =
                            '{{ route('doctor.dashboard') }}';

                    }

                },
                800
            );


        } catch (error) {

            showAlert(
                'danger',
                escapeHtml(
                    error.message
                )
            );


            console.error(
                error
            );


        } finally {

            button.disabled =
                false;


            button.innerHTML =
                'Create Prescription';

        }

    }


    /*
     * ------------------------------------------------------------
     * Alerts
     * ------------------------------------------------------------
     */

    function showAlert(
        type,
        message
    ) {

        const container =
            document.getElementById(
                'alertContainer'
            );


        container.innerHTML = `
            <div class="alert-message ${type}">

                <span>
                    ${message}
                </span>

                <button
                    type="button"
                    class="alert-close"
                    onclick="
                        this.parentElement.remove()
                    "
                >
                    ×
                </button>

            </div>
        `;


        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

    }


    /*
     * ------------------------------------------------------------
     * HTML Escaping
     * ------------------------------------------------------------
     */

    function escapeHtml(value) {

        if (
            value === null ||
            value === undefined
        ) {

            return '';

        }


        return String(value)
            .replace(
                /&/g,
                '&amp;'
            )
            .replace(
                /</g,
                '&lt;'
            )
            .replace(
                />/g,
                '&gt;'
            )
            .replace(
                /"/g,
                '&quot;'
            )
            .replace(
                /'/g,
                '&#039;'
            );

    }


    /*
     * ------------------------------------------------------------
     * Close Modal When Clicking Outside
     * ------------------------------------------------------------
     */

    document.addEventListener(
        'click',
        function (event) {

            const modal =
                document.getElementById(
                    'registerPatientModal'
                );


            if (
                event.target === modal
            ) {

                closeRegisterPatientModal();

            }

        }
    );


    /*
     * ------------------------------------------------------------
     * Escape Key
     * ------------------------------------------------------------
     */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape'
            ) {

                closeRegisterPatientModal();

            }

        }
    );

</script>

@endsection