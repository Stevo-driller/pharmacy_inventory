@extends('layouts.app')

@section('title', 'Add Customer')

@section('content')

<div class="header">

    <div>
        <h2>Add Customer</h2>

        <p style="margin-top: 5px; color: #666;">
            Add a new customer to the pharmacy system.
        </p>
    </div>

    <a href="{{ route('customers.index') }}" class="btn">
        ← Back to Customers
    </a>

</div>


<div class="card">

    <form action="{{ route('customers.store') }}" method="POST">

        @csrf

        {{-- Customer Name --}}
        <div class="form-group">

            <label for="name">
                Customer Name <span style="color: red;">*</span>
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Enter customer name"
                required
            >

            @error('name')
                <small style="color: #dc2626;">
                    {{ $message }}
                </small>
            @enderror

        </div>


        {{-- Phone --}}
        <div class="form-group">

            <label for="phone">
                Phone Number
            </label>

            <input
                type="text"
                id="phone"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="e.g. 0712345678"
            >

            @error('phone')
                <small style="color: #dc2626;">
                    {{ $message }}
                </small>
            @enderror

        </div>


        {{-- Email --}}
        <div class="form-group">

            <label for="email">
                Email Address
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="customer@example.com"
            >

            @error('email')
                <small style="color: #dc2626;">
                    {{ $message }}
                </small>
            @enderror

        </div>


        {{-- Address --}}
        <div class="form-group">

            <label for="address">
                Address
            </label>

            <textarea
                id="address"
                name="address"
                rows="4"
                placeholder="Enter customer address"
            >{{ old('address') }}</textarea>

            @error('address')
                <small style="color: #dc2626;">
                    {{ $message }}
                </small>
            @enderror

        </div>


        {{-- Buttons --}}
        <div style="
            display: flex;
            gap: 10px;
            margin-top: 20px;
        ">

            <button type="submit" class="btn btn-primary">
                Save Customer
            </button>

            <a
                href="{{ route('customers.index') }}"
                class="btn"
            >
                Cancel
            </a>

        </div>

    </form>

</div>

@endsection