@extends('layouts.app')

@section('title', 'Pharmacy POS')

@section('content')

<style>
    /* =========================================================
       PHARMACY POS
       Scoped to .pos-wrapper to avoid global CSS conflicts.
       ========================================================= */

    .pos-wrapper,
    .pos-wrapper * {
        box-sizing: border-box;
    }

    .pos-wrapper {
        width: 100%;
        max-width: 100%;
    }

    /* HEADER */
    .pos-wrapper .pos-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        margin-bottom: 20px;
    }

    .pos-wrapper .pos-header h2 {
        margin: 0;
        color: #1e293b;
        font-size: 24px;
        font-weight: 700;
        line-height: 1.3;
    }

    .pos-wrapper .pos-header p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    /* ALERTS */
    .pos-wrapper .alert {
        display: none;
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 15px;
        border-radius: 7px;
        font-size: 13px;
        line-height: 1.5;
    }

    .pos-wrapper .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }

    .pos-wrapper .alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
    }

    /* MAIN GRID */
    .pos-wrapper .pos-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(330px, 390px);
        gap: 20px;
        width: 100%;
        align-items: start;
    }

    .pos-wrapper .pos-left,
    .pos-wrapper .pos-right {
        width: 100%;
        min-width: 0;
    }

    /* CARD */
    .pos-wrapper .card {
        width: 100%;
        min-width: 0;
        padding: 18px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
    }

    .pos-wrapper .card-title {
        margin: 0 0 15px;
        color: #1e293b;
        font-size: 16px;
        font-weight: 700;
    }

    /* SEARCH */
    .pos-wrapper .search-box {
        width: 100%;
        margin-bottom: 18px;
    }

    .pos-wrapper .search-box input {
        display: block;
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        background: #fff;
        color: #1e293b;
        font-size: 13px;
        outline: none;
    }

    .pos-wrapper .search-box input:focus {
        border-color: #1e3a8a;
        box-shadow: 0 0 0 2px rgba(30,58,138,.06);
    }

    /* MEDICINES */
    .pos-wrapper .medicine-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        width: 100%;
    }

    .pos-wrapper .medicine-card {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        min-width: 0;
        width: 100%;
        padding: 14px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #fff;
        transition: box-shadow .2s ease, transform .2s ease;
    }

    .pos-wrapper .medicine-card:hover {
        box-shadow: 0 4px 12px rgba(15,23,42,.08);
        transform: translateY(-1px);
    }

    .pos-wrapper .medicine-name {
        display: block;
        width: 100%;
        min-width: 0;
        margin: 8px 0 7px;
        color: #1e293b;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.4;
        white-space: normal;
        word-break: normal;
        overflow-wrap: anywhere;
    }

    .pos-wrapper .medicine-meta {
        display: block;
        width: 100%;
        margin-top: 3px;
        color: #64748b;
        font-size: 12px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .pos-wrapper .medicine-price {
        margin-top: 10px;
        color: #1e3a8a;
        font-size: 16px;
        font-weight: 700;
    }

    .pos-wrapper .stock-ok,
    .pos-wrapper .stock-warning {
        display: block;
        width: 100%;
        margin-top: 5px;
        font-size: 12px;
        font-weight: 600;
    }

    .pos-wrapper .stock-ok {
        color: #15803d;
    }

    .pos-wrapper .stock-warning {
        color: #dc2626;
    }

    .pos-wrapper .pom-badge,
    .pos-wrapper .otc-badge {
        align-self: flex-start;
        display: inline-flex;
        width: auto;
        max-width: 100%;
        padding: 4px 7px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: 700;
        line-height: 1.3;
        white-space: normal;
    }

    .pos-wrapper .pom-badge {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .pos-wrapper .otc-badge {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    /* BUTTONS */
    .pos-wrapper button {
        font-family: inherit;
        appearance: none;
        -webkit-appearance: none;
    }

    .pos-wrapper .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 8px 13px;
        border: 0;
        border-radius: 7px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
    }

    .pos-wrapper .btn-primary {
        background: #1e3a8a;
        color: #fff;
    }

    .pos-wrapper .btn-primary:hover {
        background: #172554;
    }

    .pos-wrapper .btn-success {
        background: #15803d;
        color: #fff;
    }

    .pos-wrapper .btn-success:hover {
        background: #166534;
    }

    .pos-wrapper .btn-secondary {
        background: #94a3b8;
        color: #fff;
    }

    .pos-wrapper .btn:disabled {
        opacity: .55;
        cursor: not-allowed;
    }

    /* GENERAL FORMS */
    .pos-wrapper .form-group {
        width: 100%;
        min-width: 0;
        margin-bottom: 12px;
    }

    .pos-wrapper .form-group > label,
    .pos-wrapper .payment-section > .form-group > label {
        display: block;
        width: 100%;
        margin-bottom: 5px;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.4;
    }

    .pos-wrapper .required {
        color: #dc2626;
    }

    .pos-wrapper .form-control,
    .pos-wrapper .form-group > input,
    .pos-wrapper .form-group > textarea,
    .pos-wrapper .payment-section input,
    .pos-wrapper .payment-section select {
        display: block;
        width: 100%;
        min-width: 0;
        max-width: 100%;
        min-height: 38px;
        padding: 8px 10px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background: #fff;
        color: #1e293b;
        font-family: inherit;
        font-size: 12px;
        outline: none;
    }

    .pos-wrapper .form-control:focus,
    .pos-wrapper .form-group > input:focus,
    .pos-wrapper .form-group > textarea:focus,
    .pos-wrapper .payment-section input:focus,
    .pos-wrapper .payment-section select:focus {
        border-color: #1e3a8a;
        box-shadow: 0 0 0 2px rgba(30,58,138,.06);
    }

    /* =========================================================
       CUSTOMER DETAILS
       ========================================================= */

    .pos-wrapper .customer-section {
        position: relative;
        z-index: 20;
        width: 100%;
        min-width: 0;
        margin-bottom: 18px;
        padding-bottom: 17px;
        border-bottom: 1px solid #e2e8f0;
    }

    .pos-wrapper .customer-section h4 {
        margin: 0 0 13px;
        color: #1e293b;
        font-size: 15px;
        font-weight: 700;
    }

    .pos-wrapper .customer-search-wrapper {
        position: relative;
        width: 100%;
    }

    .pos-wrapper .customer-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 100;
        display: none;
        max-height: 210px;
        overflow-y: auto;
        margin-top: 4px;
        background: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        box-shadow: 0 8px 20px rgba(15,23,42,.12);
    }

    .pos-wrapper .customer-result {
        display: block;
        width: 100%;
        padding: 10px 12px;
        border: 0;
        border-bottom: 1px solid #f1f5f9;
        background: #fff;
        color: #1e293b;
        cursor: pointer;
        text-align: left;
    }

    .pos-wrapper .customer-result:last-child {
        border-bottom: 0;
    }

    .pos-wrapper .customer-result:hover {
        background: #f8fafc;
    }

    .pos-wrapper .customer-result-name {
        display: block;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.4;
    }

    .pos-wrapper .customer-result-phone {
        display: block;
        margin-top: 2px;
        color: #64748b;
        font-size: 11px;
    }

    .pos-wrapper .customer-search-status {
        padding: 10px 12px;
        color: #64748b;
        font-size: 11px;
        line-height: 1.4;
    }

    .pos-wrapper .customer-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: -4px;
    }

    .pos-wrapper .clear-customer {
        padding: 4px 8px;
        border: 0;
        background: transparent;
        color: #dc2626;
        cursor: pointer;
        font-size: 11px;
    }

    /* =========================================================
       CART
       These rules intentionally use !important to override
       conflicting layout.css rules.
       ========================================================= */

    .pos-wrapper .cart-container {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        max-height: 520px;
        margin-bottom: 18px;
        overflow-x: hidden !important;
        overflow-y: auto;
    }

    .pos-wrapper .cart-item {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        padding: 14px 0 !important;
        margin: 0 !important;
        border-bottom: 1px solid #e2e8f0 !important;
        overflow: visible !important;
    }

    .pos-wrapper .cart-item:first-child {
        padding-top: 0 !important;
    }

    .pos-wrapper .cart-item:last-child {
        border-bottom: 0 !important;
    }

    .pos-wrapper .cart-item-header {
        display: flex !important;
        flex-direction: row !important;
        align-items: flex-start !important;
        justify-content: space-between !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        gap: 10px !important;
        margin: 0 0 9px !important;
        padding: 0 !important;
    }

    .pos-wrapper .cart-item-header > div:first-child {
        display: block !important;
        flex: 1 1 auto !important;
        width: auto !important;
        max-width: calc(100% - 75px) !important;
        min-width: 0 !important;
    }

    .pos-wrapper .cart-item-name {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        margin: 0 !important;
        color: #1e293b !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        line-height: 1.5 !important;
        white-space: normal !important;
        word-break: normal !important;
        overflow-wrap: anywhere !important;
    }

    .pos-wrapper .cart-item-price {
        display: block !important;
        width: 100% !important;
        min-width: 0 !important;
        margin-top: 4px !important;
        color: #64748b !important;
        font-size: 11px !important;
        line-height: 1.4 !important;
    }

    .pos-wrapper .cart-item-total {
        display: block !important;
        flex: 0 0 auto !important;
        width: auto !important;
        min-width: max-content !important;
        color: #1e3a8a !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        line-height: 1.5 !important;
        white-space: nowrap !important;
        text-align: right !important;
    }

    .pos-wrapper .cart-item-controls {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        justify-content: flex-start !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        gap: 6px !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .pos-wrapper .quantity-btn {
        flex: 0 0 32px !important;
        width: 32px !important;
        min-width: 32px !important;
        max-width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        background: #fff !important;
        color: #1e293b !important;
        cursor: pointer !important;
        font-size: 16px !important;
        font-weight: 700 !important;
    }

    .pos-wrapper .quantity-value {
        flex: 0 0 34px !important;
        width: 34px !important;
        min-width: 34px !important;
        text-align: center !important;
        color: #1e293b !important;
        font-size: 13px !important;
        font-weight: 700 !important;
    }

    .pos-wrapper .remove-item {
        flex: 0 0 auto !important;
        width: auto !important;
        min-width: 76px !important;
        height: 32px !important;
        margin-left: auto !important;
        padding: 0 12px !important;
        border: 1px solid #fecaca !important;
        border-radius: 6px !important;
        background: #fef2f2 !important;
        color: #dc2626 !important;
        cursor: pointer !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        white-space: nowrap !important;
    }

    /* PRESCRIPTION IS ALWAYS BELOW THE CART CONTROLS */
    .pos-wrapper .prescription-box {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        margin: 12px 0 0 !important;
        padding: 12px !important;
        clear: both !important;
        border: 1px solid #fbbf24 !important;
        border-radius: 7px !important;
        background: #fffbeb !important;
        overflow: hidden !important;
    }

    .pos-wrapper .prescription-title {
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
        margin: 0 0 10px !important;
        padding-bottom: 9px !important;
        border-bottom: 1px solid #fcd34d !important;
        color: #92400e !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        line-height: 1.3 !important;
    }

    .pos-wrapper .prescription-title::before {
        content: "Rx";
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 25px;
        width: 25px;
        height: 25px;
        margin-right: 7px;
        border-radius: 5px;
        background: #fef3c7;
        color: #92400e;
        font-size: 11px;
        font-weight: 800;
    }

    .pos-wrapper .prescription-grid {
        display: grid !important;
        grid-template-columns: 1fr !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        gap: 9px !important;
    }

    .pos-wrapper .prescription-grid .form-group {
        display: block !important;
        width: 100% !important;
        min-width: 0 !important;
        margin: 0 !important;
    }

    .pos-wrapper .prescription-grid label {
        display: block !important;
        width: 100% !important;
        margin-bottom: 5px !important;
        color: #92400e !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        line-height: 1.3 !important;
    }

    .pos-wrapper .prescription-grid .form-control {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        height: 38px !important;
        padding: 8px 10px !important;
        font-size: 12px !important;
    }

    .pos-wrapper .prescription-grid textarea.form-control {
        height: auto !important;
        min-height: 65px !important;
        resize: vertical !important;
    }

    /* TOTALS */
    .pos-wrapper .totals {
        width: 100%;
        min-width: 0;
        padding-top: 15px;
        border-top: 1px solid #e2e8f0;
    }

    .pos-wrapper .total-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        width: 100%;
        margin-bottom: 10px;
        color: #475569;
        font-size: 13px;
    }

    .pos-wrapper .total-row strong {
        color: #1e293b;
        white-space: nowrap;
    }

    .pos-wrapper .grand-total {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e2e8f0;
        color: #1e293b;
        font-size: 16px;
        font-weight: 700;
    }

    .pos-wrapper .grand-total span:last-child {
        color: #1e3a8a;
        white-space: nowrap;
    }

    /* PAYMENT */
    .pos-wrapper .payment-section {
        width: 100%;
        min-width: 0;
        margin-top: 18px;
        padding-top: 18px;
        border-top: 1px solid #e2e8f0;
    }

    .pos-wrapper .payment-section h4 {
        margin: 0 0 13px;
        color: #1e293b;
        font-size: 15px;
        font-weight: 700;
    }

    .pos-wrapper .payment-section select {
        height: 40px;
        cursor: pointer;
    }

    .pos-wrapper .amount-due,
    .pos-wrapper .change-display {
        width: 100%;
        min-width: 0;
        margin: 13px 0;
        padding: 10px;
        border-radius: 6px;
        font-size: 12px;
    }

    .pos-wrapper .amount-due {
        background: #f8fafc;
        color: #475569;
    }

    .pos-wrapper .change-display {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .pos-wrapper .amount-due span,
    .pos-wrapper .change-display span {
        float: right;
        font-weight: 700;
        white-space: nowrap;
    }

    .pos-wrapper .amount-due::after,
    .pos-wrapper .change-display::after {
        content: "";
        display: block;
        clear: both;
    }

    .pos-wrapper .complete-sale {
        display: flex !important;
        width: 100%;
        min-height: 44px;
        margin-top: 5px;
        font-size: 14px !important;
    }

    /* SPLIT PAYMENT */
    .pos-wrapper #splitPaymentSection {
        width: 100%;
        min-width: 0;
    }

    .pos-wrapper .split-info {
        width: 100%;
        margin-bottom: 12px;
        padding: 10px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 6px;
        color: #1e3a8a;
        font-size: 12px;
        line-height: 1.5;
    }

    .pos-wrapper .split-payment-summary {
        width: 100%;
        margin-top: 10px;
        padding: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 12px;
    }

    .pos-wrapper .split-summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .pos-wrapper .split-summary-row + .split-summary-row {
        margin-top: 5px;
    }

    .pos-wrapper .split-summary-row strong {
        white-space: nowrap;
    }

    .pos-wrapper .split-valid {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #166534;
    }

    .pos-wrapper .split-invalid {
        background: #fef2f2;
        border-color: #fecaca;
        color: #b91c1c;
    }

    /* RESPONSIVE */
    @media (max-width: 1250px) {
        .pos-wrapper .medicine-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .pos-wrapper .pos-grid {
            grid-template-columns: 1fr;
        }

        .pos-wrapper .medicine-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 750px) {
        .pos-wrapper .medicine-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .pos-wrapper .pos-header h2 {
            font-size: 20px;
        }

        .pos-wrapper .card {
            padding: 14px;
        }

        .pos-wrapper .medicine-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="pos-wrapper">

    <div class="pos-header">
        <div>
            <h2>Pharmacy POS</h2>
            <p>Dispense medicines and record sales.</p>
        </div>
    </div>

    <div id="errorAlert" class="alert alert-error"></div>
    <div id="successAlert" class="alert alert-success"></div>

    <div class="pos-grid">

        {{-- =====================================================
             LEFT - AVAILABLE MEDICINES
        ====================================================== --}}
        <div class="pos-left">
            <div class="card">

                <h3 class="card-title">Available Medicines</h3>

                <div class="search-box">
                    <input
                        type="text"
                        id="medicineSearch"
                        placeholder="Search medicine..."
                        autocomplete="off"
                    >
                </div>

                <div class="medicine-grid" id="medicineGrid">

                    @forelse($medicines as $medicine)

                        @php
                            $availableBatch = $medicine->batches->first();
                            $totalStock = $medicine->batches->sum('quantity');
                            $isPom = (bool) $medicine->prescription_required;
                        @endphp

                        <div
                            class="medicine-card"
                            data-name="{{ strtolower($medicine->name) }}"
                            data-medicine-id="{{ $medicine->id }}"
                        >

                            @if($isPom)
                                <span class="pom-badge">
                                    POM / PRESCRIPTION REQUIRED
                                </span>
                            @else
                                <span class="otc-badge">
                                    OTC / NON-POM
                                </span>
                            @endif

                            <div class="medicine-name">
                                {{ $medicine->name }}
                            </div>

                            @if($medicine->category)
                                <div class="medicine-meta">
                                    Category: {{ $medicine->category->name ?? 'N/A' }}
                                </div>
                            @endif

                            @if($medicine->dosage_form)
                                <div class="medicine-meta">
                                    Form: {{ $medicine->dosage_form }}
                                </div>
                            @endif

                            @if($medicine->strength)
                                <div class="medicine-meta">
                                    Strength: {{ $medicine->strength }}
                                </div>
                            @endif

                            @if($availableBatch)
                                <div class="medicine-price">
                                    KSh {{ number_format((float) $availableBatch->selling_price, 2) }}
                                </div>
                            @else
                                <div class="stock-warning">Out of stock</div>
                            @endif

                            @if($totalStock > 0)
                                <div class="stock-ok">
                                    Stock: {{ $totalStock }}
                                </div>
                            @else
                                <div class="stock-warning">
                                    Stock: 0
                                </div>
                            @endif

                            @if($availableBatch && $totalStock > 0)
                                <button
                                    type="button"
                                    class="btn btn-primary add-medicine"
                                    style="width:100%; margin-top:10px;"
                                    data-id="{{ $medicine->id }}"
                                    data-name="{{ $medicine->name }}"
                                    data-price="{{ $availableBatch->selling_price }}"
                                    data-stock="{{ $totalStock }}"
                                    data-pom="{{ $isPom ? 1 : 0 }}"
                                >
                                    Add to Cart
                                </button>
                            @else
                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    style="width:100%; margin-top:10px;"
                                    disabled
                                >
                                    Out of Stock
                                </button>
                            @endif

                        </div>

                    @empty

                        <div class="empty-cart">
                            No medicines available.
                        </div>

                    @endforelse

                </div>
            </div>
        </div>

        {{-- =====================================================
             RIGHT - CURRENT SALE
        ====================================================== --}}
        <div class="pos-right">
            <div class="card">

                <h3 class="card-title">Current Sale</h3>

                {{-- CUSTOMER DETAILS --}}
                <div class="customer-section">

                    <h4>Customer Details</h4>

                    <div class="form-group">
                        <label for="customerSearch">
                            Search Existing Customer
                        </label>

                        <div class="customer-search-wrapper">
                            <input
                                type="text"
                                id="customerSearch"
                                class="form-control"
                                placeholder="Search by name or phone..."
                                autocomplete="off"
                            >

                            <div
                                id="customerSearchResults"
                                class="customer-search-results"
                            ></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="customerName">
                            Customer Name
                        </label>

                        <input
                            type="text"
                            id="customerName"
                            class="form-control"
                            placeholder="Enter customer name"
                            autocomplete="off"
                        >
                    </div>

                    <div class="form-group">
                        <label for="customerPhone">
                            Phone Number
                        </label>

                        <input
                            type="tel"
                            id="customerPhone"
                            class="form-control"
                            placeholder="Enter phone number"
                            autocomplete="off"
                        >
                    </div>

                    <div class="customer-actions">
                        <button
                            type="button"
                            id="clearCustomer"
                            class="clear-customer"
                        >
                            Clear customer
                        </button>
                    </div>

                </div>

                {{-- CART --}}
                <div
                    class="cart-container"
                    id="cartContainer"
                >
                    <div class="empty-cart" id="emptyCart">
                        <strong>Cart is empty</strong>
                        <br>
                        Add medicines from the list.
                    </div>
                </div>

                {{-- TOTALS --}}
                <div class="totals">

                    <div class="total-row">
                        <span>Subtotal</span>
                        <strong id="subtotal">KSh 0.00</strong>
                    </div>

                    <div class="form-group">
                        <label for="discount">Discount</label>

                        <input
                            type="number"
                            id="discount"
                            value="0"
                            min="0"
                            step="0.01"
                            placeholder="Enter discount"
                        >
                    </div>

                    <div class="total-row grand-total">
                        <span>Total</span>
                        <span id="totalAmount">KSh 0.00</span>
                    </div>

                </div>

                {{-- PAYMENT --}}
                <div class="payment-section">

                    <h4>Payment</h4>

                    <div class="form-group">
                        <label for="paymentMethod">
                            Payment Method
                        </label>

                        <select id="paymentMethod">
                            <option value="cash">Cash</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="card">Credit/Debit Card</option>
                            <option value="split">Split Payment</option>
                        </select>
                    </div>

                    <div class="amount-due">
                        Amount Due:
                        <span id="amountDue">KSh 0.00</span>
                    </div>

                    {{-- NORMAL PAYMENT --}}
                    <div
                        class="form-group"
                        id="normalPaymentSection"
                    >
                        <label for="amountPaid">Amount Paid</label>

                        <input
                            type="number"
                            id="amountPaid"
                            min="0"
                            step="0.01"
                            placeholder="Enter amount paid"
                        >
                    </div>

                    {{-- SPLIT PAYMENT --}}
                    <div
                        id="splitPaymentSection"
                        style="display:none;"
                    >

                        <div class="split-info">
                            Enter the amount paid using each payment
                            method. The combined amount must equal the
                            Amount Due.
                        </div>

                        <div class="form-group">
                            <label for="cashAmount">Cash Amount</label>
                            <input
                                type="number"
                                id="cashAmount"
                                min="0"
                                step="0.01"
                                value="0"
                                placeholder="Enter cash amount"
                            >
                        </div>

                        <div class="form-group">
                            <label for="mpesaAmount">M-Pesa Amount</label>
                            <input
                                type="number"
                                id="mpesaAmount"
                                min="0"
                                step="0.01"
                                value="0"
                                placeholder="Enter M-Pesa amount"
                            >
                        </div>

                        <div class="form-group">
                            <label for="cardAmount">
                                Credit/Debit Card Amount
                            </label>
                            <input
                                type="number"
                                id="cardAmount"
                                min="0"
                                step="0.01"
                                value="0"
                                placeholder="Enter card amount"
                            >
                        </div>

                        <div
                            id="splitPaymentSummary"
                            class="split-payment-summary"
                        >
                            <div class="split-summary-row">
                                <span>Split Total</span>
                                <strong id="splitTotal">KSh 0.00</strong>
                            </div>

                            <div class="split-summary-row">
                                <span>Remaining</span>
                                <strong id="splitRemaining">KSh 0.00</strong>
                            </div>
                        </div>

                    </div>

                    <div class="change-display">
                        Change:
                        <span id="changeAmount">KSh 0.00</span>
                    </div>

                    <button
                        type="button"
                        id="completeSale"
                        class="btn btn-success complete-sale"
                    >
                        Complete Sale
                    </button>

                </div>

            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       STATE
       ========================================================= */

    let cart = [];
    let selectedCustomerId = null;
    let customerSearchTimer = null;


    /* =========================================================
       ELEMENTS
       ========================================================= */

    const medicineSearch =
        document.getElementById('medicineSearch');

    const medicineGrid =
        document.getElementById('medicineGrid');

    const cartContainer =
        document.getElementById('cartContainer');

    const subtotalElement =
        document.getElementById('subtotal');

    const discountInput =
        document.getElementById('discount');

    const totalAmountElement =
        document.getElementById('totalAmount');

    const amountDueElement =
        document.getElementById('amountDue');

    const paymentMethod =
        document.getElementById('paymentMethod');

    const normalPaymentSection =
        document.getElementById('normalPaymentSection');

    const splitPaymentSection =
        document.getElementById('splitPaymentSection');

    const amountPaidInput =
        document.getElementById('amountPaid');

    const cashAmountInput =
        document.getElementById('cashAmount');

    const mpesaAmountInput =
        document.getElementById('mpesaAmount');

    const cardAmountInput =
        document.getElementById('cardAmount');

    const splitTotalElement =
        document.getElementById('splitTotal');

    const splitRemainingElement =
        document.getElementById('splitRemaining');

    const splitPaymentSummary =
        document.getElementById('splitPaymentSummary');

    const changeAmountElement =
        document.getElementById('changeAmount');

    const completeSaleButton =
        document.getElementById('completeSale');

    const errorAlert =
        document.getElementById('errorAlert');

    const successAlert =
        document.getElementById('successAlert');

    const customerSearch =
        document.getElementById('customerSearch');

    const customerSearchResults =
        document.getElementById('customerSearchResults');

    const customerName =
        document.getElementById('customerName');

    const customerPhone =
        document.getElementById('customerPhone');

    const clearCustomerButton =
        document.getElementById('clearCustomer');


    /* =========================================================
       MONEY
       ========================================================= */

    function formatMoney(amount) {

        const value = Number(amount) || 0;

        return 'KSh ' + value.toLocaleString(
            'en-KE',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
    }


    /* =========================================================
       HTML ESCAPE
       ========================================================= */

    function escapeHtml(value) {

        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }


    /* =========================================================
       ALERTS
       ========================================================= */

    function showError(message) {

        successAlert.style.display = 'none';
        errorAlert.textContent = message;
        errorAlert.style.display = 'block';

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }


    function showSuccess(message) {

        errorAlert.style.display = 'none';
        successAlert.textContent = message;
        successAlert.style.display = 'block';

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }


    function clearAlerts() {

        errorAlert.style.display = 'none';
        successAlert.style.display = 'none';
    }


    /* =========================================================
       CUSTOMER SEARCH
       ========================================================= */

    function hideCustomerResults() {

        customerSearchResults.innerHTML = '';
        customerSearchResults.style.display = 'none';
    }


    function getCustomerResults(data) {

        if (Array.isArray(data)) {
            return data;
        }

        if (Array.isArray(data.customers)) {
            return data.customers;
        }

        if (Array.isArray(data.data)) {
            return data.data;
        }

        return [];
    }


    function renderCustomerResults(customers) {

        if (!customers.length) {

            customerSearchResults.innerHTML = `
                <div class="customer-search-status">
                    No matching customer found. You can enter the
                    customer details manually.
                </div>
            `;

            customerSearchResults.style.display = 'block';

            return;
        }


        let html = '';


        customers.forEach(function (customer) {

            const id =
                customer.id ?? customer.customer_id ?? '';

            const name =
                customer.name ??
                customer.customer_name ??
                '';

            const phone =
                customer.phone ??
                customer.customer_phone ??
                '';


            if (!id) {
                return;
            }


            html += `
                <button
                    type="button"
                    class="customer-result"
                    data-customer-id="${escapeHtml(id)}"
                    data-customer-name="${escapeHtml(name)}"
                    data-customer-phone="${escapeHtml(phone)}"
                >
                    <span class="customer-result-name">
                        ${escapeHtml(name || 'Unnamed customer')}
                    </span>

                    <span class="customer-result-phone">
                        ${escapeHtml(phone || 'No phone number')}
                    </span>
                </button>
            `;
        });


        if (!html) {

            customerSearchResults.innerHTML = `
                <div class="customer-search-status">
                    No usable customer records found.
                </div>
            `;

        } else {

            customerSearchResults.innerHTML = html;
        }


        customerSearchResults.style.display = 'block';
    }


    async function searchCustomers(query) {

        const trimmedQuery = query.trim();

        if (!trimmedQuery) {

            hideCustomerResults();

            return;
        }


        customerSearchResults.innerHTML = `
            <div class="customer-search-status">
                Searching...
            </div>
        `;

        customerSearchResults.style.display = 'block';


        try {

            const url =
                new URL(
                    "{{ route('customers.search') }}",
                    window.location.origin
                );

            /*
             * Send both names so this remains compatible with the
             * existing customer-search controller whether it reads
             * "customer" or "search".
             */
            url.searchParams.set('customer', trimmedQuery);
            url.searchParams.set('search', trimmedQuery);


            const response =
                await fetch(
                    url.toString(),
                    {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );


            if (!response.ok) {
                throw new Error('Unable to search customers.');
            }


            const data =
                await response.json();


            renderCustomerResults(
                getCustomerResults(data)
            );


        } catch (error) {

            console.error(
                'Customer search error:',
                error
            );

            customerSearchResults.innerHTML = `
                <div class="customer-search-status">
                    Unable to search customers. You can enter the
                    customer details manually.
                </div>
            `;

            customerSearchResults.style.display = 'block';
        }
    }


    customerSearch.addEventListener(
        'input',
        function () {

            clearTimeout(customerSearchTimer);

            const query = this.value;

            customerSearchTimer =
                setTimeout(
                    function () {
                        searchCustomers(query);
                    },
                    250
                );
        }
    );


    customerSearchResults.addEventListener(
        'click',
        function (event) {

            const result =
                event.target.closest(
                    '.customer-result'
                );

            if (!result) {
                return;
            }


            selectedCustomerId =
                result.dataset.customerId || null;

            customerName.value =
                result.dataset.customerName || '';

            customerPhone.value =
                result.dataset.customerPhone || '';

            customerSearch.value =
                result.dataset.customerName || '';

            hideCustomerResults();
        }
    );


    customerName.addEventListener(
        'input',
        function () {
            selectedCustomerId = null;
        }
    );


    customerPhone.addEventListener(
        'input',
        function () {
            selectedCustomerId = null;
        }
    );


    clearCustomerButton.addEventListener(
        'click',
        function () {

            selectedCustomerId = null;

            customerSearch.value = '';
            customerName.value = '';
            customerPhone.value = '';

            hideCustomerResults();

            clearAlerts();
        }
    );


    document.addEventListener(
        'click',
        function (event) {

            if (
                !event.target.closest(
                    '.customer-search-wrapper'
                )
            ) {
                hideCustomerResults();
            }
        }
    );


    /* =========================================================
       MEDICINE SEARCH
       ========================================================= */

    medicineSearch.addEventListener(
        'input',
        function () {

            const search =
                this.value
                    .trim()
                    .toLowerCase();


            const cards =
                medicineGrid.querySelectorAll(
                    '.medicine-card'
                );


            cards.forEach(
                function (card) {

                    const name =
                        card.dataset.name || '';

                    card.style.display =
                        name.includes(search)
                            ? ''
                            : 'none';
                }
            );
        }
    );


    /* =========================================================
       ADD MEDICINE
       ========================================================= */

    document.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest(
                    '.add-medicine'
                );

            if (!button) {
                return;
            }


            const medicineId =
                Number(button.dataset.id);

            const medicineName =
                button.dataset.name || '';

            const price =
                Number(button.dataset.price);

            const stock =
                Number(button.dataset.stock);

            const isPom =
                button.dataset.pom === '1';


            if (
                !medicineId ||
                !medicineName ||
                !Number.isFinite(price) ||
                price < 0 ||
                stock <= 0
            ) {

                showError(
                    'This medicine cannot be added to the cart.'
                );

                return;
            }


            const existing =
                cart.find(
                    item =>
                        item.medicine_id === medicineId
                );


            if (existing) {

                if (
                    existing.quantity >=
                    existing.stock
                ) {

                    showError(
                        `Only ${existing.stock} units of ${medicineName} are available.`
                    );

                    return;
                }

                existing.quantity++;

            } else {

                cart.push({

                    medicine_id: medicineId,

                    name: medicineName,

                    price: price,

                    stock: stock,

                    quantity: 1,

                    pom: isPom,

                    prescription:
                        isPom
                            ? {
                                prescription_number: '',
                                prescriber_name: '',
                                prescription_date: '',
                                quantity_prescribed: '',
                                dosage: '',
                                frequency: '',
                                duration: '',
                                notes: ''
                            }
                            : null
                });
            }


            clearAlerts();

            renderCart();

            updateTotals();
        }
    );


    /* =========================================================
       RENDER CART
       ========================================================= */

    function renderCart() {

        if (!cart.length) {

            cartContainer.innerHTML = `
                <div class="empty-cart">
                    <strong>Cart is empty</strong>
                    <br>
                    Add medicines from the list.
                </div>
            `;

            return;
        }


        let html = '';


        cart.forEach(
            function (item, index) {

                const itemTotal =
                    Number(item.price) *
                    Number(item.quantity);


                html += `
                    <div
                        class="cart-item"
                        data-index="${index}"
                    >

                        <div class="cart-item-header">

                            <div>

                                <div class="cart-item-name">
                                    ${escapeHtml(item.name)}
                                </div>

                                <div class="cart-item-price">
                                    ${formatMoney(item.price)}
                                    each

                                    ${
                                        item.pom
                                            ? '<span class="pom-badge">POM</span>'
                                            : '<span class="otc-badge">OTC</span>'
                                    }
                                </div>

                            </div>

                            <div class="cart-item-total">
                                ${formatMoney(itemTotal)}
                            </div>

                        </div>

                        <div class="cart-item-controls">

                            <button
                                type="button"
                                class="quantity-btn decrease-quantity"
                                data-index="${index}"
                                aria-label="Decrease quantity"
                            >
                                −
                            </button>

                            <span class="quantity-value">
                                ${item.quantity}
                            </span>

                            <button
                                type="button"
                                class="quantity-btn increase-quantity"
                                data-index="${index}"
                                aria-label="Increase quantity"
                            >
                                +
                            </button>

                            <button
                                type="button"
                                class="remove-item"
                                data-index="${index}"
                            >
                                Remove
                            </button>

                        </div>
                `;


                if (item.pom) {

                    const prescription =
                        item.prescription || {};


                    html += `
                        <div class="prescription-box">

                            <div class="prescription-title">
                                Prescription Details
                            </div>

                            <div class="prescription-grid">

                                <div class="form-group">

                                    <label>
                                        Prescription Number
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control prescription-field"
                                        data-index="${index}"
                                        data-field="prescription_number"
                                        value="${escapeHtml(prescription.prescription_number || '')}"
                                        placeholder="Prescription number"
                                        autocomplete="off"
                                    >
                                </div>

                                <div class="form-group">

                                    <label>
                                        Prescriber Name
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control prescription-field"
                                        data-index="${index}"
                                        data-field="prescriber_name"
                                        value="${escapeHtml(prescription.prescriber_name || '')}"
                                        placeholder="Doctor / prescriber"
                                        autocomplete="off"
                                    >
                                </div>

                                <div class="form-group">

                                    <label>
                                        Prescription Date
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="date"
                                        class="form-control prescription-field"
                                        data-index="${index}"
                                        data-field="prescription_date"
                                        value="${escapeHtml(prescription.prescription_date || '')}"
                                    >
                                </div>

                                <div class="form-group">

                                    <label>
                                        Quantity Prescribed
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="number"
                                        min="1"
                                        step="1"
                                        class="form-control prescription-field"
                                        data-index="${index}"
                                        data-field="quantity_prescribed"
                                        value="${escapeHtml(prescription.quantity_prescribed || '')}"
                                        placeholder="Quantity"
                                    >
                                </div>

                                <div class="form-group">

                                    <label>
                                        Dosage
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control prescription-field"
                                        data-index="${index}"
                                        data-field="dosage"
                                        value="${escapeHtml(prescription.dosage || '')}"
                                        placeholder="e.g. 500mg"
                                        autocomplete="off"
                                    >
                                </div>

                                <div class="form-group">

                                    <label>
                                        Frequency
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control prescription-field"
                                        data-index="${index}"
                                        data-field="frequency"
                                        value="${escapeHtml(prescription.frequency || '')}"
                                        placeholder="e.g. 3 times daily"
                                        autocomplete="off"
                                    >
                                </div>

                                <div class="form-group">

                                    <label>
                                        Duration
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control prescription-field"
                                        data-index="${index}"
                                        data-field="duration"
                                        value="${escapeHtml(prescription.duration || '')}"
                                        placeholder="e.g. 7 days"
                                        autocomplete="off"
                                    >
                                </div>

                                <div class="form-group">

                                    <label>
                                        Notes
                                    </label>

                                    <textarea
                                        class="form-control prescription-field"
                                        data-index="${index}"
                                        data-field="notes"
                                        placeholder="Additional prescription notes"
                                    >${escapeHtml(prescription.notes || '')}</textarea>
                                </div>

                            </div>
                        </div>
                    `;
                }


                html += `
                    </div>
                `;
            }
        );


        cartContainer.innerHTML = html;
    }


    /* =========================================================
       CART CONTROLS
       ========================================================= */

    document.addEventListener(
        'click',
        function (event) {

            const increaseButton =
                event.target.closest(
                    '.increase-quantity'
                );

            const decreaseButton =
                event.target.closest(
                    '.decrease-quantity'
                );

            const removeButton =
                event.target.closest(
                    '.remove-item'
                );


            if (increaseButton) {

                const index =
                    Number(
                        increaseButton.dataset.index
                    );

                const item =
                    cart[index];

                if (!item) {
                    return;
                }


                if (
                    item.quantity >=
                    item.stock
                ) {

                    showError(
                        `Only ${item.stock} units of ${item.name} are available.`
                    );

                    return;
                }


                item.quantity++;

                clearAlerts();
                renderCart();
                updateTotals();

                return;
            }


            if (decreaseButton) {

                const index =
                    Number(
                        decreaseButton.dataset.index
                    );

                const item =
                    cart[index];

                if (!item) {
                    return;
                }


                if (item.quantity > 1) {

                    item.quantity--;

                } else {

                    cart.splice(index, 1);
                }


                clearAlerts();
                renderCart();
                updateTotals();

                return;
            }


            if (removeButton) {

                const index =
                    Number(
                        removeButton.dataset.index
                    );


                if (
                    Number.isInteger(index) &&
                    cart[index]
                ) {

                    cart.splice(index, 1);
                }


                clearAlerts();
                renderCart();
                updateTotals();
            }
        }
    );


    /* =========================================================
       PRESCRIPTION FIELD CHANGES
       ========================================================= */

    document.addEventListener(
        'input',
        function (event) {

            const field =
                event.target.closest(
                    '.prescription-field'
                );

            if (!field) {
                return;
            }


            const index =
                Number(field.dataset.index);

            const fieldName =
                field.dataset.field;


            if (
                !cart[index] ||
                !cart[index].prescription
            ) {
                return;
            }


            cart[index].prescription[fieldName] =
                field.value;
        }
    );


    /* =========================================================
       TOTALS
       ========================================================= */

    function calculateSubtotal() {

        return cart.reduce(
            function (total, item) {

                return total +
                    (
                        Number(item.price) *
                        Number(item.quantity)
                    );

            },
            0
        );
    }


    function calculateDiscount() {

        let discount =
            Number(discountInput.value) || 0;

        const subtotal =
            calculateSubtotal();


        if (discount < 0) {

            discount = 0;
            discountInput.value = '0';
        }


        if (discount > subtotal) {

            discount = subtotal;
            discountInput.value =
                subtotal.toFixed(2);
        }


        return discount;
    }


    function calculateTotal() {

        return Math.max(
            0,
            calculateSubtotal() -
            calculateDiscount()
        );
    }


    function updateTotals() {

        const subtotal =
            calculateSubtotal();

        const discount =
            calculateDiscount();

        const total =
            Math.max(
                0,
                subtotal - discount
            );


        subtotalElement.textContent =
            formatMoney(subtotal);

        totalAmountElement.textContent =
            formatMoney(total);

        amountDueElement.textContent =
            formatMoney(total);

        updatePaymentDisplay();
    }


    discountInput.addEventListener(
        'input',
        function () {

            let discount =
                Number(this.value) || 0;

            const subtotal =
                calculateSubtotal();


            if (discount < 0) {

                this.value = '0';

            }


            if (discount > subtotal) {

                this.value =
                    subtotal.toFixed(2);
            }


            updateTotals();
        }
    );


    /* =========================================================
       PAYMENT
       ========================================================= */

    cashAmountInput.addEventListener(
        'input',
        updatePaymentDisplay
    );

    mpesaAmountInput.addEventListener(
        'input',
        updatePaymentDisplay
    );

    cardAmountInput.addEventListener(
        'input',
        updatePaymentDisplay
    );

    amountPaidInput.addEventListener(
        'input',
        updatePaymentDisplay
    );


    paymentMethod.addEventListener(
        'change',
        function () {

            updatePaymentFields();
            updatePaymentDisplay();
        }
    );


    function updatePaymentFields() {

        const method =
            paymentMethod.value;


        if (method === 'split') {

            normalPaymentSection.style.display =
                'none';

            splitPaymentSection.style.display =
                'block';

        } else {

            normalPaymentSection.style.display =
                'block';

            splitPaymentSection.style.display =
                'none';
        }
    }


    function calculateSplitPayment() {

        const cash =
            Number(cashAmountInput.value) || 0;

        const mpesa =
            Number(mpesaAmountInput.value) || 0;

        const card =
            Number(cardAmountInput.value) || 0;


        return {
            cash: cash,
            mpesa: mpesa,
            card: card,
            total: cash + mpesa + card
        };
    }


    function updatePaymentDisplay() {

        const total =
            calculateTotal();


        if (paymentMethod.value === 'split') {

            const split =
                calculateSplitPayment();

            const remaining =
                total - split.total;


            splitTotalElement.textContent =
                formatMoney(split.total);


            if (Math.abs(remaining) < 0.01) {

                splitRemainingElement.textContent =
                    formatMoney(0);

                splitPaymentSummary.classList
                    .remove('split-invalid');

                splitPaymentSummary.classList
                    .add('split-valid');

            } else {

                splitRemainingElement.textContent =
                    formatMoney(Math.abs(remaining));

                splitPaymentSummary.classList
                    .remove('split-valid');

                splitPaymentSummary.classList
                    .add('split-invalid');
            }


            changeAmountElement.textContent =
                formatMoney(0);

            return;
        }


        const amountPaid =
            Number(amountPaidInput.value) || 0;

        const change =
            Math.max(
                0,
                amountPaid - total
            );


        changeAmountElement.textContent =
            formatMoney(change);
    }


    /* =========================================================
       PRESCRIPTION VALIDATION
       ========================================================= */

    function validatePrescriptions() {

        for (
            let i = 0;
            i < cart.length;
            i++
        ) {

            const item =
                cart[i];


            if (!item.pom) {
                continue;
            }


            const prescription =
                item.prescription;


            if (!prescription) {

                return {
                    valid: false,
                    message:
                        `Prescription details are required for ${item.name}.`
                };
            }


            const requiredFields = [
                ['prescription_number', 'prescription number'],
                ['prescriber_name', 'prescriber name'],
                ['prescription_date', 'prescription date'],
                ['quantity_prescribed', 'prescribed quantity'],
                ['dosage', 'dosage'],
                ['frequency', 'frequency'],
                ['duration', 'duration']
            ];


            for (const [field, label] of requiredFields) {

                const value =
                    prescription[field];


                if (
                    field === 'quantity_prescribed'
                ) {

                    if (
                        !Number(value) ||
                        Number(value) <= 0
                    ) {

                        return {
                            valid: false,
                            message:
                                `Please enter the ${label} for ${item.name}.`
                        };
                    }

                } else if (
                    !String(value || '').trim()
                ) {

                    return {
                        valid: false,
                        message:
                            `Please enter the ${label} for ${item.name}.`
                    };
                }
            }
        }


        return {
            valid: true
        };
    }


    /* =========================================================
       COMPLETE SALE
       ========================================================= */
function getPrescriptionValue(fieldName) {

    const pomItem =
        cart.find(
            item =>
                item.pom &&
                item.prescription &&
                String(
                    item.prescription[fieldName] || ''
                ).trim()
        );

    if (!pomItem) {
        return null;
    }

    return String(
        pomItem.prescription[fieldName]
    ).trim() || null;
}
    completeSaleButton.addEventListener(
        'click',
        async function () {

            clearAlerts();


            /* CART */

            if (!cart.length) {

                showError(
                    'Please add at least one medicine to the cart.'
                );

                return;
            }


            /* TOTALS */

            const subtotal =
                calculateSubtotal();

            const discount =
                calculateDiscount();

            const total =
                calculateTotal();


            if (discount < 0) {

                showError(
                    'Discount cannot be negative.'
                );

                return;
            }


            if (discount > subtotal) {

                showError(
                    'Discount cannot be greater than the subtotal.'
                );

                return;
            }


            /* PRESCRIPTIONS */

            const prescriptionValidation =
                validatePrescriptions();


            if (!prescriptionValidation.valid) {

                showError(
                    prescriptionValidation.message
                );

                return;
            }


            /* PAYMENT */

            const selectedPaymentMethod =
                paymentMethod.value;


            if (
                ![
                    'cash',
                    'mpesa',
                    'card',
                    'split'
                ].includes(
                    selectedPaymentMethod
                )
            ) {

                showError(
                    'Please select a valid payment method.'
                );

                return;
            }


            let amountPaid = 0;
            let changeAmount = 0;
            let cashAmount = 0;
            let mpesaAmount = 0;
            let cardAmount = 0;


            /* NORMAL PAYMENT */

            if (
                selectedPaymentMethod !==
                'split'
            ) {

                amountPaid =
                    Number(
                        amountPaidInput.value
                    ) || 0;


                if (
                    total > 0 &&
                    amountPaid <= 0
                ) {

                    showError(
                        'Please enter the amount paid.'
                    );

                    amountPaidInput.focus();

                    return;
                }


                if (amountPaid < total) {

                    showError(
                        'Amount paid cannot be less than the total amount.'
                    );

                    amountPaidInput.focus();

                    return;
                }


                changeAmount =
                    Math.max(
                        0,
                        amountPaid - total
                    );


                if (
                    selectedPaymentMethod ===
                    'cash'
                ) {

                    cashAmount =
                        amountPaid;
                }


                if (
                    selectedPaymentMethod ===
                    'mpesa'
                ) {

                    mpesaAmount =
                        amountPaid;
                }


                if (
                    selectedPaymentMethod ===
                    'card'
                ) {

                    cardAmount =
                        amountPaid;
                }
            }


            /* SPLIT PAYMENT */

            if (
                selectedPaymentMethod ===
                'split'
            ) {

                const split =
                    calculateSplitPayment();


                cashAmount =
                    split.cash;

                mpesaAmount =
                    split.mpesa;

                cardAmount =
                    split.card;

                amountPaid =
                    split.total;


                if (
                    cashAmount < 0 ||
                    mpesaAmount < 0 ||
                    cardAmount < 0
                ) {

                    showError(
                        'Payment amounts cannot be negative.'
                    );

                    return;
                }


                const difference =
                    total - split.total;


                if (
                    Math.abs(difference) >
                    0.01
                ) {

                    if (difference > 0) {

                        showError(
                            `Split payment is short by ${formatMoney(difference)}.`
                        );

                    } else {

                        showError(
                            `Split payment exceeds the total by ${formatMoney(Math.abs(difference))}.`
                        );
                    }

                    return;
                }


                amountPaid = total;
                changeAmount = 0;
            }


            /* =================================================
               BUILD ITEMS

               IMPORTANT:
               The SaleController validates items.*.selling_price.
               Do NOT send "price" or "unit_price" here.
            ================================================== */

            const items =
    cart.map(
        function (item) {

            const prescription =
                item.pom && item.prescription
                    ? item.prescription
                    : {};

            return {

                medicine_id:
                    item.medicine_id,

                quantity:
                    Number(item.quantity),

                selling_price:
                    Number(item.price),

                dosage:
                    prescription.dosage || null,

                frequency:
                    prescription.frequency || null,

                duration:
                    prescription.duration || null,

                route:
                    prescription.route || null,

                instructions:
                    prescription.notes || null
            };
        }
    );


            /* =================================================
               FINAL PAYLOAD
            ================================================== */

            const payload = {

                customer_id:
                    selectedCustomerId || null,

                customer_name:
                    customerName.value.trim() || null,

                customer_phone:
                    customerPhone.value.trim() || null,

                subtotal:
                    subtotal,

                discount:
                    discount,

                total_amount:
                    total,

                payment_method:
                    selectedPaymentMethod,

                amount_paid:
                    amountPaid,

                cash_amount:
                    cashAmount,

                mpesa_amount:
                    mpesaAmount,

                card_amount:
                    cardAmount,

                change_amount:
                    changeAmount,

                items:
    items,

prescriber_name:
    getPrescriptionValue('prescriber_name'),

prescription_date:
    getPrescriptionValue('prescription_date'),

prescription_number:
    getPrescriptionValue('prescription_number')
            };


            console.log(
                'Sale payload:',
                payload
            );


            completeSaleButton.disabled =
                true;

            completeSaleButton.textContent =
                'Processing...';


            try {

                const csrfToken =
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        ?.getAttribute(
                            'content'
                        );


                if (!csrfToken) {

                    throw new Error(
                        'CSRF token not found. Please refresh the page and try again.'
                    );
                }


                const response =
                    await fetch(
                        "{{ route('sales.store') }}",
                        {
                            method: 'POST',

                            headers: {
                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest',

                                'X-CSRF-TOKEN':
                                    csrfToken
                            },

                            body:
                                JSON.stringify(
                                    payload
                                )
                        }
                    );


                let data = {};


                try {

                    data =
                        await response.json();

                } catch (jsonError) {

                    data = {};
                }


                if (!response.ok) {

                    let message =
                        'Unable to complete the sale.';


                    if (data.message) {

                        message =
                            data.message;
                    }


                    if (data.errors) {

                        const firstError =
                            Object.values(
                                data.errors
                            )[0];


                        if (
                            Array.isArray(firstError) &&
                            firstError.length
                        ) {

                            message =
                                firstError[0];
                        }
                    }


                    throw new Error(message);
                }


                showSuccess(
                    data.message ||
                    'Sale completed successfully.'
                );


                if (data.redirect) {

                    window.location.href =
                        data.redirect;

                    return;
                }


                if (data.receipt_url) {

                    window.location.href =
                        data.receipt_url;

                    return;
                }


                if (data.sale_id) {

                    window.location.href =
                        `/sales/${data.sale_id}/receipt`;

                    return;
                }


                /* RESET POS */

                cart = [];

                selectedCustomerId = null;

                customerSearch.value = '';
                customerName.value = '';
                customerPhone.value = '';

                discountInput.value = '0';

                amountPaidInput.value = '';

                cashAmountInput.value = '0';
                mpesaAmountInput.value = '0';
                cardAmountInput.value = '0';

                paymentMethod.value = 'cash';

                updatePaymentFields();
                renderCart();
                updateTotals();


            } catch (error) {

                console.error(
                    'Complete sale error:',
                    error
                );

                showError(
                    error.message ||
                    'An unexpected error occurred while completing the sale.'
                );


            } finally {

                completeSaleButton.disabled =
                    false;

                completeSaleButton.textContent =
                    'Complete Sale';
            }
        }
    );


    /* =========================================================
       INITIAL STATE
       ========================================================= */

    updatePaymentFields();
    renderCart();
    updateTotals();

});
</script>

@endsection
