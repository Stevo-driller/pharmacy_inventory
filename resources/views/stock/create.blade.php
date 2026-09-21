@extends('layouts.app')

@section('title', 'Receive Stock')

@section('content')

<div class="header">

    <h2>Receive New Stock</h2>

    <a href="{{ route('stock.index') }}"
       class="btn btn-secondary">
        ← Back to Stock
    </a>

</div>

<div class="form-container">

    <form action="{{ route('stock.store') }}" method="POST">

        @csrf

        <div class="form-grid">

            {{-- Medicine --}}

            <div class="form-group">

                <label for="medicine_id">
                    Medicine *
                </label>

                <select
                    id="medicine_id"
                    name="medicine_id"
                    required
                >

                    <option value="">
                        -- Select Medicine --
                    </option>

                    @foreach($medicines as $medicine)

                        <option
                            value="{{ $medicine->id }}"
                            {{ old('medicine_id') == $medicine->id ? 'selected' : '' }}
                        >
                            {{ $medicine->name }}

                            @if($medicine->strength)
                                - {{ $medicine->strength }}
                            @endif

                        </option>

                    @endforeach

                </select>

                @error('medicine_id')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            {{-- Supplier --}}

            <div class="form-group">

                <label for="supplier_id">
                    Supplier
                </label>

                <select
                    id="supplier_id"
                    name="supplier_id"
                >

                    <option value="">
                        -- Select Supplier --
                    </option>

                    @foreach($suppliers as $supplier)

                        <option
                            value="{{ $supplier->id }}"
                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}
                        >
                            {{ $supplier->name }}

                            @if($supplier->company_name)
                                - {{ $supplier->company_name }}
                            @endif

                        </option>

                    @endforeach

                </select>

                @error('supplier_id')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            {{-- Batch Number --}}

            <div class="form-group">

                <label for="batch_number">
                    Batch Number *
                </label>

                <input
                    type="text"
                    id="batch_number"
                    name="batch_number"
                    value="{{ old('batch_number') }}"
                    placeholder="e.g. AMX-001"
                    required
                >

                @error('batch_number')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            {{-- Quantity --}}

            <div class="form-group">

                <label for="quantity">
                    Quantity *
                </label>

                <input
                    type="number"
                    id="quantity"
                    name="quantity"
                    value="{{ old('quantity') }}"
                    min="1"
                    placeholder="e.g. 100"
                    required
                >

                @error('quantity')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            {{-- Buying Price --}}

            <div class="form-group">

                <label for="buying_price">
                    Buying Price (KSh) *
                </label>

                <input
                    type="number"
                    id="buying_price"
                    name="buying_price"
                    value="{{ old('buying_price') }}"
                    min="0"
                    step="0.01"
                    placeholder="e.g. 15.00"
                    required
                >

                @error('buying_price')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            {{-- Selling Price --}}

            <div class="form-group">

                <label for="selling_price">
                    Selling Price (KSh) *
                </label>

                <input
                    type="number"
                    id="selling_price"
                    name="selling_price"
                    value="{{ old('selling_price') }}"
                    min="0"
                    step="0.01"
                    placeholder="e.g. 25.00"
                    required
                >

                @error('selling_price')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            {{-- Manufactured Date --}}

            <div class="form-group">

                <label for="manufactured_date">
                    Manufactured Date
                </label>

                <input
                    type="date"
                    id="manufactured_date"
                    name="manufactured_date"
                    value="{{ old('manufactured_date') }}"
                >

                @error('manufactured_date')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            {{-- Expiry Date --}}

            <div class="form-group">

                <label for="expiry_date">
                    Expiry Date *
                </label>

                <input
                    type="date"
                    id="expiry_date"
                    name="expiry_date"
                    value="{{ old('expiry_date') }}"
                    required
                >

                @error('expiry_date')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            {{-- Received Date --}}

            <div class="form-group">

                <label for="received_date">
                    Received Date
                </label>

                <input
                    type="date"
                    id="received_date"
                    name="received_date"
                    value="{{ old('received_date', date('Y-m-d')) }}"
                >

                @error('received_date')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

        </div>


        <div class="form-actions">

            <a
                href="{{ route('stock.index') }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Receive Stock
            </button>

        </div>

    </form>

</div>

@endsection