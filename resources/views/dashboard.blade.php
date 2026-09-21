
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="header">

    <div>
        <h2>Pharmacy Dashboard</h2>

        <p style="margin-top: 5px; color: #666;">
            Overview of your pharmacy inventory and sales
        </p>
    </div>

    <div class="dashboard-actions">

        <a href="{{ route('sales.index') }}"
           class="btn btn-primary">
            Open POS
        </a>

        <a href="{{ route('sales.history') }}"
           class="btn btn-secondary">
            Sales History
        </a>

    </div>

</div>


{{-- ===================================================== --}}
{{-- DASHBOARD CARDS --}}
{{-- ===================================================== --}}

<div class="dashboard-grid">

    {{-- Total Medicines --}}
    <div class="dashboard-card">

        <div class="card-title">
            Medicines
        </div>

        <div class="card-value">
            {{ number_format($totalMedicines) }}
        </div>

        <div class="card-description">
            Total active medicines
        </div>

    </div>


    {{-- Total Stock --}}
    <div class="dashboard-card">

        <div class="card-title">
            Stock Units
        </div>

        <div class="card-value">
            {{ number_format($totalStock) }}
        </div>

        <div class="card-description">
            Usable units in stock
        </div>

    </div>


    {{-- Stock Value --}}
    <div class="dashboard-card">

        <div class="card-title">
            Stock Value
        </div>

        <div class="card-value">
            KSh {{ number_format($stockValue, 2) }}
        </div>

        <div class="card-description">
            Current usable inventory value
        </div>

    </div>


    {{-- Today's Sales --}}
    <div class="dashboard-card">

        <div class="card-title">
            Today's Sales
        </div>

        <div class="card-value">
            KSh {{ number_format($todaySales, 2) }}
        </div>

        <div class="card-description">
            Completed sales today
        </div>

    </div>


    {{-- Low Stock --}}
    <div class="dashboard-card">

        <div class="card-title">
            Low Stock
        </div>

        <div class="card-value warning-value">
            {{ number_format($lowStockCount) }}
        </div>

        <div class="card-description">
            Medicines requiring restocking
        </div>

    </div>


    {{-- Expired --}}
    <div class="dashboard-card">

        <div class="card-title">
            Expired
        </div>

        <div class="card-value danger-value">
            {{ number_format($expiredCount) }}
        </div>

        <div class="card-description">
            Batches already expired
        </div>

    </div>


    {{-- Expiring Soon --}}
    <div class="dashboard-card">

        <div class="card-title">
            Expiring Soon
        </div>

        <div class="card-value warning-value">
            {{ number_format($expiringCount) }}
        </div>

        <div class="card-description">
            Batches expiring within 30 days
        </div>

    </div>


    {{-- Total Sales --}}
    <div class="dashboard-card">

        <div class="card-title">
            Total Sales
        </div>

        <div class="card-value">
            {{ number_format($totalSales) }}
        </div>

        <div class="card-description">
            Completed sales
        </div>

    </div>

</div>


{{-- ===================================================== --}}
{{-- INVENTORY SUMMARY --}}
{{-- ===================================================== --}}

<div class="dashboard-section">

    <div class="section-header">

        <h3>
            Inventory Summary
        </h3>

        <a href="{{ route('stock.index') }}"
           class="btn btn-secondary">
            View Stock
        </a>

    </div>


    <div class="summary-box">

        <div>
            <span>Total Stock Units</span>

            <strong>
                {{ number_format($totalStock) }}
            </strong>
        </div>


        <div>
            <span>Inventory Value</span>

            <strong>
                KSh {{ number_format($stockValue, 2) }}
            </strong>
        </div>


        <div>
            <span>Low Stock Medicines</span>

            <strong class="warning-text">
                {{ number_format($lowStockCount) }}
            </strong>
        </div>


        <div>
            <span>Expired Batches</span>

            <strong class="danger-text">
                {{ number_format($expiredCount) }}
            </strong>
        </div>


        <div>
            <span>Expiring Within 30 Days</span>

            <strong class="warning-text">
                {{ number_format($expiringCount) }}
            </strong>
        </div>

    </div>

</div>


{{-- ===================================================== --}}
{{-- RECENT SALES --}}
{{-- ===================================================== --}}

<div class="dashboard-section">

    <div class="section-header">

        <h3>
            Recent Sales
        </h3>

        <a href="{{ route('sales.history') }}"
           class="btn btn-secondary">
            View All Sales
        </a>

    </div>


    <div class="table-container dashboard-table">

        <table>

            <thead>

                <tr>

                    <th>Sale Number</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Action</th>

                </tr>

            </thead>


            <tbody>

                @forelse($recentSales as $sale)

                    <tr>

                        <td>
                            <strong>
                                {{ $sale->sale_number }}
                            </strong>
                        </td>


                        <td>
                            {{ $sale->created_at->format('d M Y, H:i') }}
                        </td>


                        <td>
                            {{ $sale->customer_name ?: 'Walk-in Customer' }}
                        </td>


                        <td>
                            {{ $sale->items->sum('quantity') }}
                        </td>


                        <td>
                            <strong>
                                KSh
                                {{ number_format(
                                    $sale->total_amount,
                                    2
                                ) }}
                            </strong>
                        </td>


                        <td>

                            <span class="payment-badge">
                                {{ strtoupper($sale->payment_method) }}
                            </span>

                        </td>


                        <td>

                            <a href="{{ route(
                                'sales.show',
                                $sale
                            ) }}"
                            class="btn btn-primary">

                                View

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="empty">

                            No sales have been recorded yet.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


{{-- ===================================================== --}}
{{-- ALERTS --}}
{{-- ===================================================== --}}

<div class="alert-grid">


    {{-- ================================================= --}}
    {{-- LOW STOCK --}}
    {{-- ================================================= --}}

    <div class="dashboard-section alert-section">

        <div class="section-header">

            <h3>
                ⚠ Low Stock Medicines
            </h3>

            <span class="alert-count warning-count">
                {{ number_format($lowStockCount) }}
            </span>

        </div>


        @forelse($lowStockMedicines as $medicine)

            <div class="alert-item low-stock-alert">

                <div>

                    <strong>
                        {{ $medicine->name }}
                    </strong>

                    <small>
                        Reorder level:
                        {{ number_format($medicine->reorder_level ?? 0) }}
                        units
                    </small>

                </div>


                {{-- IMPORTANT:
                     This must match total_quantity
                     calculated by DashboardController --}}
                <div class="stock-number">

                    {{ number_format(
                        $medicine->total_quantity ?? 0
                    ) }}

                    <small>
                        units
                    </small>

                </div>

            </div>

        @empty

            <div class="empty-alert">

                <div class="success-icon">
                    ✓
                </div>

                <p>
                    No low-stock medicines.
                </p>

            </div>

        @endforelse

    </div>


    {{-- ================================================= --}}
    {{-- EXPIRED --}}
    {{-- ================================================= --}}

    <div class="dashboard-section alert-section">

        <div class="section-header">

            <h3>
                🔴 Expired Medicines
            </h3>

            <span class="alert-count danger-count">
                {{ number_format($expiredCount) }}
            </span>

        </div>


        @forelse($expiredBatches as $batch)

            <div class="alert-item expired-alert">

                <div>

                    <strong>
                        {{ $batch->medicine->name ?? 'Unknown Medicine' }}
                    </strong>

                    <small>
                        Batch:
                        {{ $batch->batch_number }}
                    </small>

                </div>


                <div class="expiry-date expired-date">

                    {{ \Carbon\Carbon::parse(
                        $batch->expiry_date
                    )->format('d M Y') }}

                    <small>
                        {{ number_format($batch->quantity) }}
                        units
                    </small>

                </div>

            </div>

        @empty

            <div class="empty-alert">

                <div class="success-icon">
                    ✓
                </div>

                <p>
                    No expired batches.
                </p>

            </div>

        @endforelse

    </div>


    {{-- ================================================= --}}
    {{-- EXPIRING SOON --}}
    {{-- ================================================= --}}

    <div class="dashboard-section alert-section">

        <div class="section-header">

            <h3>
                🟠 Expiring Within 30 Days
            </h3>

            <span class="alert-count expiring-count">
                {{ number_format($expiringCount) }}
            </span>

        </div>


        @forelse($expiringBatches as $batch)

            <div class="alert-item expiring-alert">

                <div>

                    <strong>
                        {{ $batch->medicine->name ?? 'Unknown Medicine' }}
                    </strong>

                    <small>
                        Batch:
                        {{ $batch->batch_number }}
                    </small>

                </div>


                <div class="expiry-date expiring-date">

                    {{ \Carbon\Carbon::parse(
                        $batch->expiry_date
                    )->format('d M Y') }}

                    <small>
                        {{ number_format($batch->quantity) }}
                        units
                    </small>

                </div>

            </div>

        @empty

            <div class="empty-alert">

                <div class="success-icon">
                    ✓
                </div>

                <p>
                    No batches expiring within 30 days.
                </p>

            </div>

        @endforelse

    </div>

</div>


{{-- ===================================================== --}}
{{-- STYLES --}}
{{-- ===================================================== --}}

<style>

    /* ===================================================== */
    /* HEADER */
    /* ===================================================== */

    .dashboard-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }


    /* ===================================================== */
    /* DASHBOARD CARDS */
    /* ===================================================== */

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }


    .dashboard-card {
        background: white;
        padding: 22px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }


    .card-title {
        font-size: 15px;
        color: #666;
        margin-bottom: 10px;
    }


    .card-value {
        font-size: 26px;
        font-weight: bold;
        color: #1e3a8a;
        margin-bottom: 6px;
    }


    .warning-value {
        color: #d97706;
    }


    .danger-value {
        color: #dc2626;
    }


    .card-description {
        font-size: 13px;
        color: #888;
    }


    /* ===================================================== */
    /* SECTIONS */
    /* ===================================================== */

    .dashboard-section {
        background: white;
        padding: 20px;
        margin-bottom: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }


    .dashboard-section h3 {
        margin-top: 0;
        margin-bottom: 0;
    }


    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        gap: 15px;
    }


    /* ===================================================== */
    /* INVENTORY SUMMARY */
    /* ===================================================== */

    .summary-box {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
    }


    .summary-box > div {
        padding: 15px;
        background: #f8fafc;
        border-radius: 6px;
    }


    .summary-box span {
        display: block;
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
    }


    .summary-box strong {
        font-size: 18px;
    }


    .warning-text {
        color: #d97706;
    }


    .danger-text {
        color: #dc2626;
    }


    /* ===================================================== */
    /* TABLE */
    /* ===================================================== */

    .dashboard-table {
        box-shadow: none;
        border: 1px solid #eee;
    }


    .payment-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 12px;
        background: #eff6ff;
        color: #1e3a8a;
        font-size: 11px;
        font-weight: bold;
    }


    /* ===================================================== */
    /* ALERT GRID */
    /* ===================================================== */

    .alert-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }


    .alert-section {
        min-width: 0;
        margin-bottom: 0;
    }


    /* ===================================================== */
    /* ALERT COUNTS */
    /* ===================================================== */

    .alert-count {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 13px;
        font-weight: bold;
    }


    .warning-count {
        background: #fef3c7;
        color: #92400e;
    }


    .danger-count {
        background: #fee2e2;
        color: #991b1b;
    }


    .expiring-count {
        background: #ffedd5;
        color: #9a3412;
    }


    /* ===================================================== */
    /* ALERT ITEMS */
    /* ===================================================== */

    .alert-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 13px;
        margin-bottom: 10px;
        background: #f8fafc;
        border-radius: 6px;
        border-left: 4px solid #f59e0b;
        gap: 15px;
    }


    .alert-item:last-child {
        margin-bottom: 0;
    }


    .alert-item strong {
        display: block;
        margin-bottom: 4px;
    }


    .alert-item small {
        display: block;
        color: #777;
        font-size: 12px;
    }


    /* ===================================================== */
    /* LOW STOCK */
    /* ===================================================== */

    .low-stock-alert {
        border-left-color: #f59e0b;
    }


    .stock-number {
        font-weight: bold;
        color: #dc2626;
        text-align: right;
        white-space: nowrap;
    }


    .stock-number small {
        color: #777;
        font-weight: normal;
    }


    /* ===================================================== */
    /* EXPIRED */
    /* ===================================================== */

    .expired-alert {
        background: #fef2f2;
        border-left-color: #dc2626;
    }


    .expired-date {
        color: #dc2626;
    }


    /* ===================================================== */
    /* EXPIRING */
    /* ===================================================== */

    .expiring-alert {
        background: #fffbeb;
        border-left-color: #f97316;
    }


    .expiring-date {
        color: #ea580c;
    }


    .expiry-date {
        font-weight: bold;
        text-align: right;
        white-space: nowrap;
    }


    .expiry-date small {
        color: #777;
        font-weight: normal;
    }


    /* ===================================================== */
    /* EMPTY ALERT */
    /* ===================================================== */

    .empty-alert {
        text-align: center;
        padding: 25px;
        color: #777;
    }


    .empty-alert p {
        margin-bottom: 0;
    }


    .success-icon {
        width: 35px;
        height: 35px;
        line-height: 35px;
        margin: 0 auto 10px;
        border-radius: 50%;
        background: #dcfce7;
        color: #166534;
        font-weight: bold;
        text-align: center;
    }


    /* ===================================================== */
    /* RESPONSIVE */
    /* ===================================================== */

    @media (max-width: 1200px) {

        .dashboard-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .summary-box {
            grid-template-columns: repeat(3, 1fr);
        }

        .alert-grid {
            grid-template-columns: repeat(2, 1fr);
        }

    }


    @media (max-width: 800px) {

        .dashboard-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .summary-box {
            grid-template-columns: repeat(2, 1fr);
        }

        .alert-grid {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 600px) {

        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .summary-box {
            grid-template-columns: 1fr;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .dashboard-actions {
            width: 100%;
        }

        .dashboard-actions .btn {
            flex: 1;
            text-align: center;
        }

    }

</style>

@endsection
