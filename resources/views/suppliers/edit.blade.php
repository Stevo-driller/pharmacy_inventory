@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')

<div class="header">

    <h2>Edit Supplier</h2>

    <a href="{{ route('suppliers.index') }}"
       class="btn btn-secondary">
        ← Back to Suppliers
    </a>

</div>

<div class="form-container">

    <form action="{{ route('suppliers.update', $supplier) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">

                <label for="name">Supplier Name *</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $supplier->name) }}"
                    required
                >

                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <div class="form-group">

                <label for="company_name">Company Name</label>

                <input
                    type="text"
                    id="company_name"
                    name="company_name"
                    value="{{ old('company_name', $supplier->company_name) }}"
                >

            </div>

            <div class="form-group">

                <label for="phone">Phone</label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone', $supplier->phone) }}"
                >

            </div>

            <div class="form-group">

                <label for="email">Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $supplier->email) }}"
                >

            </div>

            <div class="form-group">

                <label for="license_number">
                    License Number
                </label>

                <input
                    type="text"
                    id="license_number"
                    name="license_number"
                    value="{{ old('license_number', $supplier->license_number) }}"
                >

            </div>

            <div class="form-group full">

                <label for="address">Address</label>

                <textarea
                    id="address"
                    name="address"
                >{{ old('address', $supplier->address) }}</textarea>

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
                Update Supplier
            </button>

        </div>

    </form>

</div>

@endsection