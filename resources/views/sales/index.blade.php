@extends('layouts.app')

@section('title', 'Pharmacy POS')

@section('content')

<style>

    /* =========================================================
       POS WRAPPER
    ========================================================= */

    .pos-wrapper {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
        padding: 20px;
        box-sizing: border-box;
    }


    /* =========================================================
       HEADER
    ========================================================= */

    .pos-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .pos-header h2 {
        margin: 0;
        color: #1e293b;
        font-size: 26px;
        font-weight: 700;
    }

    .pos-header p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 14px;
    }


    /* =========================================================
       ALERTS
    ========================================================= */

    .alert {
        display: none;
        padding: 12px 15px;
        margin-bottom: 15px;
        border-radius: 7px;
        white-space: pre-line;
    }

    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }

    .alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }


    /* =========================================================
       POS GRID
    ========================================================= */

    .pos-grid {
        display: grid;
        grid-template-columns:
            minmax(0, 1fr)
            minmax(360px, 430px);
        gap: 20px;
        align-items: start;
    }

    .pos-left,
    .pos-right {
        min-width: 0;
    }


    /* =========================================================
       CARD
    ========================================================= */

    .card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 18px;
        box-shadow: 0 2px 7px rgba(15, 23, 42, 0.04);
        min-width: 0;
    }

    .card-title {
        margin: 0 0 15px;
        color: #1e293b;
        font-size: 18px;
        font-weight: 700;
    }


    /* =========================================================
       SEARCH
    ========================================================= */

    .search-box {
        margin-bottom: 15px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        box-sizing: border-box;
        min-height: 42px;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        outline: none;
        font-family: inherit;
        font-size: 14px;
    }

    .search-box input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }


    /* =========================================================
       MEDICINE GRID
    ========================================================= */

    .medicine-grid {
        display: grid;
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
        gap: 14px;
        width: 100%;
    }

    .medicine-card {
        position: relative;
        min-width: 0;
        display: flex;
        flex-direction: column;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        padding: 14px;
        background: #ffffff;
        transition:
            box-shadow 0.2s ease,
            transform 0.2s ease;
    }

    .medicine-card:hover {
        box-shadow:
            0 4px 12px rgba(15, 23, 42, 0.08);
        transform: translateY(-1px);
    }

    .medicine-name {
        margin-top: 8px;
        margin-bottom: 7px;
        color: #1e293b;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .medicine-meta {
        margin-top: 3px;
        color: #64748b;
        font-size: 12px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .medicine-price {
        margin-top: 10px;
        color: #1e3a8a;
        font-size: 16px;
        font-weight: 700;
        line-height: 1.4;
    }

    .stock-ok {
        margin-top: 5px;
        color: #15803d;
        font-size: 12px;
        font-weight: 600;
    }

    .stock-warning {
        margin-top: 10px;
        color: #dc2626;
        font-size: 12px;
        font-weight: 600;
    }


    /* =========================================================
       POM / OTC BADGES
    ========================================================= */

    .pom-badge,
    .otc-badge {
        align-self: flex-start;
        display: inline-block;
        max-width: 100%;
        padding: 4px 7px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: 700;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .pom-badge {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .otc-badge {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }


    /* =========================================================
       BUTTONS
    ========================================================= */

    .pos-wrapper button {
        font-family: inherit;
        appearance: none;
        -webkit-appearance: none;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 8px 13px;
        border: none;
        border-radius: 7px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.3;
        text-align: center;
        text-decoration: none;
        transition: 0.2s ease;
        box-sizing: border-box;
    }

    .btn-primary {
        background: #2563eb;
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

    .btn-secondary {
        background: #64748b;
        color: #ffffff;
    }

    .btn-secondary:hover {
        background: #475569;
    }

    .btn-success {
        background: #16a34a;
        color: #ffffff;
    }

    .btn-success:hover {
        background: #15803d;
    }

    .btn-danger {
        background: #dc2626;
        color: #ffffff;
    }

    .btn-danger:hover {
        background: #b91c1c;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }


    /* =========================================================
       SALE TYPE
    ========================================================= */

    .sale-type-section {
        margin-bottom: 20px;
        padding-bottom: 18px;
        border-bottom: 1px solid #e5e7eb;
    }

    .sale-type-section h3 {
        margin: 0 0 10px;
        color: #1e293b;
        font-size: 16px;
    }

    .sale-type-select {
        width: 100%;
        min-height: 42px;
        padding: 9px 11px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        background: #ffffff;
        font-family: inherit;
        font-size: 14px;
        outline: none;
    }

    .sale-type-select:focus {
        border-color: #2563eb;
        box-shadow:
            0 0 0 3px rgba(37, 99, 235, 0.1);
    }


    /* =========================================================
       HOSPITAL PRESCRIPTION
    ========================================================= */

    .hospital-prescription-section {
        display: none;
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        background: #eff6ff;
    }

    .hospital-prescription-section h4 {
        margin: 0 0 10px;
        color: #1e40af;
        font-size: 15px;
    }

    .hospital-prescription-search {
        display: flex;
        gap: 8px;
    }

    .hospital-prescription-search input {
        flex: 1;
        min-width: 0;
        min-height: 40px;
        padding: 9px 11px;
        border: 1px solid #bfdbfe;
        border-radius: 7px;
        box-sizing: border-box;
        font-family: inherit;
    }

    .hospital-prescription-result {
        display: none;
        margin-top: 12px;
        padding: 12px;
        border-radius: 7px;
        background: #ffffff;
        border: 1px solid #bfdbfe;
    }

    .hospital-prescription-result strong {
        display: block;
        margin-bottom: 5px;
        color: #1e3a8a;
    }

    .hospital-prescription-result span {
        display: block;
        color: #475569;
        font-size: 13px;
        margin-top: 3px;
    }


    /* =========================================================
       EXTERNAL PRESCRIPTION
    ========================================================= */

    .external-prescription-section {
        display: none;
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #fcd34d;
        border-radius: 8px;
        background: #fffbeb;
    }

    .external-prescription-section h4 {
        margin: 0 0 6px;
        color: #92400e;
        font-size: 15px;
    }

    .external-prescription-section p {
        margin: 0 0 12px;
        color: #78350f;
        font-size: 12px;
        line-height: 1.5;
    }

    .external-prescription-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .external-prescription-actions .btn {
        flex: 1;
        min-width: 150px;
    }

    .external-prescription-file {
        display: none;
    }

    .external-prescription-preview {
        display: none;
        margin-top: 15px;
        padding: 10px;
        border: 1px solid #fde68a;
        border-radius: 7px;
        background: #ffffff;
    }

    .external-prescription-preview img {
        display: block;
        width: 100%;
        max-height: 360px;
        object-fit: contain;
        border-radius: 6px;
        background: #f8fafc;
    }

    .external-prescription-preview .pdf-preview {
        padding: 12px;
        color: #78350f;
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .external-prescription-preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .external-prescription-preview-header strong {
        color: #92400e;
        font-size: 13px;
    }


    /* =========================================================
       CUSTOMER
    ========================================================= */

    .customer-section {
        margin-bottom: 20px;
    }

    .customer-section h4 {
        margin: 0 0 12px;
        color: #1e293b;
        font-size: 15px;
    }

    .form-grid {
        display: grid;
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .form-group {
        min-width: 0;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
    }

    .form-control {
        width: 100%;
        box-sizing: border-box;
        min-height: 40px;
        padding: 8px 10px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        font-family: inherit;
        font-size: 13px;
        outline: none;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow:
            0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    textarea.form-control {
        min-height: 75px;
        resize: vertical;
    }

    .customer-search-wrapper {
        position: relative;
    }

    .customer-results {
        display: none;
        position: absolute;
        z-index: 20;
        left: 0;
        right: 0;
        top: calc(100% + 4px);
        max-height: 230px;
        overflow-y: auto;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        box-shadow:
            0 8px 20px rgba(15, 23, 42, 0.12);
    }

    .customer-result {
        display: flex;
        flex-direction: column;
        gap: 3px;
        padding: 10px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9;
    }

    .customer-result:last-child {
        border-bottom: none;
    }

    .customer-result:hover {
        background: #f8fafc;
    }

    .customer-result strong {
        color: #1e293b;
        font-size: 13px;
    }

    .customer-result span {
        color: #64748b;
        font-size: 12px;
    }


    /* =========================================================
       CART
    ========================================================= */

    .cart-section {
        margin-top: 10px;
    }

    .cart-section h3 {
        margin: 0 0 12px;
        color: #1e293b;
        font-size: 16px;
    }

    .empty-cart {
        padding: 25px;
        text-align: center;
        color: #64748b;
        background: #f8fafc;
        border-radius: 7px;
    }

    .cart-item {
        margin-bottom: 12px;
        padding: 13px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #ffffff;
    }

    .cart-item-header {
        display: grid;
        grid-template-columns:
            minmax(0, 1fr)
            auto;
        gap: 10px;
        align-items: start;
    }

    .cart-item-name {
        color: #1e293b;
        font-size: 14px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .cart-item-price {
        color: #475569;
        font-size: 12px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .cart-item-total {
        color: #1e3a8a;
        font-size: 14px;
        font-weight: 700;
        white-space: nowrap;
    }

    .cart-item-controls {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-top: 10px;
    }

    .quantity-btn {
        width: 34px;
        height: 34px;
        padding: 0;
        border: none;
        border-radius: 6px;
        background: #e2e8f0;
        color: #1e293b;
        font-size: 18px;
        cursor: pointer;
    }

    .quantity-btn:hover {
        background: #cbd5e1;
    }

    .quantity-input {
        width: 75px;
        height: 34px;
        padding: 5px 8px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        box-sizing: border-box;
        text-align: center;
        font-family: inherit;
        font-size: 13px;
    }

    .remove-item {
        min-height: 34px;
        padding: 6px 10px;
        border: none;
        border-radius: 6px;
        background: #fee2e2;
        color: #b91c1c;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
    }

    .remove-item:hover {
        background: #fecaca;
    }


    /* =========================================================
       POM PRESCRIPTION DETAILS
    ========================================================= */

    .prescription-box {
        margin-top: 12px;
        padding: 12px;
        border: 1px solid #fde68a;
        border-radius: 7px;
        background: #fffbeb;
    }

    .prescription-title {
        margin-bottom: 10px;
        color: #92400e;
        font-size: 13px;
        font-weight: 700;
    }

    .prescription-grid {
        display: grid;
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
        gap: 9px;
    }

    .prescription-grid .form-group {
        min-width: 0;
    }

    .prescription-grid label {
        display: block;
        width: 100%;
        margin-bottom: 5px;
        color: #92400e;
        font-size: 11px;
        font-weight: 600;
    }

    .prescription-grid .form-control {
        display: block;
        width: 100%;
        max-width: 100%;
        min-width: 0;
        height: 38px;
        padding: 8px 10px;
        font-size: 12px;
    }

    .prescription-grid textarea.form-control {
        height: auto;
        min-height: 65px;
        resize: vertical;
    }

    .prescription-grid .notes-field {
        grid-column: 1 / -1;
    }


    /* =========================================================
       CART BADGES
    ========================================================= */

    .cart-item-price .pom-badge,
    .cart-item-price .otc-badge {
        display: inline-block;
        margin-left: 4px;
        margin-bottom: 0;
    }


    /* =========================================================
       TOTALS
    ========================================================= */

    .cart-summary {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #e5e7eb;
    }

    .cart-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        color: #475569;
        font-size: 14px;
    }

    .cart-summary-row strong {
        color: #1e293b;
    }

    .cart-summary input {
        width: 130px;
        box-sizing: border-box;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding-top: 12px;
        border-top: 2px solid #e5e7eb;
        font-size: 18px;
    }

    .total-row strong {
        color: #1e3a8a;
        font-size: 20px;
    }


    /* =========================================================
       PAYMENT
    ========================================================= */

    .payment-section {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .payment-section h4 {
        margin: 0 0 12px;
        color: #1e293b;
        font-size: 16px;
    }

    .payment-methods {
        display: grid;
        grid-template-columns:
            repeat(4, minmax(0, 1fr));
        gap: 8px;
        margin-bottom: 12px;
    }

    .payment-method {
        position: relative;
        padding: 10px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        background: #ffffff;
        cursor: pointer;
        text-align: center;
        font-size: 12px;
        font-weight: 600;
    }

    .payment-method input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .payment-method.active {
        border-color: #2563eb;
        background: #eff6ff;
        color: #1e40af;
    }

    .single-payment-field,
    .split-payment-fields {
        margin-top: 10px;
    }

    .split-payment-fields {
        display: none;
    }

    .payment-section label {
        display: block;
        margin-top: 12px;
        margin-bottom: 6px;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
    }

    .payment-section select,
    .payment-section input {
        width: 100%;
    }

    .split-summary {
        margin-top: 12px;
        padding: 10px;
        border-radius: 7px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        font-size: 12px;
    }

    .split-summary-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .split-summary-row + .split-summary-row {
        margin-top: 5px;
    }

    .split-summary-row strong {
        white-space: nowrap;
    }

    .split-valid {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #166534;
    }

    .split-invalid {
        background: #fef2f2;
        border-color: #fecaca;
        color: #b91c1c;
    }

    .change-display {
        display: none;
        margin-top: 15px;
        padding: 15px;
        background: #f0fdf4;
        border-radius: 7px;
        justify-content: space-between;
        align-items: center;
    }

    .change-display strong {
        color: #16a34a;
    }


    /* =========================================================
       COMPLETE SALE
    ========================================================= */

    .complete-sale-btn {
        width: 100%;
        margin-top: 20px;
        min-height: 46px;
        padding: 14px;
        font-size: 16px;
        font-weight: bold;
    }


    /* =========================================================
       CAMERA MODAL
    ========================================================= */

    .camera-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 23, 42, 0.78);
        box-sizing: border-box;
    }

    .camera-modal.is-open {
        display: flex;
    }

    .camera-modal-content {
        width: 100%;
        max-width: 760px;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow:
            0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .camera-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 15px 18px;
        border-bottom: 1px solid #e2e8f0;
    }

    .camera-modal-header h3 {
        margin: 0;
        color: #1e293b;
        font-size: 17px;
    }

    .camera-close {
        width: 34px;
        height: 34px;
        padding: 0;
        border: none;
        border-radius: 6px;
        background: #f1f5f9;
        color: #334155;
        cursor: pointer;
        font-size: 20px;
        line-height: 1;
    }

    .camera-close:hover {
        background: #e2e8f0;
    }

    .camera-modal-body {
        padding: 18px;
    }

    .camera-video-wrapper {
        position: relative;
        width: 100%;
        min-height: 300px;
        overflow: hidden;
        border-radius: 10px;
        background: #0f172a;
    }

    .camera-video {
        display: block;
        width: 100%;
        max-height: 55vh;
        min-height: 300px;
        object-fit: cover;
        background: #0f172a;
    }

    .camera-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 25px;
        color: #cbd5e1;
        text-align: center;
        font-size: 14px;
    }

    .camera-preview-wrapper {
        display: none;
        width: 100%;
    }

    .camera-preview {
        display: block;
        width: 100%;
        max-height: 55vh;
        object-fit: contain;
        border-radius: 8px;
        background: #0f172a;
    }

    .camera-canvas {
        display: none;
    }

    .camera-status {
        min-height: 20px;
        margin-top: 10px;
        color: #64748b;
        font-size: 13px;
        line-height: 1.5;
    }

    .camera-status.error {
        color: #b91c1c;
    }

    .camera-modal-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 15px;
    }

    .camera-modal-actions .btn {
        flex: 1;
        min-width: 140px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1250px) {

        .medicine-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }


    @media (max-width: 1050px) {

        .pos-grid {
            grid-template-columns:
                minmax(0, 1fr)
                350px;
            gap: 15px;
        }

    }


    @media (max-width: 900px) {

        .pos-grid {
            grid-template-columns: 1fr;
        }

        .medicine-grid {
            grid-template-columns:
                repeat(3, minmax(0, 1fr));
        }

    }


    @media (max-width: 750px) {

        .medicine-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .payment-methods {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }


    @media (max-width: 600px) {

        .pos-wrapper {
            padding: 10px;
        }

        .pos-header h2 {
            font-size: 20px;
        }

        .card {
            padding: 14px;
        }

        .medicine-grid {
            grid-template-columns: 1fr;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: auto;
        }

        .prescription-grid {
            grid-template-columns: 1fr;
        }

        .prescription-grid .notes-field {
            grid-column: auto;
        }

        .hospital-prescription-search {
            flex-direction: column;
        }

        .camera-modal {
            padding: 10px;
        }

        .camera-modal-content {
            max-height: calc(100vh - 20px);
        }

        .camera-modal-body {
            padding: 12px;
        }

        .camera-video-wrapper,
        .camera-video {
            min-height: 240px;
        }

    }


    @media (max-width: 400px) {

        .cart-item-header {
            grid-template-columns: 1fr;
        }

        .cart-item-total {
            justify-self: start;
        }

        .quantity-row {
            width: 100%;
        }

        .payment-methods {
            grid-template-columns: 1fr;
        }

    }

</style>


<div class="pos-wrapper">

    <div class="pos-header">

        <div>

            <h2>
                Pharmacy POS
            </h2>

            <p>
                Dispense medicines and record sales.
            </p>

        </div>

    </div>


    <div
        id="errorAlert"
        class="alert alert-error"
    ></div>

    <div
        id="successAlert"
        class="alert alert-success"
    ></div>


    <div class="pos-grid">

        <div class="pos-left">

            <div class="card">

                <h3 class="card-title">
                    Available Medicines
                </h3>


                <div class="search-box">

                    <input
                        type="text"
                        id="medicineSearch"
                        placeholder="Search medicine..."
                        autocomplete="off"
                    >

                </div>


                <div
                    class="medicine-grid"
                    id="medicineGrid"
                >

                    @forelse($medicines as $medicine)

                        @php

                            $availableBatch =
                                $medicine->batches->first();

                            $totalStock =
                                $medicine->batches->sum('quantity');

                            $isPom =
                                (bool) $medicine->prescription_required;

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
                                    Category:
                                    {{ $medicine->category->name ?? 'N/A' }}
                                </div>

                            @endif


                            @if($medicine->dosage_form)

                                <div class="medicine-meta">
                                    Form:
                                    {{ $medicine->dosage_form }}
                                </div>

                            @endif


                            @if($medicine->strength)

                                <div class="medicine-meta">
                                    Strength:
                                    {{ $medicine->strength }}
                                </div>

                            @endif


                            @if($availableBatch)

                                <div class="medicine-price">
                                    KSh
                                    {{ number_format((float) $availableBatch->selling_price, 2) }}
                                </div>

                            @else

                                <div class="stock-warning">
                                    Out of stock
                                </div>

                            @endif


                            @if($totalStock > 0)

                                <div class="stock-ok">
                                    Stock:
                                    {{ $totalStock }}
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

                <div
                    id="noResults"
                    style="
                        display:none;
                        margin-top:15px;
                        padding:20px;
                        text-align:center;
                        color:#64748b;
                    "
                >
                    No medicines found.
                </div>

            </div>

        </div>


        <div class="pos-right">

            <div class="card">

                <div class="sale-type-section">

                    <h3>
                        Customer / Sale Type
                    </h3>

                    <select
                        id="saleType"
                        class="sale-type-select"
                    >

                        <option value="walk_in">
                            Walk-in Customer
                        </option>

                        <option value="hospital_prescription">
                            Hospital Prescription
                        </option>

                        <option value="external_prescription">
                            External Prescription
                        </option>

                    </select>

                </div>


                <div
                    id="hospitalPrescriptionSection"
                    class="hospital-prescription-section"
                >

                    <h4>
                        Verify Hospital Prescription
                    </h4>

                    <div class="hospital-prescription-search">

                        <input
                            type="text"
                            id="hospitalPrescriptionNumber"
                            placeholder="Enter prescription number..."
                            autocomplete="off"
                        >

                        <button
                            type="button"
                            id="verifyHospitalPrescription"
                            class="btn btn-primary"
                        >
                            Verify
                        </button>

                    </div>

                    <div
                        id="hospitalPrescriptionStatus"
                        style="
                            margin-top:8px;
                            color:#64748b;
                            font-size:12px;
                        "
                    ></div>

                    <div
                        id="hospitalPrescriptionResult"
                        class="hospital-prescription-result"
                    ></div>

                </div>


                <div
                    id="externalPrescriptionSection"
                    class="external-prescription-section"
                >

                    <h4>
                        External Prescription
                    </h4>

                    <p>
                        Take a photo of the prescription using the
                        computer camera, or choose a prescription file
                        from the device.
                    </p>


                    <div class="external-prescription-actions">

                        <button
                            type="button"
                            id="takePrescriptionPhoto"
                            class="btn btn-primary"
                        >
                            📷 Take Photo
                        </button>


                        <button
                            type="button"
                            id="choosePrescriptionFile"
                            class="btn btn-secondary"
                        >
                            📁 Choose File
                        </button>

                    </div>


                    <input
                        type="file"
                        id="externalPrescriptionFile"
                        class="external-prescription-file"
                        accept="image/*,.pdf"
                    >


                    <div
                        id="externalPrescriptionPreview"
                        class="external-prescription-preview"
                    ></div>


                    <button
                        type="button"
                        id="removePrescriptionFile"
                        class="btn btn-danger"
                        style="
                            display:none;
                            margin-top:10px;
                            width:100%;
                        "
                    >
                        Remove Prescription
                    </button>

                </div>


               <div
    id="customerSection"
    class="customer-section"
>

                    <h4>
                        Customer Information
                    </h4>

                    <div class="form-grid">

                        <div
                            class="form-group full"
                            id="customerSearchGroup"
                        >

                            <label for="customerSearch">
                                Search Existing Customer
                            </label>

                            <div class="customer-search-wrapper">

                                <input
                                    type="text"
                                    id="customerSearch"
                                    class="form-control"
                                    placeholder="Search by customer name or phone..."
                                    autocomplete="off"
                                >

                                <div
                                    id="customerResults"
                                    class="customer-results"
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
                                placeholder="Customer name"
                            >

                        </div>


                        <div class="form-group">

                            <label for="customerPhone">
                                Phone Number
                            </label>

                            <input
                                type="text"
                                id="customerPhone"
                                class="form-control"
                                placeholder="Phone number"
                            >

                        </div>

                    </div>

                </div>


                <div class="cart-section">

                    <h3>
                        Cart
                    </h3>

                    <div id="cartItems">

                        <p class="empty-cart">
                            Your cart is empty.
                        </p>

                    </div>

                </div>


                <div class="cart-summary">

                    <div class="cart-summary-row">

                        <span>
                            Subtotal:
                        </span>

                        <strong id="subtotalDisplay">
                            KSh 0.00
                        </strong>

                    </div>


                    <div class="cart-summary-row">

                        <label
                            for="discount"
                            style="margin:0;"
                        >
                            Discount:
                        </label>

                        <input
                            type="number"
                            id="discount"
                            class="form-control"
                            value="0"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="total-row">

                        <span>
                            Total:
                        </span>

                        <strong id="totalDisplay">
                            KSh 0.00
                        </strong>

                    </div>

                </div>


                <div class="payment-section">

                    <h4>
                        Payment
                    </h4>


                    <label>
                        Payment Method
                    </label>


                    <div class="payment-methods">

                        <label class="payment-method active">

                            <input
                                type="radio"
                                name="payment_method"
                                value="cash"
                                checked
                            >

                            Cash

                        </label>


                        <label class="payment-method">

                            <input
                                type="radio"
                                name="payment_method"
                                value="mpesa"
                            >

                            M-Pesa

                        </label>


                        <label class="payment-method">

                            <input
                                type="radio"
                                name="payment_method"
                                value="card"
                            >

                            Card

                        </label>


                        <label class="payment-method">

                            <input
                                type="radio"
                                name="payment_method"
                                value="split"
                            >

                            Split

                        </label>

                    </div>


                    <div
                        id="singlePaymentField"
                        class="single-payment-field"
                    >

                        <label for="amountPaid">
                            Amount Paid
                        </label>

                        <input
                            type="number"
                            id="amountPaid"
                            class="form-control"
                            min="0"
                            step="0.01"
                            placeholder="Enter amount paid"
                        >

                    </div>


                    <div
                        id="splitPaymentFields"
                        class="split-payment-fields"
                    >

                        <label for="cashAmount">
                            Cash Amount
                        </label>

                        <input
                            type="number"
                            id="cashAmount"
                            class="form-control"
                            min="0"
                            step="0.01"
                            value="0"
                        >


                        <label for="mpesaAmount">
                            M-Pesa Amount
                        </label>

                        <input
                            type="number"
                            id="mpesaAmount"
                            class="form-control"
                            min="0"
                            step="0.01"
                            value="0"
                        >


                        <label for="cardAmount">
                            Card Amount
                        </label>

                        <input
                            type="number"
                            id="cardAmount"
                            class="form-control"
                            min="0"
                            step="0.01"
                            value="0"
                        >


                        <div
                            id="splitPaymentSummary"
                            class="split-summary"
                        >

                            <div class="split-summary-row">

                                <span>
                                    Total:
                                </span>

                                <strong id="splitTotal">
                                    KSh 0.00
                                </strong>

                            </div>


                            <div class="split-summary-row">

                                <span>
                                    Paid:
                                </span>

                                <strong id="splitPaid">
                                    KSh 0.00
                                </strong>

                            </div>


                            <div class="split-summary-row">

                                <span>
                                    Difference:
                                </span>

                                <strong id="splitDifference">
                                    KSh 0.00
                                </strong>

                            </div>

                        </div>

                    </div>


                    <div
                        id="changeDisplay"
                        class="change-display"
                    >

                        <span>
                            Change
                        </span>

                        <strong>
                            KSh 0.00
                        </strong>

                    </div>


                    <button
                        type="button"
                        id="completeSale"
                        class="btn btn-success complete-sale-btn"
                    >
                        Complete Sale
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>


<div
    id="prescriptionCameraModal"
    class="camera-modal"
    aria-hidden="true"
>

    <div
        class="camera-modal-content"
        role="dialog"
        aria-modal="true"
        aria-labelledby="prescriptionCameraTitle"
    >

        <div class="camera-modal-header">

            <h3 id="prescriptionCameraTitle">
                Take Prescription Photo
            </h3>

            <button
                type="button"
                id="closePrescriptionCamera"
                class="camera-close"
                aria-label="Close camera"
            >
                ×
            </button>

        </div>


        <div class="camera-modal-body">

            <div class="camera-video-wrapper">

                <video
                    id="prescriptionCameraVideo"
                    class="camera-video"
                    autoplay
                    playsinline
                    muted
                ></video>


                <div
                    id="cameraPlaceholder"
                    class="camera-placeholder"
                >
                    Allow camera access when your browser asks
                    for permission.
                </div>


                <div
                    id="cameraPreviewWrapper"
                    class="camera-preview-wrapper"
                >

                    <img
                        id="prescriptionCameraPreview"
                        class="camera-preview"
                        src=""
                        alt="Captured prescription preview"
                    >

                </div>

            </div>


            <canvas
                id="prescriptionCameraCanvas"
                class="camera-canvas"
            ></canvas>


            <div
                id="cameraStatus"
                class="camera-status"
            >
                Starting camera...
            </div>


            <div class="camera-modal-actions">

                <button
                    type="button"
                    id="capturePrescriptionPhoto"
                    class="btn btn-primary"
                >
                    📸 Capture Photo
                </button>


                <button
                    type="button"
                    id="retakePrescriptionPhoto"
                    class="btn btn-secondary"
                    style="display:none;"
                >
                    ↻ Retake Photo
                </button>


                <button
                    type="button"
                    id="usePrescriptionPhoto"
                    class="btn btn-success"
                    style="display:none;"
                >
                    ✓ Use Photo
                </button>


                <button
                    type="button"
                    id="cancelPrescriptionCamera"
                    class="btn btn-danger"
                >
                    Cancel
                </button>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /* =====================================================
           ELEMENTS
        ====================================================== */

        const medicineSearch =
            document.getElementById(
                'medicineSearch'
            );

        const medicineGrid =
            document.getElementById(
                'medicineGrid'
            );

        const noResults =
            document.getElementById(
                'noResults'
            );

        const cartItems =
            document.getElementById(
                'cartItems'
            );

        const saleTypeSelect =
            document.getElementById(
                'saleType'
            );

        const hospitalPrescriptionSection =
            document.getElementById(
                'hospitalPrescriptionSection'
            );

        const externalPrescriptionSection =
            document.getElementById(
                'externalPrescriptionSection'
            );

        const hospitalPrescriptionNumber =
            document.getElementById(
                'hospitalPrescriptionNumber'
            );

        const verifyHospitalPrescription =
            document.getElementById(
                'verifyHospitalPrescription'
            );

        const hospitalPrescriptionStatus =
            document.getElementById(
                'hospitalPrescriptionStatus'
            );

        const hospitalPrescriptionResult =
            document.getElementById(
                'hospitalPrescriptionResult'
            );

        const customerSearch =
            document.getElementById(
                'customerSearch'
            );

        const customerResults =
            document.getElementById(
                'customerResults'
            );

        const customerName =
            document.getElementById(
                'customerName'
            );

                       const customerPhone =
            document.getElementById(
                'customerPhone'
            );

                    const customerSection =
            document.getElementById(
                'customerSection'
            );

        const discountInput =
            document.getElementById(
                'discount'
            );

        const subtotalDisplay =
            document.getElementById(
                'subtotalDisplay'
            );

        const totalDisplay =
            document.getElementById(
                'totalDisplay'
            );

        const amountPaidInput =
            document.getElementById(
                'amountPaid'
            );

        const cashAmountInput =
            document.getElementById(
                'cashAmount'
            );

        const mpesaAmountInput =
            document.getElementById(
                'mpesaAmount'
            );

        const cardAmountInput =
            document.getElementById(
                'cardAmount'
            );

        const splitPaymentFields =
            document.getElementById(
                'splitPaymentFields'
            );

        const singlePaymentField =
            document.getElementById(
                'singlePaymentField'
            );

        const changeDisplay =
            document.getElementById(
                'changeDisplay'
            );

        const splitPaymentSummary =
            document.getElementById(
                'splitPaymentSummary'
            );

        const splitTotal =
            document.getElementById(
                'splitTotal'
            );

        const splitPaid =
            document.getElementById(
                'splitPaid'
            );

        const splitDifference =
            document.getElementById(
                'splitDifference'
            );

        const completeSaleButton =
            document.getElementById(
                'completeSale'
            );

        const errorAlert =
            document.getElementById(
                'errorAlert'
            );

        const successAlert =
            document.getElementById(
                'successAlert'
            );


        /* =====================================================
           EXTERNAL PRESCRIPTION ELEMENTS
        ====================================================== */

        const takePrescriptionPhoto =
            document.getElementById(
                'takePrescriptionPhoto'
            );

        const choosePrescriptionFile =
            document.getElementById(
                'choosePrescriptionFile'
            );

        const externalPrescriptionFileInput =
            document.getElementById(
                'externalPrescriptionFile'
            );

        const externalPrescriptionPreview =
            document.getElementById(
                'externalPrescriptionPreview'
            );

        const removePrescriptionFile =
            document.getElementById(
                'removePrescriptionFile'
            );


        /* =====================================================
           CAMERA ELEMENTS
        ====================================================== */

        const prescriptionCameraModal =
            document.getElementById(
                'prescriptionCameraModal'
            );

        const prescriptionCameraVideo =
            document.getElementById(
                'prescriptionCameraVideo'
            );

        const prescriptionCameraCanvas =
            document.getElementById(
                'prescriptionCameraCanvas'
            );

        const prescriptionCameraPreview =
            document.getElementById(
                'prescriptionCameraPreview'
            );

        const cameraPreviewWrapper =
            document.getElementById(
                'cameraPreviewWrapper'
            );

        const cameraPlaceholder =
            document.getElementById(
                'cameraPlaceholder'
            );

        const cameraStatus =
            document.getElementById(
                'cameraStatus'
            );

        const closePrescriptionCamera =
            document.getElementById(
                'closePrescriptionCamera'
            );

        const cancelPrescriptionCamera =
            document.getElementById(
                'cancelPrescriptionCamera'
            );

        const capturePrescriptionPhoto =
            document.getElementById(
                'capturePrescriptionPhoto'
            );

        const retakePrescriptionPhoto =
            document.getElementById(
                'retakePrescriptionPhoto'
            );

        const usePrescriptionPhoto =
            document.getElementById(
                'usePrescriptionPhoto'
            );


        /* =====================================================
           STATE
        ====================================================== */

        let cart = [];

        let selectedCustomerId = null;

        let customerSearchTimer = null;

        let saleType =
            'walk_in';

        let externalPrescriptionFile =
            null;

        let hospitalPrescription =
            null;


        /* =====================================================
           CAMERA STATE
        ====================================================== */

        let cameraStream =
            null;

        let capturedCameraFile =
            null;

        let capturedCameraDataUrl =
            null;


        /* =====================================================
           HELPERS
        ====================================================== */

        function formatMoney(value) {

            return (
                'KSh ' +
                Number(value || 0).toLocaleString(
                    'en-KE',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                )
            );

        }


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


        function showError(message) {

            successAlert.style.display =
                'none';

            successAlert.textContent =
                '';

            errorAlert.textContent =
                message;

            errorAlert.style.display =
                'block';

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

        }


        function showSuccess(message) {

            errorAlert.style.display =
                'none';

            errorAlert.textContent =
                '';

            successAlert.textContent =
                message;

            successAlert.style.display =
                'block';

        }


        function clearAlerts() {

            errorAlert.style.display =
                'none';

            successAlert.style.display =
                'none';

            errorAlert.textContent =
                '';

            successAlert.textContent =
                '';

        }


        function getPaymentMethod() {

            const checked =
                document.querySelector(
                    'input[name="payment_method"]:checked'
                );

            return checked
                ? checked.value
                : 'cash';

        }


        /* =====================================================
           MEDICINE SEARCH
        ====================================================== */

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

                let visibleCount =
                    0;

                cards.forEach(
                    function (card) {

                        const name =
                            card.dataset.name ||
                            '';

                        const matches =
                            name.includes(
                                search
                            );

                        card.style.display =
                            matches
                                ? ''
                                : 'none';

                        if (matches) {
                            visibleCount++;
                        }

                    }
                );

                noResults.style.display =
                    visibleCount === 0
                        ? 'block'
                        : 'none';

            }
        );


        /* =====================================================
           CART
        ====================================================== */

        function addMedicineToCart(button) {

            const id =
                Number(
                    button.dataset.id
                );

            const name =
                button.dataset.name ||
                '';

            const price =
                Number(
                    button.dataset.price || 0
                );

            const stock =
                Number(
                    button.dataset.stock || 0
                );

            const pom =
                button.dataset.pom === '1';


            if (stock <= 0) {

                showError(
                    `${name} is out of stock.`
                );

                return;

            }


            const existing =
                cart.find(
                    item =>
                        Number(item.id) ===
                        id
                );


            if (existing) {

                if (
                    Number(existing.quantity) >=
                    Number(existing.stock)
                ) {

                    showError(
                        `Only ${stock} units of ${name} are available.`
                    );

                    return;

                }

                existing.quantity++;

            } else {

                cart.push({

                    id: id,

                    name: name,

                    price: price,

                    stock: stock,

                    pom: pom,

                    quantity: 1,

                    prescription: {

                        prescription_number:
                            '',

                        prescriber_name:
                            '',

                        prescription_date:
                            '',

                        quantity_prescribed:
                            '',

                        dosage:
                            '',

                        frequency:
                            '',

                        duration:
                            '',

                        route:
                            '',

                        notes:
                            ''
                    },

                    prescription_item_id:
                        null,

                    prescription_id:
                        null,

                    prescription_remaining:
                        null,

                    prescription_number:
                        null,

                    prescriber_name:
                        null,

                    prescription_date:
                        null,

                    quantity_prescribed:
                        null,

                    dosage:
                        null,

                    frequency:
                        null,

                    duration:
                        null,

                    route:
                        null,

                    instructions:
                        null,

                    notes:
                        null

                });

            }


            clearAlerts();

            renderCart();

            updateTotals();

        }


        document
            .querySelectorAll(
                '.add-medicine'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            addMedicineToCart(
                                this
                            );

                        }
                    );

                }
            );


        /* =====================================================
           RENDER CART
        ====================================================== */

        function renderCart() {

            if (!cart.length) {

                cartItems.innerHTML = `
                    <p class="empty-cart">
                        Your cart is empty.
                    </p>
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

                        <div class="cart-item">

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
                                                ? `
                                                    <span class="pom-badge">
                                                        POM
                                                    </span>
                                                `
                                                : `
                                                    <span class="otc-badge">
                                                        OTC
                                                    </span>
                                                `
                                        }

                                    </div>

                                </div>

                                <div class="cart-item-total">
                                    ${formatMoney(itemTotal)}
                                </div>

                            </div>


                            <div class="cart-item-controls">

                                <input
                                    type="number"
                                    class="quantity-input cart-quantity-input"
                                    data-index="${index}"
                                    value="${item.quantity}"
                                    min="1"
                                    max="${item.stock}"
                                    step="1"
                                    aria-label="Quantity for ${escapeHtml(item.name)}"
                                >


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
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control prescription-field"
                                            data-index="${index}"
                                            data-field="prescription_number"
                                            value="${escapeHtml(
                                                prescription.prescription_number ||
                                                ''
                                            )}"
                                            placeholder="Prescription number"
                                        >

                                    </div>


                                    <div class="form-group">

                                        <label>
                                            Prescriber Name
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control prescription-field"
                                            data-index="${index}"
                                            data-field="prescriber_name"
                                            value="${escapeHtml(
                                                prescription.prescriber_name ||
                                                ''
                                            )}"
                                            placeholder="Doctor / prescriber"
                                        >

                                    </div>


                                    <div class="form-group">

                                        <label>
                                            Prescription Date
                                        </label>

                                        <input
                                            type="date"
                                            class="form-control prescription-field"
                                            data-index="${index}"
                                            data-field="prescription_date"
                                            value="${escapeHtml(
                                                prescription.prescription_date ||
                                                ''
                                            )}"
                                        >

                                    </div>


                                    <div class="form-group">

                                        <label>
                                            Quantity Prescribed
                                        </label>

                                        <input
                                            type="number"
                                            class="form-control prescription-field"
                                            data-index="${index}"
                                            data-field="quantity_prescribed"
                                            value="${escapeHtml(
                                                prescription.quantity_prescribed ||
                                                ''
                                            )}"
                                            min="1"
                                            placeholder="Quantity"
                                        >

                                    </div>


                                    <div class="form-group">

                                        <label>
                                            Dosage
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control prescription-field"
                                            data-index="${index}"
                                            data-field="dosage"
                                            value="${escapeHtml(
                                                prescription.dosage ||
                                                ''
                                            )}"
                                            placeholder="e.g. 500mg"
                                        >

                                    </div>


                                    <div class="form-group">

                                        <label>
                                            Frequency
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control prescription-field"
                                            data-index="${index}"
                                            data-field="frequency"
                                            value="${escapeHtml(
                                                prescription.frequency ||
                                                ''
                                            )}"
                                            placeholder="e.g. 3 times daily"
                                        >

                                    </div>


                                    <div class="form-group">

                                        <label>
                                            Duration
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control prescription-field"
                                            data-index="${index}"
                                            data-field="duration"
                                            value="${escapeHtml(
                                                prescription.duration ||
                                                ''
                                            )}"
                                            placeholder="e.g. 7 days"
                                        >

                                    </div>


                                    <div class="form-group">

                                        <label>
                                            Route
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control prescription-field"
                                            data-index="${index}"
                                            data-field="route"
                                            value="${escapeHtml(
                                                prescription.route ||
                                                ''
                                            )}"
                                            placeholder="e.g. Oral"
                                        >

                                    </div>


                                    <div class="form-group notes-field">

                                        <label>
                                            Notes / Instructions
                                        </label>

                                        <textarea
                                            class="form-control prescription-field"
                                            data-index="${index}"
                                            data-field="notes"
                                            placeholder="Additional prescription notes"
                                        >${escapeHtml(
                                            prescription.notes ||
                                            ''
                                        )}</textarea>

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


            cartItems.innerHTML =
                html;

        }


        /* =====================================================
           CART CONTROLS
        ====================================================== */

        cartItems.addEventListener(
            'click',
            function (event) {

                const increaseButton =
                    event.target.closest(
                        '.increase-item'
                    );

                const decreaseButton =
                    event.target.closest(
                        '.decrease-item'
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
                        Number(item.quantity) >=
                        Number(item.stock)
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


                    if (
                        Number(item.quantity) > 1
                    ) {

                        item.quantity--;

                    } else {

                        cart.splice(
                            index,
                            1
                        );

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

                        cart.splice(
                            index,
                            1
                        );

                    }


                    clearAlerts();

                    renderCart();

                    updateTotals();

                }

            }
        );


        /* =====================================================
           MANUAL QUANTITY
        ====================================================== */

        cartItems.addEventListener(
            'input',
            function (event) {

                const input = event.target.closest('.cart-quantity-input');

                if (!input) {
                    return;
                }

                const item = cart[Number(input.dataset.index)];

                if (!item) {
                    return;
                }

                const quantity = Math.floor(Number(input.value));

                // Let the user clear the field while typing
                if (!Number.isFinite(quantity) || quantity < 1) {
                    return;
                }

                item.quantity = Math.min(quantity, Number(item.stock));

                const totalElement = input
                    .closest('.cart-item')
                    .querySelector('.cart-item-total');

                if (totalElement) {
                    totalElement.textContent =
                        formatMoney(item.price * item.quantity);
                }

                updateTotals();

            }
        );


        cartItems.addEventListener(
            'change',
            function (event) {

                const input = event.target.closest('.cart-quantity-input');

                if (!input) {
                    return;
                }

                const item = cart[Number(input.dataset.index)];

                if (!item) {
                    return;
                }

                let quantity = Math.floor(Number(input.value));

                if (!Number.isFinite(quantity) || quantity < 1) {
                    quantity = 1;
                }

                if (quantity > Number(item.stock)) {
                    quantity = Number(item.stock);

                    showError(
                        `Only ${item.stock} units of ${item.name} are available.`
                    );
                } else {
                    clearAlerts();
                }

                item.quantity = quantity;

                renderCart();
                updateTotals();

            }
        );


        /* =====================================================
           PRESCRIPTION FIELD CHANGES
        ====================================================== */

        cartItems.addEventListener(
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
                    Number(
                        field.dataset.index
                    );

                const fieldName =
                    field.dataset.field;

                if (
                    !cart[index] ||
                    !cart[index].prescription
                ) {

                    return;

                }


                cart[index]
                    .prescription[fieldName] =
                    field.value;

            }
        );


                /* =====================================================
           SALE TYPE
        ====================================================== */

        saleTypeSelect.addEventListener(
            'change',
            function () {

                saleType =
                    this.value;

                if (
                    saleType ===
                    'hospital_prescription'
                ) {

                    window.location.href =
                        "{{ route('pharmacy.queue') }}";

                    return;

                }

                updateSaleType();

            }
        );

        function updateSaleType() {

            saleType =
                saleTypeSelect.value;

            customerSection.style.display =
                saleType === 'walk_in'
                    ? 'none'
                    : 'block';
                    
            hospitalPrescriptionSection.style.display =
                saleType ===
                'hospital_prescription'
                    ? 'block'
                    : 'none';


            externalPrescriptionSection.style.display =
                saleType ===
                'external_prescription'
                    ? 'block'
                    : 'none';


            if (
                saleType ===
                'walk_in'
            ) {

                customerName.placeholder =
                    'Optional for walk-in';

                customerPhone.placeholder =
                    'Optional for walk-in';

            } else {

                customerName.placeholder =
                    'Customer name';

                customerPhone.placeholder =
                    'Phone number';

            }

        }


        /* =====================================================
           HOSPITAL PRESCRIPTION VERIFICATION
        ====================================================== */

        verifyHospitalPrescription.addEventListener(
            'click',
            async function () {

                const prescriptionNumber =
                    hospitalPrescriptionNumber
                        .value
                        .trim();


                if (!prescriptionNumber) {

                    hospitalPrescriptionStatus.textContent =
                        'Please enter a prescription number.';

                    hospitalPrescriptionStatus.style.color =
                        '#b91c1c';

                    return;

                }


                hospitalPrescriptionStatus.textContent =
                    'Verifying prescription...';

                hospitalPrescriptionStatus.style.color =
                    '#64748b';


                hospitalPrescriptionResult.style.display =
                    'none';


                verifyHospitalPrescription.disabled =
                    true;


                try {

                    const csrfToken =
                        document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )
                            ?.getAttribute(
                                'content'
                            ) ||
                        '';


                    /*
                     * The route is POST, so the request must
                     * explicitly use POST and include CSRF.
                     */

                    const response =
                        await fetch(
                            '{{ route('hospital-prescriptions.verify') }}',
                            {
                                method: 'POST',

                                headers: {

                                    'Accept':
                                        'application/json',

                                    'Content-Type':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest',

                                    'X-CSRF-TOKEN':
                                        csrfToken

                                },

                                body:
                                    JSON.stringify({
                                        prescription_number:
                                            prescriptionNumber
                                    })

                            }
                        );


                    let data =
                        {};

                    try {

                        data =
                            await response.json();

                    } catch (
                        jsonError
                    ) {

                        data =
                            {};

                    }


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Hospital prescription could not be verified.'
                        );

                    }


                    hospitalPrescription =
                        data.prescription ||
                        data.data ||
                        data;


                    if (
                        !hospitalPrescription ||
                        !hospitalPrescription.id
                    ) {

                        throw new Error(
                            'The prescription response did not contain a valid prescription.'
                        );

                    }


                    hospitalPrescriptionStatus.textContent =
                        'Prescription verified successfully.';

                    hospitalPrescriptionStatus.style.color =
                        '#166534';


                    const customer =
                        hospitalPrescription.customer ||
                        null;


                    if (customer) {

                        selectedCustomerId =
                            customer.id ||
                            null;

                        customerName.value =
                            customer.name ||
                            '';

                        customerPhone.value =
                            customer.phone ||
                            '';

                    }


                    hospitalPrescriptionResult.innerHTML = `

                        <strong>
                            Prescription:
                            ${escapeHtml(
                                hospitalPrescription.prescription_number ||
                                prescriptionNumber
                            )}
                        </strong>

                        <span>
                            Patient:
                            ${escapeHtml(
                                customer?.name ||
                                hospitalPrescription.patient_name ||
                                'N/A'
                            )}
                        </span>

                        <span>
                            Prescriber:
                            ${escapeHtml(
                                hospitalPrescription.prescriber_name ||
                                'N/A'
                            )}
                        </span>

                        <span>
                            Date:
                            ${escapeHtml(
                                hospitalPrescription.prescription_date ||
                                'N/A'
                            )}
                        </span>

                    `;

                    hospitalPrescriptionResult.style.display =
                        'block';


                    clearAlerts();

                } catch (error) {

                    console.error(
                        'Hospital prescription verification error:',
                        error
                    );


                    hospitalPrescription =
                        null;


                    hospitalPrescriptionStatus.textContent =
                        error.message ||
                        'Unable to verify hospital prescription.';

                    hospitalPrescriptionStatus.style.color =
                        '#b91c1c';

                } finally {

                    verifyHospitalPrescription.disabled =
                        false;

                }

            }
        );


        /* =====================================================
           EXTERNAL PRESCRIPTION FILE
        ====================================================== */

        function handlePrescriptionFile(file) {

            externalPrescriptionFile =
                file || null;


            externalPrescriptionPreview.innerHTML =
                '';


            if (!file) {

                externalPrescriptionPreview.style.display =
                    'none';

                removePrescriptionFile.style.display =
                    'none';

                return;

            }


            externalPrescriptionPreview.style.display =
                'block';

            removePrescriptionFile.style.display =
                'block';


            if (
                file.type.startsWith(
                    'image/'
                )
            ) {

                const reader =
                    new FileReader();


                reader.onload =
                    function (event) {

                        externalPrescriptionPreview.innerHTML = `

                            <div class="external-prescription-preview-header">

                                <strong>
                                    Prescription Preview
                                </strong>

                            </div>

                            <img
                                src="${event.target.result}"
                                alt="Prescription preview"
                            >

                        `;

                    };


                reader.readAsDataURL(
                    file
                );

            } else {

                externalPrescriptionPreview.innerHTML = `

                    <div class="external-prescription-preview-header">

                        <strong>
                            Selected Prescription File
                        </strong>

                    </div>

                    <div class="pdf-preview">

                        Selected prescription file:

                        <strong>
                            ${escapeHtml(
                                file.name
                            )}
                        </strong>

                    </div>

                `;

            }

        }


        choosePrescriptionFile.addEventListener(
            'click',
            function () {

                externalPrescriptionFileInput.click();

            }
        );


        externalPrescriptionFileInput.addEventListener(
            'change',
            function () {

                const file =
                    this.files &&
                    this.files[0]
                        ? this.files[0]
                        : null;


                handlePrescriptionFile(
                    file
                );

            }
        );


        removePrescriptionFile.addEventListener(
            'click',
            function () {

                externalPrescriptionFile =
                    null;

                externalPrescriptionFileInput.value =
                    '';

                externalPrescriptionPreview.innerHTML =
                    '';

                externalPrescriptionPreview.style.display =
                    'none';

                removePrescriptionFile.style.display =
                    'none';

                capturedCameraFile =
                    null;

                capturedCameraDataUrl =
                    null;

                clearAlerts();

            }
        );


        /* =====================================================
           CAMERA STATUS
        ====================================================== */

        function setCameraStatus(
            message,
            isError = false
        ) {

            cameraStatus.textContent =
                message;

            cameraStatus.classList.toggle(
                'error',
                isError
            );

        }


        /* =====================================================
           STOP CAMERA
        ====================================================== */

        function stopCameraStream() {

            if (!cameraStream) {
                return;
            }


            cameraStream
                .getTracks()
                .forEach(
                    function (track) {

                        track.stop();

                    }
                );


            cameraStream =
                null;


            prescriptionCameraVideo.srcObject =
                null;

        }


        /* =====================================================
           RESET CAMERA MODAL
        ====================================================== */

        function resetCameraModal() {

            capturedCameraFile =
                null;

            capturedCameraDataUrl =
                null;


            prescriptionCameraVideo.style.display =
                'block';


            prescriptionCameraPreview.src =
                '';

            cameraPreviewWrapper.style.display =
                'none';


            cameraPlaceholder.style.display =
                'flex';


            capturePrescriptionPhoto.style.display =
                'inline-flex';


            retakePrescriptionPhoto.style.display =
                'none';


            usePrescriptionPhoto.style.display =
                'none';


            setCameraStatus(
                'Starting camera...'
            );

        }


        /* =====================================================
           OPEN CAMERA
        ====================================================== */

        async function startPrescriptionCamera() {

            clearAlerts();


            resetCameraModal();


            prescriptionCameraModal.classList.add(
                'is-open'
            );

            prescriptionCameraModal.setAttribute(
                'aria-hidden',
                'false'
            );


            if (
                !navigator.mediaDevices ||
                !navigator.mediaDevices.getUserMedia
            ) {

                setCameraStatus(
                    'Your browser does not support camera access.',
                    true
                );

                cameraPlaceholder.textContent =
                    'Camera access is not supported by this browser.';

                return;

            }


            try {

                setCameraStatus(
                    'Requesting camera access...'
                );


                cameraStream =
                    await navigator.mediaDevices.getUserMedia(
                        {
                            video: {
                                facingMode: {
                                    ideal: 'environment'
                                }
                            },
                            audio: false
                        }
                    );


                prescriptionCameraVideo.srcObject =
                    cameraStream;


                cameraPlaceholder.style.display =
                    'none';


                prescriptionCameraVideo.style.display =
                    'block';


                await prescriptionCameraVideo.play();


                setCameraStatus(
                    'Position the prescription inside the camera view, then click Capture Photo.'
                );

            } catch (error) {

                console.error(
                    'Camera error:',
                    error
                );


                let message =
                    'Unable to access the camera.';


                if (
                    error.name ===
                    'NotAllowedError'
                ) {

                    message =
                        'Camera permission was denied. Please allow camera access in your browser settings and try again.';

                } else if (
                    error.name ===
                    'NotFoundError'
                ) {

                    message =
                        'No camera was found on this computer.';

                } else if (
                    error.name ===
                    'NotReadableError'
                ) {

                    message =
                        'The camera is already being used by another application.';

                } else if (
                    error.name ===
                    'SecurityError'
                ) {

                    message =
                        'The browser blocked camera access for security reasons.';

                }


                setCameraStatus(
                    message,
                    true
                );


                cameraPlaceholder.style.display =
                    'flex';

                cameraPlaceholder.textContent =
                    message;

            }

        }


        takePrescriptionPhoto.addEventListener(
            'click',
            function () {

                startPrescriptionCamera();

            }
        );


        /* =====================================================
           CAPTURE PHOTO
        ====================================================== */

        capturePrescriptionPhoto.addEventListener(
            'click',
            function () {

                if (
                    !cameraStream ||
                    !prescriptionCameraVideo.videoWidth ||
                    !prescriptionCameraVideo.videoHeight
                ) {

                    setCameraStatus(
                        'The camera is not ready yet. Please wait a moment and try again.',
                        true
                    );

                    return;

                }


                const width =
                    prescriptionCameraVideo.videoWidth;

                const height =
                    prescriptionCameraVideo.videoHeight;


                prescriptionCameraCanvas.width =
                    width;

                prescriptionCameraCanvas.height =
                    height;


                const context =
                    prescriptionCameraCanvas.getContext(
                        '2d'
                    );


                context.drawImage(
                    prescriptionCameraVideo,
                    0,
                    0,
                    width,
                    height
                );


                capturedCameraDataUrl =
                    prescriptionCameraCanvas.toDataURL(
                        'image/jpeg',
                        0.92
                    );


                prescriptionCameraPreview.src =
                    capturedCameraDataUrl;


                prescriptionCameraVideo.style.display =
                    'none';

                cameraPreviewWrapper.style.display =
                    'block';


                stopCameraStream();


                capturePrescriptionPhoto.style.display =
                    'none';

                retakePrescriptionPhoto.style.display =
                    'inline-flex';

                usePrescriptionPhoto.style.display =
                    'inline-flex';


                setCameraStatus(
                    'Photo captured. You can retake it or use this photo.'
                );

            }
        );


        /* =====================================================
           DATA URL TO FILE
        ====================================================== */

        function dataUrlToFile(
            dataUrl,
            filename
        ) {

            const parts =
                dataUrl.split(',');

            const mimeMatch =
                parts[0].match(
                    /:(.*?);/
                );

            const mime =
                mimeMatch
                    ? mimeMatch[1]
                    : 'image/jpeg';

            const binary =
                atob(parts[1]);

            const length =
                binary.length;

            const array =
                new Uint8Array(
                    length
                );


            for (
                let index = 0;
                index < length;
                index++
            ) {

                array[index] =
                    binary.charCodeAt(
                        index
                    );

            }


            return new File(
                [array],
                filename,
                {
                    type: mime
                }
            );

        }


        /* =====================================================
           RETAKE PHOTO
        ====================================================== */

        retakePrescriptionPhoto.addEventListener(
            'click',
            async function () {

                capturedCameraFile =
                    null;

                capturedCameraDataUrl =
                    null;


                prescriptionCameraPreview.src =
                    '';

                cameraPreviewWrapper.style.display =
                    'none';


                capturePrescriptionPhoto.style.display =
                    'inline-flex';

                retakePrescriptionPhoto.style.display =
                    'none';

                usePrescriptionPhoto.style.display =
                    'none';


                prescriptionCameraVideo.style.display =
                    'block';

                cameraPlaceholder.style.display =
                    'flex';


                await startPrescriptionCamera();

            }
        );


        /* =====================================================
           USE CAPTURED PHOTO
        ====================================================== */

        usePrescriptionPhoto.addEventListener(
            'click',
            function () {

                if (
                    !capturedCameraDataUrl
                ) {

                    setCameraStatus(
                        'There is no captured photo to use.',
                        true
                    );

                    return;

                }


                capturedCameraFile =
                    dataUrlToFile(
                        capturedCameraDataUrl,
                        `prescription-${Date.now()}.jpg`
                    );


                /*
                 * Transfer the captured image to the
                 * normal prescription handler BEFORE
                 * closing the camera modal.
                 */

                handlePrescriptionFile(
                    capturedCameraFile
                );


                externalPrescriptionFileInput.value =
                    '';


                /*
                 * Only close the camera after the
                 * captured file has been transferred.
                 */

                closeCameraModal();

            }
        );


        /* =====================================================
           CLOSE CAMERA MODAL
        ====================================================== */

        function closeCameraModal() {

            stopCameraStream();


            prescriptionCameraModal.classList.remove(
                'is-open'
            );

            prescriptionCameraModal.setAttribute(
                'aria-hidden',
                'true'
            );


            resetCameraModal();

        }


        closePrescriptionCamera.addEventListener(
            'click',
            function () {

                closeCameraModal();

            }
        );


        cancelPrescriptionCamera.addEventListener(
            'click',
            function () {

                closeCameraModal();

            }
        );


        prescriptionCameraModal.addEventListener(
            'click',
            function (event) {

                if (
                    event.target ===
                    prescriptionCameraModal
                ) {

                    closeCameraModal();

                }

            }
        );


        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key ===
                    'Escape' &&
                    prescriptionCameraModal.classList.contains(
                        'is-open'
                    )
                ) {

                    closeCameraModal();

                }

            }
        );


        /* =====================================================
           CUSTOMER SEARCH
        ====================================================== */

        customerSearch.addEventListener(
            'input',
            function () {

                const search =
                    this.value.trim();


                clearTimeout(
                    customerSearchTimer
                );


                if (
                    search.length < 2
                ) {

                    customerResults.innerHTML =
                        '';

                    customerResults.style.display =
                        'none';

                    return;

                }


                customerSearchTimer =
                    setTimeout(
                        async function () {

                            try {

                                const response =
                                    await fetch(
                                        `{{ route('customers.search') }}?customer=${encodeURIComponent(search)}&search=${encodeURIComponent(search)}`,
                                        {
                                            headers: {
                                                'Accept':
                                                    'application/json',
                                                'X-Requested-With':
                                                    'XMLHttpRequest'
                                            }
                                        }
                                    );


                                if (!response.ok) {

                                    return;

                                }


                                const data =
                                    await response.json();


                                const customers =
                                    Array.isArray(data)
                                        ? data
                                        : (
                                            data.customers ||
                                            data.data ||
                                            []
                                        );


                                if (
                                    !customers.length
                                ) {

                                    customerResults.innerHTML = `

                                        <div class="customer-result">

                                            <span>
                                                No customers found.
                                            </span>

                                        </div>

                                    `;

                                    customerResults.style.display =
                                        'block';

                                    return;

                                }


                                customerResults.innerHTML =
                                    customers
                                        .map(
                                            customer => `

                                                <div
                                                    class="customer-result"
                                                    data-id="${customer.id}"
                                                    data-name="${escapeHtml(customer.name || '')}"
                                                    data-phone="${escapeHtml(customer.phone || '')}"
                                                >

                                                    <strong>
                                                        ${escapeHtml(
                                                            customer.name ||
                                                            'Unnamed Customer'
                                                        )}
                                                    </strong>

                                                    <span>
                                                        ${escapeHtml(
                                                            customer.phone ||
                                                            'No phone number'
                                                        )}
                                                    </span>

                                                </div>

                                            `
                                        )
                                        .join('');


                                customerResults.style.display =
                                    'block';


                                document
                                    .querySelectorAll(
                                        '.customer-result[data-id]'
                                    )
                                    .forEach(
                                        function (result) {

                                            result.addEventListener(
                                                'click',
                                                function () {

                                                    selectedCustomerId =
                                                        Number(
                                                            this.dataset.id
                                                        );


                                                    customerName.value =
                                                        this.dataset.name;


                                                    customerPhone.value =
                                                        this.dataset.phone;


                                                    customerSearch.value =
                                                        this.dataset.name;


                                                    customerResults.innerHTML =
                                                        '';

                                                    customerResults.style.display =
                                                        'none';

                                                }
                                            );

                                        }
                                    );


                            } catch (error) {

                                console.error(
                                    'Customer search failed:',
                                    error
                                );

                            }

                        },
                        300
                    );

            }
        );


        /* =====================================================
           HIDE CUSTOMER RESULTS
        ====================================================== */

        document.addEventListener(
            'click',
            function (event) {

                if (
                    !customerSearch.contains(
                        event.target
                    ) &&
                    !customerResults.contains(
                        event.target
                    )
                ) {

                    customerResults.style.display =
                        'none';

                }

            }
        );


        /* =====================================================
           PAYMENT METHOD
        ====================================================== */

        document
            .querySelectorAll(
                '.payment-method'
            )
            .forEach(
                function (label) {

                    label.addEventListener(
                        'click',
                        function () {

                            document
                                .querySelectorAll(
                                    '.payment-method'
                                )
                                .forEach(
                                    function (item) {

                                        item.classList.remove(
                                            'active'
                                        );

                                    }
                                );


                            this.classList.add(
                                'active'
                            );


                            const radio =
                                this.querySelector(
                                    'input[type="radio"]'
                                );


                            if (radio) {

                                radio.checked =
                                    true;

                            }


                            updatePaymentFields();

                        }
                    );

                }
            );


        /* =====================================================
           PAYMENT FIELDS
        ====================================================== */

        function updatePaymentFields() {

            const method =
                getPaymentMethod();


            if (
                method === 'split'
            ) {

                singlePaymentField.style.display =
                    'none';

                splitPaymentFields.style.display =
                    'block';

            } else {

                singlePaymentField.style.display =
                    'block';

                splitPaymentFields.style.display =
                    'none';

            }


            updateChange();

        }


        /* =====================================================
           CALCULATE SUBTOTAL
        ====================================================== */

        function calculateSubtotal() {

            return cart.reduce(
                function (
                    sum,
                    item
                ) {

                    return (
                        sum +
                        (
                            Number(item.price) *
                            Number(item.quantity)
                        )
                    );

                },
                0
            );

        }


        /* =====================================================
           CALCULATE TOTAL
        ====================================================== */

        function calculateTotal() {

            const subtotal =
                calculateSubtotal();


            const discount =
                Math.max(
                    0,
                    Number(
                        discountInput.value ||
                        0
                    )
                );


            return Math.max(
                0,
                subtotal - discount
            );

        }


        /* =====================================================
           UPDATE TOTALS
        ====================================================== */

        function updateTotals() {

            const subtotal =
                calculateSubtotal();


            const total =
                calculateTotal();


            subtotalDisplay.textContent =
                formatMoney(
                    subtotal
                );


            totalDisplay.textContent =
                formatMoney(
                    total
                );


            updateChange();

        }


        discountInput.addEventListener(
            'input',
            updateTotals
        );


        /* =====================================================
           UPDATE CHANGE
        ====================================================== */

        function updateChange() {

            const total =
                calculateTotal();


            const method =
                getPaymentMethod();


            let paid =
                0;


            if (
                method === 'split'
            ) {

                paid =
                    Number(
                        cashAmountInput.value ||
                        0
                    ) +
                    Number(
                        mpesaAmountInput.value ||
                        0
                    ) +
                    Number(
                        cardAmountInput.value ||
                        0
                    );


                const difference =
                    paid - total;


                splitTotal.textContent =
                    formatMoney(
                        total
                    );

                splitPaid.textContent =
                    formatMoney(
                        paid
                    );

                splitDifference.textContent =
                    formatMoney(
                        Math.abs(
                            difference
                        )
                    );


                splitPaymentSummary.classList.remove(
                    'split-valid',
                    'split-invalid'
                );


                if (
                    Math.abs(
                        difference
                    ) <= 0.01
                ) {

                    splitPaymentSummary.classList.add(
                        'split-valid'
                    );

                } else {

                    splitPaymentSummary.classList.add(
                        'split-invalid'
                    );

                }


                changeDisplay.style.display =
                    'none';


                return;

            }


            paid =
                Number(
                    amountPaidInput.value ||
                    0
                );


            const change =
                Math.max(
                    0,
                    paid - total
                );


            if (
                paid > 0 &&
                change > 0
            ) {

                changeDisplay.style.display =
                    'flex';


                changeDisplay.innerHTML = `

                    <span>
                        Change
                    </span>

                    <strong>
                        ${formatMoney(change)}
                    </strong>

                `;

            } else {

                changeDisplay.style.display =
                    'none';

            }

        }


        amountPaidInput.addEventListener(
            'input',
            updateChange
        );

        cashAmountInput.addEventListener(
            'input',
            updateChange
        );

        mpesaAmountInput.addEventListener(
            'input',
            updateChange
        );

        cardAmountInput.addEventListener(
            'input',
            updateChange
        );


        /* =====================================================
           VALIDATE POM INFORMATION
        ====================================================== */

        function validatePrescriptionItems() {

            for (
                const item
                of cart
            ) {

                if (
                    !item.pom
                ) {

                    continue;

                }


                const prescription =
                    item.prescription || {};


                if (
                    !prescription.prescription_number ||
                    !prescription.prescriber_name ||
                    !prescription.prescription_date ||
                    !prescription.dosage ||
                    !prescription.frequency ||
                    !prescription.duration
                ) {

                    return {

                        valid: false,

                        message:
                            `Please complete the prescription details for ${item.name}.`

                    };

                }


                if (
                    item.prescription_item_id
                ) {

                    if (
                        !item.prescription_id
                    ) {

                        return {

                            valid: false,

                            message:
                                `The hospital prescription link for ${item.name} is missing.`

                        };

                    }


                    if (
                        Number(item.quantity) >
                        Number(item.prescription_remaining)
                    ) {

                        return {

                            valid: false,

                            message:
                                `The quantity for ${item.name} exceeds the remaining quantity on the hospital prescription.`

                        };

                    }

                }

            }


            return {

                valid: true

            };

        }


        /* =====================================================
           COMPLETE SALE
        ====================================================== */

        completeSaleButton.addEventListener(
            'click',
            async function () {

                clearAlerts();


                if (
                    !cart.length
                ) {

                    showError(
                        'Please add at least one medicine to the cart.'
                    );

                    return;

                }


                /* -------------------------------------------------
                   HOSPITAL PRESCRIPTION
                ------------------------------------------------- */

                if (
                    saleType ===
                    'hospital_prescription'
                ) {

                    if (
                        !hospitalPrescription ||
                        !hospitalPrescription.id
                    ) {

                        showError(
                            'Please verify the hospital prescription before completing the sale.'
                        );

                        return;

                    }


                    const hospitalItems =
                        cart.filter(
                            item =>
                                item.prescription_item_id
                        );


                    if (
                        !hospitalItems.length
                    ) {

                        showError(
                            'Please add at least one medicine from the verified hospital prescription.'
                        );

                        return;

                    }


                    for (
                        const item
                        of hospitalItems
                    ) {

                        if (
                            Number(item.quantity) >
                            Number(item.prescription_remaining)
                        ) {

                            showError(
                                `The quantity for ${item.name} exceeds the remaining prescribed quantity.`
                            );

                            return;

                        }

                    }


                    const invalidHospitalItem =
                        cart.find(
                            item =>
                                item.pom &&
                                !item.prescription_item_id
                        );


                    if (
                        invalidHospitalItem
                    ) {

                        showError(
                            `${invalidHospitalItem.name} is a prescription-only medicine and must be added from the verified hospital prescription.`
                        );

                        return;

                    }

                }


                /* -------------------------------------------------
                   EXTERNAL PRESCRIPTION
                ------------------------------------------------- */

                if (
                    saleType ===
                    'external_prescription'
                ) {

                    if (
                        !externalPrescriptionFile
                    ) {

                        showError(
                            'Please take a photo or upload the external prescription before completing the sale.'
                        );

                        return;

                    }


                    if (
                        !customerName.value.trim()
                    ) {

                        showError(
                            'Please enter the customer name.'
                        );

                        customerName.focus();

                        return;

                    }

                }


                /* -------------------------------------------------
                   POM VALIDATION
                ------------------------------------------------- */

                const prescriptionValidation =
                    validatePrescriptionItems();


                if (
                    !prescriptionValidation.valid
                ) {

                    showError(
                        prescriptionValidation.message
                    );

                    return;

                }


                /* -------------------------------------------------
                   TOTALS
                ------------------------------------------------- */

                const total =
                    calculateTotal();


                const subtotal =
                    calculateSubtotal();


                const discount =
                    Math.max(
                        0,
                        Number(
                            discountInput.value ||
                            0
                        )
                    );


                const paymentMethod =
                    getPaymentMethod();


                let amountPaid =
                    0;

                let cashAmount =
                    0;

                let mpesaAmount =
                    0;

                let cardAmount =
                    0;

                let changeAmount =
                    0;


                /* -------------------------------------------------
                   SPLIT PAYMENT
                ------------------------------------------------- */

                if (
                    paymentMethod ===
                    'split'
                ) {

                    cashAmount =
                        Number(
                            cashAmountInput.value ||
                            0
                        );


                    mpesaAmount =
                        Number(
                            mpesaAmountInput.value ||
                            0
                        );


                    cardAmount =
                        Number(
                            cardAmountInput.value ||
                            0
                        );


                    amountPaid =
                        cashAmount +
                        mpesaAmount +
                        cardAmount;


                    if (
                        Math.abs(
                            amountPaid -
                            total
                        ) > 0.01
                    ) {

                        showError(
                            `Split payment must equal the total amount of ${formatMoney(total)}.`
                        );

                        return;

                    }


                    changeAmount =
                        0;

                } else {

                    amountPaid =
                        Number(
                            amountPaidInput.value ||
                            0
                        );


                    if (
                        amountPaid < total
                    ) {

                        showError(
                            `Amount paid must be at least ${formatMoney(total)}.`
                        );

                        amountPaidInput.focus();

                        return;

                    }


                    changeAmount =
                        amountPaid -
                        total;

                }


                /* -------------------------------------------------
                   HOSPITAL CUSTOMER
                ------------------------------------------------- */

                if (
                    saleType ===
                    'hospital_prescription'
                ) {

                    if (
                        hospitalPrescription &&
                        hospitalPrescription.customer
                    ) {

                        selectedCustomerId =
                            hospitalPrescription
                                .customer
                                .id;

                    }

                }


                /* -------------------------------------------------
                   PAYLOAD
                ------------------------------------------------- */

                const payload = {

                    customer_id:
                        selectedCustomerId,

                    customer_name:
                        saleType ===
                        'walk_in'
                            ? 'Walk-in Customer'
                            : customerName
                                .value
                                .trim(),

                    customer_phone:
                        saleType ===
                        'walk_in'
                            ? null
                            : customerPhone
                                .value
                                .trim(),

                    subtotal:
                        subtotal,

                    discount:
                        discount,

                    total_amount:
                        total,

                    payment_method:
                        paymentMethod,

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

                    sale_type:
                        saleType,

                    prescription_id:
                        saleType ===
                        'hospital_prescription' &&
                        hospitalPrescription
                            ? hospitalPrescription.id
                            : null,

                    hospital_prescription_number:
                        saleType ===
                        'hospital_prescription'
                            ? hospitalPrescription.prescription_number
                            : null,

                    prescription_number:
                        saleType ===
                        'hospital_prescription'
                            ? hospitalPrescription.prescription_number
                            : null,

                    prescriber_name:
                        saleType ===
                        'hospital_prescription'
                            ? hospitalPrescription.prescriber_name
                            : null,

                    prescription_date:
                        saleType ===
                        'hospital_prescription'
                            ? hospitalPrescription.prescription_date
                            : null,

                    items:
                        cart.map(
                            item => ({

                                medicine_id:
                                    item.id,

                                quantity:
                                    item.quantity,

                                selling_price:
                                    item.price,

                                prescription_item_id:
                                    item.prescription_item_id ||
                                    null,

                                prescription_remaining:
                                    item.prescription_remaining ||
                                    null,

                                prescription_number:
                                    item.prescription_number ||
                                    item.prescription?.prescription_number ||
                                    null,

                                prescriber_name:
                                    item.prescriber_name ||
                                    item.prescription?.prescriber_name ||
                                    null,

                                prescription_date:
                                    item.prescription_date ||
                                    item.prescription?.prescription_date ||
                                    null,

                                quantity_prescribed:
                                    item.quantity_prescribed ||
                                    item.prescription?.quantity_prescribed ||
                                    null,

                                dosage:
                                    item.dosage ||
                                    item.prescription?.dosage ||
                                    null,

                                frequency:
                                    item.frequency ||
                                    item.prescription?.frequency ||
                                    null,

                                duration:
                                    item.duration ||
                                    item.prescription?.duration ||
                                    null,

                                route:
                                    item.route ||
                                    item.prescription?.route ||
                                    null,

                                instructions:
                                    item.instructions ||
                                    item.prescription?.notes ||
                                    null,

                                notes:
                                    item.notes ||
                                    item.prescription?.notes ||
                                    null

                            })
                        )

                };


                completeSaleButton.disabled =
                    true;

                completeSaleButton.textContent =
                    'Completing Sale...';


                try {

                    /*
                     * FormData is required because an external
                     * prescription may be an image or PDF.
                     */

                    const formData =
                        new FormData();


                    Object.entries(
                        payload
                    ).forEach(
                        function (
                            [
                                key,
                                value
                            ]
                        ) {

                            if (
                                key ===
                                'items'
                            ) {

                                value.forEach(
                                    function (
                                        item,
                                        index
                                    ) {

                                        Object.entries(
                                            item
                                        ).forEach(
                                            function (
                                                [
                                                    itemKey,
                                                    itemValue
                                                ]
                                            ) {

                                                formData.append(
                                                    `items[${index}][${itemKey}]`,
                                                    itemValue === null ||
                                                    itemValue === undefined
                                                        ? ''
                                                        : itemValue
                                                );

                                            }
                                        );

                                    }
                                );


                                return;

                            }


                            formData.append(
                                key,
                                value === null ||
                                value === undefined
                                    ? ''
                                    : value
                            );

                        }
                    );


                    if (
                        externalPrescriptionFile
                    ) {

                        formData.append(
                            'prescription_file',
                            externalPrescriptionFile
                        );

                    }


                    const csrfToken =
                        document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )
                            ?.getAttribute(
                                'content'
                            ) ||
                        '';


                    const response =
                        await fetch(
                            '{{ route('sales.store') }}',
                            {
                                method: 'POST',

                                headers: {

                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest',

                                    'X-CSRF-TOKEN':
                                        csrfToken

                                },

                                body:
                                    formData

                            }
                        );


                    let data =
                        {};

                    try {

                        data =
                            await response.json();

                    } catch (
                        jsonError
                    ) {

                        data =
                            {};

                    }


                    if (
                        !response.ok
                    ) {

                        let message =
                            data.message ||
                            'Unable to complete the sale.';


                        if (
                            data.errors
                        ) {

                            const errorMessages =
                                Object.values(
                                    data.errors
                                ).flat();


                            if (
                                errorMessages.length
                            ) {

                                message =
                                    errorMessages.join(
                                        ' '
                                    );

                            }

                        }


                        throw new Error(
                            message
                        );

                    }


                    /*
                     * Prefer the backend receipt URL.
                     * This allows the new QR receipt flow
                     * to work automatically.
                     */

                    if (
                        data.redirect
                    ) {

                        window.location.href =
                            data.redirect;

                        return;

                    }


                    if (
                        data.receipt_url
                    ) {

                        window.location.href =
                            data.receipt_url;

                        return;

                    }


                    if (
                        data.sale_id
                    ) {

                        window.location.href =
                            `/sales/${data.sale_id}/receipt`;

                        return;

                    }


                    if (
                        data.sale &&
                        data.sale.id
                    ) {

                        window.location.href =
                            `/sales/${data.sale.id}/receipt`;

                        return;

                    }


                    showSuccess(
                        data.message ||
                        'Sale completed successfully.'
                    );


                    cart =
                        [];

                    selectedCustomerId =
                        null;

                    hospitalPrescription =
                        null;

                    hospitalPrescriptionNumber.value =
                        '';

                    hospitalPrescriptionStatus.textContent =
                        '';

                    hospitalPrescriptionResult.innerHTML =
                        '';

                    hospitalPrescriptionResult.style.display =
                        'none';

                    customerSearch.value =
                        '';

                    customerName.value =
                        '';

                    customerPhone.value =
                        '';

                    discountInput.value =
                        '0';

                    amountPaidInput.value =
                        '';

                    cashAmountInput.value =
                        '0';

                    mpesaAmountInput.value =
                        '0';

                    cardAmountInput.value =
                        '0';


                    paymentMethodReset();


                    handlePrescriptionFile(
                        null
                    );


                    renderCart();

                    updateTotals();


                } catch (
                    error
                ) {

                    console.error(
                        'Sale completion error:',
                        error
                    );


                    showError(
                        error.message ||
                        'Unable to complete the sale.'
                    );


                } finally {

                    completeSaleButton.disabled =
                        false;

                    completeSaleButton.textContent =
                        'Complete Sale';

                }

            }
        );


        /* =====================================================
           RESET PAYMENT METHOD
        ====================================================== */

        function paymentMethodReset() {

            const cashRadio =
                document.querySelector(
                    'input[name="payment_method"][value="cash"]'
                );


            document
                .querySelectorAll(
                    '.payment-method'
                )
                .forEach(
                    function (item) {

                        item.classList.remove(
                            'active'
                        );

                    }
                );


            if (
                cashRadio
            ) {

                cashRadio.checked =
                    true;


                cashRadio
                    .closest(
                        '.payment-method'
                    )
                    ?.classList.add(
                        'active'
                    );

            }


            updatePaymentFields();

        }


        /* =====================================================
           INITIALIZE POS
        ====================================================== */

        updateSaleType();

        updatePaymentFields();

        renderCart();

        updateTotals();

    }
);

</script>

@endsection