@extends('layouts.app')

@section('title', 'Customers')

@section('content')

<div class="header">

    <div>
        <h2>Customers</h2>

        <p style="margin-top: 5px; color: #666;">
            Manage customers and view their purchase information.
        </p>
    </div>

    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        + Add Customer
    </a>

</div>


{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


{{-- Error Message --}}
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


{{-- Search --}}
<div class="card" style="margin-bottom: 20px;">

    <form method="GET" action="{{ route('customers.index') }}">

        <div style="
            display: flex;
            gap: 10px;
            align-items: end;
            flex-wrap: wrap;
        ">

            <div style="flex: 1; min-width: 250px;">

                <label for="search">
                    Search Customer
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, phone or email..."
                    style="width: 100%;"
                >

            </div>

            <button type="submit" class="btn btn-primary">
                Search
            </button>

            @if(request('search'))
                <a href="{{ route('customers.index') }}" class="btn">
                    Clear
                </a>
            @endif

        </div>

    </form>

</div>


{{-- Customers Table --}}
<div class="card">

    <div style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    ">

        <div>
            <h3 style="margin: 0;">
                Customer List
            </h3>

            <p style="margin: 5px 0 0; color: #666;">
                {{ $customers->total() }} customer(s)
            </p>
        </div>

    </div>


    @if($customers->count() > 0)

        <div style="overflow-x: auto;">

            <table class="table">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($customers as $customer)

                        <tr>

                            <td>
                                {{ $customers->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>
                                    {{ $customer->name }}
                                </strong>
                            </td>

                            <td>
                                {{ $customer->phone ?: '—' }}
                            </td>

                            <td>
                                {{ $customer->email ?: '—' }}
                            </td>

                            <td>
                                {{ $customer->address ?: '—' }}
                            </td>

                            <td>

                                <div style="
                                    display: flex;
                                    gap: 6px;
                                    flex-wrap: wrap;
                                ">

                                    <a
                                        href="{{ route('customers.show', $customer) }}"
                                        class="btn btn-sm"
                                    >
                                        View
                                    </a>

                                    <a
                                        href="{{ route('customers.edit', $customer) }}"
                                        class="btn btn-sm"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('customers.destroy', $customer) }}"
                                        method="POST"
                                        style="display: inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this customer?');"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm"
                                            style="color: #dc2626;"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}

        <div style="
            margin-top: 20px;
            display: flex;
            justify-content: center;
        ">

            {{ $customers->links() }}

        </div>

    @else

        <div style="
            text-align: center;
            padding: 40px 20px;
            color: #666;
        ">

            <h3>
                No Customers Found
            </h3>

            @if(request('search'))

                <p>
                    No customers match
                    <strong>"{{ request('search') }}"</strong>.
                </p>

                <a
                    href="{{ route('customers.index') }}"
                    class="btn"
                >
                    Clear Search
                </a>

            @else

                <p>
                    There are no customers in the system yet.
                </p>

                <a
                    href="{{ route('customers.create') }}"
                    class="btn btn-primary"
                >
                    + Add First Customer
                </a>

            @endif

        </div>

    @endif

</div>

@endsection