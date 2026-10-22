@extends('layouts.app')

@section('title', 'Stock Management')

@section('content')

<style>
    .stock-page {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        padding-bottom: 40px;
    }

    .stock-page *,
    .stock-page *::before,
    .stock-page *::after {
        box-sizing: border-box;
    }

    .stock-header {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .stock-header-content {
        min-width: 0;
    }

    .stock-header h2 {
        margin: 0 0 5px;
        font-size: 28px;
        line-height: 1.25;
        color: #111827;
    }

    .stock-header p {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .receive-stock-btn {
        flex-shrink: 0;
        white-space: nowrap;
    }

    .stock-summary {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .summary-card {
        min-width: 0;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .summary-label {
        display: block;
        color: #6b7280;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .summary-value {
        display: block;
        color: #111827;
        font-size: 25px;
        line-height: 1.2;
        font-weight: 700;
    }

    .summary-description {
        margin-top: 5px;
        color: #9ca3af;
        font-size: 12px;
    }

    .stock-card {
        width: 100%;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
    }

    .stock-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 18px 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .stock-card-header h3 {
        margin: 0;
        color: #111827;
        font-size: 18px;
    }

    .stock-card-header span {
        color: #6b7280;
        font-size: 13px;
    }

    .stock-table-wrapper {
        width: 100%;
        overflow-x: auto;
        overflow-y: visible;
        -webkit-overflow-scrolling: touch;
    }

    .stock-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
        table-layout: auto;
    }

    .stock-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        white-space: nowrap;
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .stock-table td {
        padding: 16px;
        border-bottom: 1px solid #eef2f7;
        color: #374151;
        font-size: 14px;
        vertical-align: middle;
    }

    .stock-table tbody tr.medicine-row {
        transition: background 0.15s ease;
    }

    .stock-table tbody tr.medicine-row:hover {
        background: #f8fafc;
    }

    .medicine-cell {
        min-width: 220px;
    }

    .medicine-name {
        color: #111827;
        font-weight: 700;
        font-size: 15px;
        line-height: 1.4;
    }

    .medicine-meta {
        margin-top: 4px;
        color: #6b7280;
        font-size: 12px;
    }

    .total-stock-cell {
        min-width: 150px;
    }

    .total-stock-number {
        color: #111827;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.2;
    }

    .total-stock-label {
        display: block;
        margin-top: 3px;
        color: #6b7280;
        font-size: 11px;
    }

    .stock-good {
        color: #15803d;
    }

    .stock-low {
        color: #d97706;
    }

    .stock-empty {
        color: #dc2626;
    }

    .batch-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        padding: 5px 9px;
        border-radius: 20px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 700;
    }

    .price {
        white-space: nowrap;
        color: #374151;
        font-weight: 600;
    }

    .expiry-info {
        min-width: 130px;
    }

    .expiry-date {
        display: block;
        white-space: nowrap;
        font-weight: 600;
    }

    .expiry-warning {
        color: #dc2626 !important;
    }

    .expiry-normal {
        color: #374151;
    }

    .expiry-label {
        display: block;
        margin-top: 4px;
        font-size: 11px;
        color: #dc2626;
        font-weight: 600;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-active {
        background: #ecfdf5;
        color: #047857;
    }

    .status-inactive {
        background: #fef2f2;
        color: #b91c1c;
    }

    .status-expired {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        flex-shrink: 0;
    }

    .action-cell {
        min-width: 190px;
    }

    .actions {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
    }

    .actions form,
    .batch-actions form {
        margin: 0;
        padding: 0;
    }

    .actions .btn,
    .batch-actions .btn {
        white-space: nowrap;
    }

    .view-batches-btn {
        border: 1px solid #dbeafe;
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 6px;
        padding: 7px 10px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .view-batches-btn:hover {
        background: #dbeafe;
    }

    .view-batches-btn .arrow {
        display: inline-block;
        margin-left: 4px;
        transition: transform 0.2s ease;
    }

    .view-batches-btn.open .arrow {
        transform: rotate(180deg);
    }

    .batch-details-row {
        display: none;
        background: #f8fafc;
    }

    .batch-details-row.open {
        display: table-row;
    }

    .batch-details-cell {
        padding: 0 !important;
        border-bottom: 1px solid #e5e7eb !important;
    }

    .batch-details {
        padding: 20px 24px 22px;
        border-left: 4px solid #2563eb;
        background: #f8fafc;
    }

    .batch-details-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 15px;
    }

    .batch-details-title strong {
        color: #111827;
        font-size: 14px;
    }

    .batch-details-title span {
        color: #6b7280;
        font-size: 12px;
    }

    .batch-grid {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .batch-card {
        min-width: 0;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        padding: 15px;
    }

    .batch-card-inactive {
        opacity: 0.85;
        border-color: #fecaca;
    }

    .batch-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .batch-number {
        color: #1d4ed8;
        font-weight: 800;
        font-size: 14px;
        word-break: break-word;
    }

    .batch-quantity {
        flex-shrink: 0;
        color: #111827;
        font-weight: 800;
        white-space: nowrap;
    }

    .batch-info-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
    }

    .batch-info-item {
        min-width: 0;
    }

    .batch-info-label {
        display: block;
        color: #94a3b8;
        font-size: 10px;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .batch-info-value {
        display: block;
        color: #374151;
        font-size: 12px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .batch-fefo {
        margin-top: 12px;
        padding: 8px 10px;
        border-radius: 6px;
        background: #fffbeb;
        color: #92400e;
        font-size: 11px;
        font-weight: 600;
    }

    .batch-expired {
        background: #fef2f2;
        color: #b91c1c;
    }

    .batch-active {
        background: #ecfdf5;
        color: #047857;
    }

    .batch-inactive {
        background: #f3f4f6;
        color: #4b5563;
    }

    .batch-actions {
        display: flex;
        gap: 7px;
        flex-wrap: wrap;
        margin-top: 13px;
        padding-top: 12px;
        border-top: 1px solid #eef2f7;
    }

    .batch-actions form {
        display: inline-flex;
    }

    .empty-state {
        padding: 55px 25px;
        text-align: center;
    }

    .empty-state-icon {
        width: 55px;
        height: 55px;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        font-size: 24px;
    }

    .empty-state strong {
        display: block;
        color: #111827;
        font-size: 17px;
        margin-bottom: 6px;
    }

    .empty-state span {
        display: block;
        color: #6b7280;
        font-size: 13px;
        margin-bottom: 18px;
    }

    .stock-alert {
        padding: 12px 16px;
        margin-bottom: 16px;
        border-radius: 8px;
        font-size: 14px;
    }

    .stock-alert-success {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
    }

    .stock-alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .stock-alert-info {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
    }

    @media (max-width: 1000px) {
        .stock-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .batch-grid {
            grid-template-columns: 1fr;
        }

        .batch-info-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .stock-header {
            align-items: stretch;
            flex-direction: column;
        }

        .receive-stock-btn {
            width: 100%;
            text-align: center;
        }

        .stock-summary {
            grid-template-columns: 1fr;
        }

        .stock-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .batch-details {
            padding: 15px;
        }

        .batch-info-grid {
            grid-template-columns: 1fr;
        }

        .batch-details-title {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="stock-page">

    {{-- PAGE HEADER --}}

    <div class="stock-header">
        <div class="stock-header-content">
            <h2>Stock Management</h2>

            <p>
                Manage medicine stock, batches, expiry dates and inventory levels.
            </p>
        </div>

        <a
            href="{{ route('stock.create') }}"
            class="btn btn-primary receive-stock-btn"
        >
            + Receive Stock
        </a>
    </div>

    {{-- STATUS MESSAGES --}}

    @if(session('success'))
        <div class="stock-alert stock-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="stock-alert stock-alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if(session('info'))
        <div class="stock-alert stock-alert-info">
            {{ session('info') }}
        </div>
    @endif

    @if($errors->any())
        <div class="stock-alert stock-alert-error">
            <strong>Please check the following:</strong>

            <ul style="margin: 8px 0 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- SUMMARY CALCULATIONS --}}

    @php
        $today = today();

        $medicineGroups = $batches->groupBy('medicine_id');

        $medicineCount = $medicineGroups->count();

        $totalBatches = $batches->count();

        $totalUnits = $batches->sum(function ($batch) {
            return (int) $batch->quantity;
        });

        /*
         * Active units include only batches that:
         * - Are marked active.
         * - Have stock remaining.
         * - Have not expired.
         */
        $activeUnits = $batches
            ->filter(function ($batch) use ($today) {
                return $batch->status
                    && (int) $batch->quantity > 0
                    && $batch->expiry_date
                    && $batch->expiry_date->greaterThanOrEqualTo($today);
            })
            ->sum(function ($batch) {
                return (int) $batch->quantity;
            });
    @endphp

    {{-- SUMMARY CARDS --}}

    <div class="stock-summary">

        <div class="summary-card">
            <span class="summary-label">Medicines</span>

            <span class="summary-value">
                {{ number_format($medicineCount) }}
            </span>

            <div class="summary-description">
                Unique medicines in stock
            </div>
        </div>

        <div class="summary-card">
            <span class="summary-label">Total Batches</span>

            <span class="summary-value">
                {{ number_format($totalBatches) }}
            </span>

            <div class="summary-description">
                Individual stock batches
            </div>
        </div>

        <div class="summary-card">
            <span class="summary-label">Total Units</span>

            <span class="summary-value">
                {{ number_format($totalUnits) }}
            </span>

            <div class="summary-description">
                All recorded stock quantities
            </div>
        </div>

        <div class="summary-card">
            <span class="summary-label">Available Units</span>

            <span class="summary-value">
                {{ number_format($activeUnits) }}
            </span>

            <div class="summary-description">
                Active, unexpired stock with quantity remaining
            </div>
        </div>

    </div>

    {{-- STOCK TABLE --}}

    <div class="stock-card">

        <div class="stock-card-header">
            <div>
                <h3>Medicine Inventory</h3>

                <span>
                    Expand a medicine to view, deactivate or reactivate its batches.
                </span>
            </div>
        </div>

        <div class="stock-table-wrapper">

            <table class="stock-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine</th>
                        <th>Available Stock</th>
                        <th>Batches</th>
                        <th>Selling Price</th>
                        <th>Earliest Expiry</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($medicineGroups as $medicineId => $medicineBatches)

                        @php
                            $medicine = $medicineBatches->first()->medicine;

                            /*
                             * Calculate stock that is eligible for dispensing.
                             */
                            $availableBatches = $medicineBatches->filter(
                                function ($batch) use ($today) {
                                    return $batch->status
                                        && (int) $batch->quantity > 0
                                        && $batch->expiry_date
                                        && $batch->expiry_date->greaterThanOrEqualTo($today);
                                }
                            );

                            $totalMedicineStock = $availableBatches->sum(
                                function ($batch) {
                                    return (int) $batch->quantity;
                                }
                            );

                            /*
                             * Find the earliest expiry among batches with stock.
                             */
                            $earliestExpiryBatch = $medicineBatches
                                ->filter(function ($batch) {
                                    return $batch->expiry_date !== null
                                        && (int) $batch->quantity > 0;
                                })
                                ->sortBy('expiry_date')
                                ->first();

                            $hasActiveStock = $totalMedicineStock > 0;

                            $rowId = 'medicine-' . $medicineId;

                            $daysUntilExpiry = null;

                            if ($earliestExpiryBatch) {
                                $daysUntilExpiry = $today->diffInDays(
                                    $earliestExpiryBatch->expiry_date,
                                    false
                                );
                            }
                        @endphp

                        {{-- MEDICINE SUMMARY ROW --}}

                        <tr class="medicine-row">

                            <td>{{ $loop->iteration }}</td>

                            <td class="medicine-cell">
                                <div class="medicine-name">
                                    {{ $medicine->name ?? 'Unknown medicine' }}
                                </div>

                                <div class="medicine-meta">
                                    @if(!empty($medicine->generic_name))
                                        {{ $medicine->generic_name }}
                                    @endif

                                    @if(!empty($medicine->strength))
                                        @if(!empty($medicine->generic_name))
                                            •
                                        @endif

                                        {{ $medicine->strength }}
                                    @endif
                                </div>
                            </td>

                            {{-- AVAILABLE STOCK --}}

                            <td class="total-stock-cell">

                                @if($totalMedicineStock <= 0)
                                    <div class="total-stock-number stock-empty">
                                        0
                                    </div>
                                @elseif($totalMedicineStock <= 10)
                                    <div class="total-stock-number stock-low">
                                        {{ number_format($totalMedicineStock) }}
                                    </div>
                                @else
                                    <div class="total-stock-number stock-good">
                                        {{ number_format($totalMedicineStock) }}
                                    </div>
                                @endif

                                <span class="total-stock-label">
                                    eligible units
                                </span>

                            </td>

                            {{-- BATCH COUNT --}}

                            <td>
                                <span class="batch-count">
                                    {{ $medicineBatches->count() }}
                                </span>
                            </td>

                            {{-- SELLING PRICE --}}

                            <td>
                                @php
                                    $sellingPrices = $availableBatches
                                        ->pluck('selling_price')
                                        ->filter();
                                @endphp

                                @if($sellingPrices->count() > 0)
                                    KSh {{ number_format($sellingPrices->min(), 2) }}

                                    @if($sellingPrices->unique()->count() > 1)
                                        <br>

                                        <span style="font-size: 11px; color: #6b7280;">
                                            varies by batch
                                        </span>
                                    @endif
                                @else
                                    KSh 0.00
                                @endif
                            </td>

                            {{-- EARLIEST EXPIRY --}}

                            <td class="expiry-info">

                                @if($earliestExpiryBatch)

                                    @if($earliestExpiryBatch->expiry_date->lt($today))
                                        <span class="expiry-date expiry-warning">
                                            {{ $earliestExpiryBatch->expiry_date->format('d M Y') }}
                                        </span>

                                        <span class="expiry-label">
                                            Expired — do not dispense
                                        </span>

                                    @elseif($daysUntilExpiry !== null && $daysUntilExpiry <= 30)
                                        <span class="expiry-date expiry-warning">
                                            {{ $earliestExpiryBatch->expiry_date->format('d M Y') }}
                                        </span>

                                        <span class="expiry-label">
                                            Expiring soon
                                        </span>

                                    @else
                                        <span class="expiry-date expiry-normal">
                                            {{ $earliestExpiryBatch->expiry_date->format('d M Y') }}
                                        </span>
                                    @endif

                                @else
                                    <span>N/A</span>
                                @endif

                            </td>

                            {{-- MEDICINE STOCK STATUS --}}

                            <td>
                                @if($hasActiveStock)
                                    <span class="status-badge status-active">
                                        <span class="status-dot"></span>
                                        Available
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">
                                        <span class="status-dot"></span>
                                        No eligible stock
                                    </span>
                                @endif
                            </td>

                            {{-- MEDICINE ACTIONS --}}

                            <td class="action-cell">
                                <div class="actions">
                                    <button
                                        type="button"
                                        class="view-batches-btn"
                                        onclick="toggleBatches('{{ $rowId }}', this)"
                                    >
                                        View Batches
                                        <span class="arrow">▼</span>
                                    </button>
                                </div>
                            </td>

                        </tr>

                        {{-- EXPANDABLE BATCH DETAILS --}}

                        <tr
                            id="{{ $rowId }}"
                            class="batch-details-row"
                        >
                            <td colspan="8" class="batch-details-cell">

                                <div class="batch-details">

                                    <div class="batch-details-title">
                                        <strong>
                                            {{ $medicine->name ?? 'Medicine' }} — Batch Details
                                        </strong>

                                        <span>
                                            {{ $medicineBatches->count() }} batch(es)
                                            •
                                            {{ number_format($totalMedicineStock) }} eligible units
                                        </span>
                                    </div>

                                    <div class="batch-grid">

                                        @foreach($medicineBatches->sortBy('expiry_date') as $batch)

                                            @php
                                                $isExpired = $batch->expiry_date
                                                    && $batch->expiry_date->lt($today);

                                                $isActive = (bool) $batch->status;

                                                $hasQuantity = (int) $batch->quantity > 0;

                                                $canDispense = $isActive
                                                    && ! $isExpired
                                                    && $hasQuantity
                                                    && $batch->expiry_date !== null;

                                                $batchDaysToExpiry = null;

                                                if ($batch->expiry_date) {
                                                    $batchDaysToExpiry = $today->diffInDays(
                                                        $batch->expiry_date,
                                                        false
                                                    );
                                                }
                                            @endphp

                                            <div class="batch-card {{ ! $isActive || $isExpired ? 'batch-card-inactive' : '' }}">

                                                <div class="batch-card-header">

                                                    <div class="batch-number">
                                                        Batch {{ $batch->batch_number }}
                                                    </div>

                                                    <div class="batch-quantity">
                                                        {{ number_format($batch->quantity) }} units
                                                    </div>

                                                </div>

                                                {{-- BATCH STATUS --}}

                                                <div style="margin-bottom: 12px;">

                                                    @if($isExpired)
                                                        <span class="status-badge status-expired">
                                                            <span class="status-dot"></span>
                                                            Expired
                                                        </span>

                                                    @elseif(! $isActive)
                                                        <span class="status-badge status-inactive">
                                                            <span class="status-dot"></span>
                                                            Inactive
                                                        </span>

                                                    @elseif(! $hasQuantity)
                                                        <span class="status-badge status-inactive">
                                                            <span class="status-dot"></span>
                                                            Out of stock
                                                        </span>

                                                    @else
                                                        <span class="status-badge status-active">
                                                            <span class="status-dot"></span>
                                                            Active
                                                        </span>
                                                    @endif

                                                </div>

                                                {{-- BATCH INFORMATION --}}

                                                <div class="batch-info-grid">

                                                    <div class="batch-info-item">
                                                        <span class="batch-info-label">
                                                            Supplier
                                                        </span>

                                                        <span class="batch-info-value">
                                                            {{ $batch->supplier->name ?? 'N/A' }}
                                                        </span>
                                                    </div>

                                                    <div class="batch-info-item">
                                                        <span class="batch-info-label">
                                                            Buying Price
                                                        </span>

                                                        <span class="batch-info-value">
                                                            KSh {{ number_format($batch->buying_price, 2) }}
                                                        </span>
                                                    </div>

                                                    <div class="batch-info-item">
                                                        <span class="batch-info-label">
                                                            Selling Price
                                                        </span>

                                                        <span class="batch-info-value">
                                                            KSh {{ number_format($batch->selling_price, 2) }}
                                                        </span>
                                                    </div>

                                                    <div class="batch-info-item">
                                                        <span class="batch-info-label">
                                                            Expiry
                                                        </span>

                                                        <span class="batch-info-value {{ $isExpired || ($batchDaysToExpiry !== null && $batchDaysToExpiry <= 30) ? 'expiry-warning' : '' }}">
                                                            @if($batch->expiry_date)
                                                                {{ $batch->expiry_date->format('d M Y') }}
                                                            @else
                                                                Not recorded
                                                            @endif
                                                        </span>
                                                    </div>

                                                </div>

                                                {{-- FEFO AND EXPIRY MESSAGE --}}

                                                @if($isExpired)

                                                    <div class="batch-fefo batch-expired">
                                                        This batch has expired. It must not be sold or dispensed.
                                                    </div>

                                                @elseif(! $isActive)

                                                    <div class="batch-fefo batch-inactive">
                                                        This batch is deactivated and is not available for dispensing.
                                                    </div>

                                                @elseif(! $hasQuantity)

                                                    <div class="batch-fefo batch-inactive">
                                                        This batch has no remaining stock.
                                                    </div>

                                                @elseif(! $batch->expiry_date)

                                                    <div class="batch-fefo batch-expired">
                                                        No expiry date is recorded. This batch is excluded from available stock.
                                                    </div>

                                                @elseif($batchDaysToExpiry !== null && $batchDaysToExpiry <= 30)

                                                    <div class="batch-fefo">
                                                        This batch is approaching its expiry date. Follow FEFO and dispense only while unexpired.
                                                    </div>

                                                @else

                                                    <div class="batch-fefo batch-active">
                                                        Active and unexpired stock.
                                                    </div>

                                                @endif

                                                {{-- BATCH ACTIONS --}}

                                                <div class="batch-actions">

                                                    <a
                                                        href="{{ route('stock.show', $batch) }}"
                                                        class="btn btn-primary"
                                                    >
                                                        View
                                                    </a>

                                                    <a
                                                        href="{{ route('stock.edit', $batch) }}"
                                                        class="btn btn-warning"
                                                    >
                                                        Edit
                                                    </a>

                                                    {{-- DEACTIVATE ACTIVE BATCHES --}}

                                                    @if($isActive)

                                                        <form
                                                            action="{{ route('stock.deactivate', $batch) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Deactivate batch {{ $batch->batch_number }}? The batch will be preserved in sales history but will no longer be available for dispensing.');"
                                                        >
                                                            @csrf
                                                            @method('PATCH')

                                                            <button
                                                                type="submit"
                                                                class="btn btn-danger"
                                                            >
                                                                Deactivate
                                                            </button>
                                                        </form>

                                                    {{-- REACTIVATE ONLY UNEXPIRED BATCHES --}}

                                                    @elseif(! $isExpired && $batch->expiry_date)

                                                        <form
                                                            action="{{ route('stock.activate', $batch) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Reactivate batch {{ $batch->batch_number }}?');"
                                                        >
                                                            @csrf
                                                            @method('PATCH')

                                                            <button
                                                                type="submit"
                                                                class="btn btn-success"
                                                            >
                                                                Reactivate
                                                            </button>
                                                        </form>

                                                    @endif

                                                    {{-- DELETE ONLY IF NOT USED IN SALES --}}

                                                    <form
                                                        action="{{ route('stock.destroy', $batch) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Delete batch {{ $batch->batch_number }}? Batches used in sales cannot be deleted.');"
                                                    >
                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="btn btn-danger"
                                                        >
                                                            Delete
                                                        </button>
                                                    </form>

                                                </div>

                                            </div>

                                        @endforeach

                                    </div>

                                </div>

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" style="padding: 0;">
                                <div class="empty-state">

                                    <div class="empty-state-icon">
                                        📦
                                    </div>

                                    <strong>No stock found</strong>

                                    <span>
                                        Start by receiving your first medicine stock.
                                    </span>

                                    <a
                                        href="{{ route('stock.create') }}"
                                        class="btn btn-primary"
                                    >
                                        + Receive Stock
                                    </a>

                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
    function toggleBatches(rowId, button) {
        const row = document.getElementById(rowId);

        if (!row) {
            return;
        }

        const isOpen = row.classList.contains('open');

        /*
         * Close other expanded batch rows.
         */
        document.querySelectorAll('.batch-details-row.open').forEach(function (openRow) {
            if (openRow.id !== rowId) {
                openRow.classList.remove('open');
            }
        });

        /*
         * Reset other buttons.
         */
        document.querySelectorAll('.view-batches-btn.open').forEach(function (openButton) {
            if (openButton !== button) {
                openButton.classList.remove('open');
                openButton.innerHTML = 'View Batches <span class="arrow">▼</span>';
            }
        });

        /*
         * Toggle the selected row.
         */
        if (isOpen) {
            row.classList.remove('open');
            button.classList.remove('open');
            button.innerHTML = 'View Batches <span class="arrow">▼</span>';
        } else {
            row.classList.add('open');
            button.classList.add('open');
            button.innerHTML = 'Hide Batches <span class="arrow">▲</span>';
        }
    }
</script>

@endsection