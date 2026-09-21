@extends('layouts.app')

@section('title', 'Sales History')

@section('content')

<div class="header">

    <div>
        <h2>Sales History</h2>

        <p style="margin-top: 5px; color: #666;">
            Search customers, view purchase history and review completed sales.
        </p>
    </div>

    <a
        href="{{ route('sales.index') }}"
        class="btn btn-primary"
    >
        + New Sale
    </a>

</div>


{{-- ========================================================= --}}
{{-- CUSTOMER SEARCH --}}
{{-- ========================================================= --}}

<div class="customer-search-card">

    <div class="search-description">

        <h3>
            Customer / Patient Search
        </h3>

        <p>
            Search by customer name or phone number to view their
            purchase history.
        </p>

    </div>


    <form
        method="GET"
        action="{{ route('sales.history') }}"
        class="history-search-form"
    >

        <input
            type="text"
            name="customer"
            value="{{ request('customer') }}"
            placeholder="Search customer name or phone..."
            autocomplete="off"
        >


        <button
            type="submit"
            class="btn btn-primary"
        >
            Search
        </button>


        @if(request('customer'))

            <a
                href="{{ route('sales.history') }}"
                class="btn btn-secondary"
            >
                Clear
            </a>

        @endif

    </form>

</div>


{{-- ========================================================= --}}
{{-- CUSTOMER PROFILE --}}
{{-- ========================================================= --}}

@if(isset($customer) && $customer)

    @php

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER SALES
        |--------------------------------------------------------------------------
        |
        | The SaleController loads the customer's completed sales.
        |
        */

        $customerSales = $customer->sales ?? collect();


        /*
        |--------------------------------------------------------------------------
        | TOTAL PURCHASE VALUE
        |--------------------------------------------------------------------------
        */

        $totalPurchased = $customerSales->sum(
            'total_amount'
        );


        /*
        |--------------------------------------------------------------------------
        | LAST PURCHASE
        |--------------------------------------------------------------------------
        */

        $lastPurchase = $customerSales
            ->sortByDesc('created_at')
            ->first();

    @endphp


    <div class="patient-profile">

        <div class="profile-header">

            <div>

                <span class="profile-label">
                    CUSTOMER / PATIENT PROFILE
                </span>


                <h3>
                    {{ $customer->name }}
                </h3>


                <p>

                    Phone:

                    <strong>
                        {{ $customer->phone ?: 'Not provided' }}
                    </strong>

                </p>

            </div>


            <div class="profile-actions">

                <a
                    href="{{ route(
                        'sales.history',
                        ['customer_id' => $customer->id]
                    ) }}"
                    class="btn btn-primary"
                >
                    View Purchase History
                </a>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- CUSTOMER SUMMARY --}}
        {{-- ================================================= --}}

        <div class="profile-summary">


            {{-- TOTAL SALES --}}

            <div>

                <span>
                    Total Sales
                </span>

                <strong>

                    {{ number_format(
                        $customer->sales_count
                        ?? $customerSales->count()
                    ) }}

                </strong>

            </div>


            {{-- TOTAL PURCHASED --}}

            <div>

                <span>
                    Total Purchased
                </span>

                <strong>

                    KSh
                    {{ number_format(
                        $totalPurchased,
                        2
                    ) }}

                </strong>

            </div>


            {{-- LAST PURCHASE --}}

            <div>

                <span>
                    Last Purchase
                </span>

                <strong>

                    @if($lastPurchase)

                        {{ $lastPurchase->created_at->format(
                            'd M Y'
                        ) }}

                    @else

                        -

                    @endif

                </strong>

            </div>

        </div>

    </div>

@endif


{{-- ========================================================= --}}
{{-- SEARCH RESULT MESSAGE --}}
{{-- ========================================================= --}}

@if(request('customer') && !$customer)

    <div class="search-not-found">

        <div>

            <strong>
                Customer not found
            </strong>

            <p>
                No customer was found matching
                <strong>"{{ request('customer') }}"</strong>.
            </p>

        </div>


        <a
            href="{{ route('sales.history') }}"
            class="btn btn-secondary"
        >
            Clear Search
        </a>

    </div>

@endif


{{-- ========================================================= --}}
{{-- SALES TABLE --}}
{{-- ========================================================= --}}

<div class="table-container">

    @if($sales->count() > 0)

        <table>

            <thead>

                <tr>

                    <th>
                        Sale Number
                    </th>

                    <th>
                        Date
                    </th>

                    <th>
                        Customer / Patient
                    </th>

                    <th>
                        Items
                    </th>

                    <th>
                        Prescription
                    </th>

                    <th>
                        Total
                    </th>

                    <th>
                        Payment
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody>

            @foreach($sales as $sale)

                <tr>


                    {{-- ===================================== --}}
                    {{-- SALE NUMBER --}}
                    {{-- ===================================== --}}

                    <td>

                        <strong class="sale-number">
                            {{ $sale->sale_number }}
                        </strong>

                    </td>


                    {{-- ===================================== --}}
                    {{-- DATE --}}
                    {{-- ===================================== --}}

                    <td>

                        <div class="sale-date">

                            {{ $sale->created_at->format(
                                'd M Y'
                            ) }}

                        </div>

                        <small class="sale-time">

                            {{ $sale->created_at->format(
                                'H:i'
                            ) }}

                        </small>

                    </td>


                    {{-- ===================================== --}}
                    {{-- CUSTOMER --}}
                    {{-- ===================================== --}}

                    <td>

                        @if($sale->customer)

                            <strong>
                                {{ $sale->customer->name }}
                            </strong>


                            @if($sale->customer->phone)

                                <small class="customer-phone">
                                    {{ $sale->customer->phone }}
                                </small>

                            @endif

                        @elseif($sale->customer_name)

                            <strong>
                                {{ $sale->customer_name }}
                            </strong>


                            @if($sale->customer_phone)

                                <small class="customer-phone">
                                    {{ $sale->customer_phone }}
                                </small>

                            @endif

                        @else

                            <span class="walk-in">
                                Walk-in Customer
                            </span>

                        @endif

                    </td>


                    {{-- ===================================== --}}
                    {{-- ITEMS --}}
                    {{-- ===================================== --}}

                    <td>

                        @forelse($sale->items as $item)

                            <div class="history-item">

                                <strong>

                                    {{ $item->medicine->name
                                        ?? 'Unknown Medicine' }}

                                </strong>

                                <span>

                                    × {{ $item->quantity }}

                                </span>

                            </div>

                        @empty

                            <span class="no-items">
                                No items
                            </span>

                        @endforelse

                    </td>


                    {{-- ===================================== --}}
                    {{-- PRESCRIPTION STATUS --}}
                    {{-- ===================================== --}}

                    <td>

                        {{--

                            IMPORTANT:

                            Prescription belongs to the SALE,
                            not the SaleItem.

                        --}}

                        @if($sale->prescription)

                            <span class="pom-status">

                                POM

                            </span>

                        @else

                            <span class="otc-status">

                                OTC

                            </span>

                        @endif

                    </td>


                    {{-- ===================================== --}}
                    {{-- TOTAL --}}
                    {{-- ===================================== --}}

                    <td>

                        <strong>

                            KSh
                            {{ number_format(
                                $sale->total_amount,
                                2
                            ) }}

                        </strong>

                    </td>


                    {{-- ===================================== --}}
                    {{-- PAYMENT --}}
                    {{-- ===================================== --}}

                    <td>

                        <span class="payment-badge">

                            {{ strtoupper(
                                $sale->payment_method
                            ) }}

                        </span>

                    </td>


                    {{-- ===================================== --}}
                    {{-- STATUS --}}
                    {{-- ===================================== --}}

                    <td>

                        @if($sale->status === 'completed')

                            <span class="completed-status">

                                Completed

                            </span>

                        @else

                            <span class="other-status">

                                {{ ucfirst(
                                    $sale->status
                                ) }}

                            </span>

                        @endif

                    </td>


                    {{-- ===================================== --}}
                    {{-- ACTIONS --}}
                    {{-- ===================================== --}}

                    <td>

                        <div class="actions">


                            <a
                                href="{{ route(
                                    'sales.show',
                                    $sale
                                ) }}"
                                class="btn btn-primary"
                            >
                                View
                            </a>


                            <a
                                href="{{ route(
                                    'sales.receipt',
                                    $sale
                                ) }}"
                                class="btn btn-secondary"
                                target="_blank"
                            >
                                Receipt
                            </a>

                        </div>

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>


    @else

        <div class="empty">

            @if(request('customer'))

                <h3>
                    No sales found
                </h3>

                <p>

                    No completed sales were found for

                    <strong>
                        "{{ request('customer') }}"
                    </strong>.

                </p>

            @else

                <h3>
                    No sales found
                </h3>

                <p>
                    There are no completed sales yet.
                </p>

            @endif


            <a
                href="{{ route('sales.index') }}"
                class="btn btn-primary"
            >
                Make a Sale
            </a>

        </div>

    @endif

</div>


{{-- ========================================================= --}}
{{-- PAGINATION --}}
{{-- ========================================================= --}}

@if($sales->hasPages())

    <div class="pagination-container">

        {{ $sales->links() }}

    </div>

@endif


{{-- ========================================================= --}}
{{-- PAGE STYLES --}}
{{-- ========================================================= --}}

<style>


/* ========================================================= */
/* CUSTOMER SEARCH */
/* ========================================================= */

.customer-search-card {

    background: white;

    padding: 20px;

    margin-bottom: 20px;

    border-radius: 8px;

    box-shadow:
        0 2px 8px rgba(0,0,0,0.08);

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 20px;

}


.customer-search-card h3 {

    margin: 0 0 5px;

}


.customer-search-card p {

    margin: 0;

    color: #777;

    font-size: 13px;

}


.search-description {

    flex: 1;

}


.history-search-form {

    display: flex;

    gap: 8px;

    min-width: 450px;

}


.history-search-form input {

    flex: 1;

    padding: 10px 12px;

    border:
        1px solid #d1d5db;

    border-radius: 6px;

    outline: none;

    font-size: 14px;

}


.history-search-form input:focus {

    border-color: #1e3a8a;

    box-shadow:
        0 0 0 2px rgba(30,58,138,0.08);

}


/* ========================================================= */
/* CUSTOMER PROFILE */
/* ========================================================= */

.patient-profile {

    background: white;

    padding: 20px;

    margin-bottom: 20px;

    border-radius: 8px;

    box-shadow:
        0 2px 8px rgba(0,0,0,0.08);

    border-left:
        4px solid #1e3a8a;

}


.profile-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 20px;

}


.profile-label {

    display: inline-block;

    font-size: 11px;

    font-weight: bold;

    color: #1e3a8a;

    margin-bottom: 5px;

}


.profile-header h3 {

    margin: 0;

    font-size: 22px;

}


.profile-header p {

    margin: 6px 0 0;

    color: #666;

}


.profile-summary {

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 15px;

    margin-top: 20px;

}


.profile-summary > div {

    background: #f8fafc;

    padding: 15px;

    border-radius: 6px;

    border:
        1px solid #f1f5f9;

}


.profile-summary span {

    display: block;

    color: #777;

    font-size: 12px;

    margin-bottom: 6px;

}


.profile-summary strong {

    font-size: 18px;

    color: #1e3a8a;

}


/* ========================================================= */
/* SEARCH NOT FOUND */
/* ========================================================= */

.search-not-found {

    background: white;

    padding: 18px 20px;

    margin-bottom: 20px;

    border-radius: 8px;

    border-left:
        4px solid #dc2626;

    box-shadow:
        0 2px 8px rgba(0,0,0,0.08);

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 15px;

}


.search-not-found strong {

    color: #991b1b;

}


.search-not-found p {

    margin: 5px 0 0;

    color: #666;

    font-size: 13px;

}


.search-not-found p strong {

    color: #333;

}


/* ========================================================= */
/* SALE INFORMATION */
/* ========================================================= */

.sale-number {

    display: block;

    word-break: break-word;

}


.sale-date {

    white-space: nowrap;

}


.sale-time {

    display: block;

    color: #777;

    margin-top: 2px;

}


/* ========================================================= */
/* CUSTOMER */
/* ========================================================= */

.customer-phone {

    display: block;

    color: #777;

    font-size: 11px;

    margin-top: 3px;

}


.walk-in {

    color: #777;

}


.no-items {

    color: #999;

    font-size: 12px;

}


/* ========================================================= */
/* MEDICINE ITEMS */
/* ========================================================= */

.history-item {

    margin-bottom: 6px;

    line-height: 1.3;

}


.history-item:last-child {

    margin-bottom: 0;

}


.history-item strong {

    display: inline;

}


.history-item span {

    color: #777;

    margin-left: 3px;

}


/* ========================================================= */
/* PRESCRIPTION */
/* ========================================================= */

.pom-status {

    display: inline-block;

    background: #fee2e2;

    color: #991b1b;

    padding: 5px 8px;

    border-radius: 5px;

    font-size: 11px;

    font-weight: bold;

}


.otc-status {

    display: inline-block;

    background: #dcfce7;

    color: #166534;

    padding: 5px 8px;

    border-radius: 5px;

    font-size: 11px;

    font-weight: bold;

}


/* ========================================================= */
/* PAYMENT */
/* ========================================================= */

.payment-badge {

    display: inline-block;

    background: #eff6ff;

    color: #1e3a8a;

    padding: 5px 8px;

    border-radius: 5px;

    font-size: 11px;

    font-weight: bold;

}


/* ========================================================= */
/* STATUS */
/* ========================================================= */

.completed-status {

    display: inline-block;

    background: #dcfce7;

    color: #166534;

    padding: 5px 8px;

    border-radius: 5px;

    font-size: 11px;

    font-weight: bold;

}


.other-status {

    display: inline-block;

    background: #fee2e2;

    color: #991b1b;

    padding: 5px 8px;

    border-radius: 5px;

    font-size: 11px;

    font-weight: bold;

}


/* ========================================================= */
/* ACTIONS */
/* ========================================================= */

.actions {

    display: flex;

    gap: 6px;

    flex-wrap: wrap;

}


.actions .btn {

    white-space: nowrap;

}


/* ========================================================= */
/* PAGINATION */
/* ========================================================= */

.pagination-container {

    margin-top: 20px;

}


/* ========================================================= */
/* RESPONSIVE */
/* ========================================================= */

@media (max-width: 1000px) {

    .customer-search-card {

        flex-direction: column;

        align-items: stretch;

    }


    .history-search-form {

        min-width: 0;

        width: 100%;

    }

}


@media (max-width: 900px) {

    .profile-header {

        flex-direction: column;

        align-items: flex-start;

    }


    .profile-summary {

        grid-template-columns: 1fr;

    }


    .search-not-found {

        flex-direction: column;

        align-items: flex-start;

    }

}


@media (max-width: 700px) {

    .history-search-form {

        flex-direction: column;

    }


    .history-search-form .btn {

        width: 100%;

    }

}

</style>

@endsection