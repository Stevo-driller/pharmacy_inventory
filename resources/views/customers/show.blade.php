@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')

<div class="header">

    <div>
        <h2>Customer Details</h2>

        <p style="margin-top: 5px; color: #666;">
            View customer information and purchase history.
        </p>
    </div>

    <div style="
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    ">

        <a
            href="{{ route('customers.edit', $customer) }}"
            class="btn btn-primary"
        >
            Edit Customer
        </a>

        <a
            href="{{ route('customers.index') }}"
            class="btn"
        >
            ← Back
        </a>

    </div>

</div>


{{-- Customer Information --}}

<div class="card">

    <h3 style="margin-bottom: 20px;">
        Customer Information
    </h3>

    <div style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    ">

        <div>
            <strong>Name</strong>

            <p style="margin-top: 5px;">
                {{ $customer->name }}
            </p>
        </div>


        <div>
            <strong>Phone</strong>

            <p style="margin-top: 5px;">
                {{ $customer->phone ?: '—' }}
            </p>
        </div>


        <div>
            <strong>Email</strong>

            <p style="margin-top: 5px;">
                {{ $customer->email ?: '—' }}
            </p>
        </div>


        <div>
            <strong>Address</strong>

            <p style="margin-top: 5px;">
                {{ $customer->address ?: '—' }}
            </p>
        </div>

    </div>

</div>


{{-- Sales History --}}

<div class="card" style="margin-top: 20px;">

    <h3 style="margin-bottom: 20px;">
        Purchase History
    </h3>


    @if($customer->sales->count() > 0)

        <div style="overflow-x: auto;">

            <table class="table">

                <thead>

                    <tr>
                        <th>Sale Number</th>
                        <th>Date</th>
                        <th>Payment Method</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($customer->sales as $sale)

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
                                {{ ucfirst($sale->payment_method) }}
                            </td>

                            <td>
                                KSh {{ number_format($sale->total_amount, 2) }}
                            </td>

                            <td>
                                {{ ucfirst($sale->status) }}
                            </td>

                            <td>

                                <a
                                    href="{{ route('sales.show', $sale) }}"
                                    class="btn btn-sm"
                                >
                                    View Sale
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div style="
            text-align: center;
            padding: 30px;
            color: #666;
        ">

            <p>
                This customer has no recorded purchases yet.
            </p>

        </div>

    @endif

</div>

@endsection