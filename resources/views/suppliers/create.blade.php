@extends('layouts.app')

@section('title', 'Add Supplier')

@section('content')

<div class="header">

    <h2>Add New Supplier</h2>

    <a href="{{ route('suppliers.index') }}"
       class="btn btn-secondary">
        ← Back to Suppliers
    </a>

</div>

<div class="form-container">

    <form action="{{ route('suppliers.store') }}"
          method="POST">

        @csrf

        <div class="form-grid">

            <div class="form-group">

                <label for="name">
                    Supplier Name *
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="e.g. John Kamau"
                    required
                >

                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            <div class="form-group">

                <label for="company_name">
                    Company Name
                </label>

                <input
                    type="text"
                    id="company_name"
                    name="company_name"
                    value="{{ old('company_name') }}"
                    placeholder="e.g. MedSupply Kenya Ltd"
                >

                @error('company_name')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            <div class="form-group">

                <label for="phone">
                    Phone
                </label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="e.g. 0712345678"
                >

                @error('phone')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="supplier@example.com"
                >

                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            <div class="form-group">

                <label for="license_number">
                    License Number
                </label>

                <input
                    type="text"
                    id="license_number"
                    name="license_number"
                    value="{{ old('license_number') }}"
                    placeholder="e.g. PPB-123456"
                >

                @error('license_number')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            <div class="form-group full">

                <label for="address">
                    Address
                </label>

                <textarea
                    id="address"
                    name="address"
                    placeholder="Enter supplier address..."
                >{{ old('address') }}</textarea>

                @error('address')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

        </div>


        <div class="form-actions">

            <a href="{{ route('suppliers.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary">
                Save Supplier
            </button>

        </div>

    </form>

</div>

@endsection