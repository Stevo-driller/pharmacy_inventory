@extends('layouts.app')

@section('title', 'Edit Stock')

@section('content')

<div class="header">

    <div>
        <h2>Edit Stock Batch</h2>

        <p style="margin-top: 5px; color: #666;">
            Update the details of this medicine stock batch.
        </p>
    </div>

    <a href="{{ route('stock.index') }}" class="btn btn-secondary">
        ← Back to Stock
    </a>

</div>

<div class="form-container">

    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="error-message">

            <strong>Please fix the following errors:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    {{-- Success Message --}}
    @if (session('success'))

        <div class="success-message">
            {{ session('success') }}
        </div>

    @endif


    <form
        action="{{ route('stock.update', $stock) }}"
        method="POST"
    >

        @csrf
        @method('PUT')


        <div class="form-grid">

            {{-- Medicine --}}
            <div class="form-group">

                <label for="medicine_id">
                    Medicine <span class="required">*</span>
                </label>

                <select
                    name="medicine_id"
                    id="medicine_id"
                    required
                >

                    <option value="">
                        Select Medicine
                    </option>

                    @foreach ($medicines as $medicine)

                        <option
                            value="{{ $medicine->id }}"
                            {{ old(
                                'medicine_id',
                                $stock->medicine_id
                            ) == $medicine->id ? 'selected' : '' }}
                        >
                            {{ $medicine->name }}

                            @if ($medicine->generic_name)
                                - {{ $medicine->generic_name }}
                            @endif

                        </option>

                    @endforeach

                </select>

                @error('medicine_id')
                    <span class="field-error">{{ $message }}</span>
                @enderror

            </div>


            {{-- Supplier --}}
            <div class="form-group">

                <label for="supplier_id">
                    Supplier
                </label>

                <select
                    name="supplier_id"
                    id="supplier_id"
                >

                    <option value="">
                        Select Supplier
                    </option>

                    @foreach ($suppliers as $supplier)

                        <option
                            value="{{ $supplier->id }}"
                            {{ old(
                                'supplier_id',
                                $stock->supplier_id
                            ) == $supplier->id ? 'selected' : '' }}
                        >

                            {{ $supplier->name }}

                            @if ($supplier->company_name)
                                - {{ $supplier->company_name }}
                            @endif

                        </option>

                    @endforeach

                </select>

                @error('supplier_id')
                    <span class="field-error">{{ $message }}</span>
                @enderror

            </div>


            {{-- Batch Number --}}
            <div class="form-group">

                <label for="batch_number">
                    Batch Number <span class="required">*</span>
                </label>

                <input
                    type="text"
                    name="batch_number"
                    id="batch_number"
                    value="{{ old(
                        'batch_number',
                        $stock->batch_number
                    ) }}"
                    placeholder="e.g. AMX-001"
                    required
                >

                @error('batch_number')
                    <span class="field-error">{{ $message }}</span>
                @enderror

            </div>


            {{-- Quantity --}}
            <div class="form-group">

                <label for="quantity">
                    Quantity <span class="required">*</span>
                </label>

                <input
                    type="number"
                    name="quantity"
                    id="quantity"
                    value="{{ old(
                        'quantity',
                        $stock->quantity
                    ) }}"
                    min="0"
                    required
                >

                @error('quantity')
                    <span class="field-error">{{ $message }}</span>
                @enderror

            </div>


            {{-- Buying Price --}}
            <div class="form-group">

                <label for="buying_price">
                    Buying Price (KSh) <span class="required">*</span>
                </label>

                <input
                    type="number"
                    name="buying_price"
                    id="buying_price"
                    value="{{ old(
                        'buying_price',
                        $stock->buying_price
                    ) }}"
                    min="0"
                    step="0.01"
                    placeholder="0.00"
                    required
                >

                @error('buying_price')
                    <span class="field-error">{{ $message }}</span>
                @enderror

            </div>


            {{-- Selling Price --}}
            <div class="form-group">

                <label for="selling_price">
                    Selling Price (KSh) <span class="required">*</span>
                </label>

                <input
                    type="number"
                    name="selling_price"
                    id="selling_price"
                    value="{{ old(
                        'selling_price',
                        $stock->selling_price
                    ) }}"
                    min="0"
                    step="0.01"
                    placeholder="0.00"
                    required
                >

                @error('selling_price')
                    <span class="field-error">{{ $message }}</span>
                @enderror

            </div>


            {{-- Manufactured Date --}}
            <div class="form-group">

                <label for="manufactured_date">
                    Manufactured Date
                </label>

                <input
                    type="date"
                    name="manufactured_date"
                    id="manufactured_date"
                    value="{{ old(
                        'manufactured_date',
                        $stock->manufactured_date
                            ? \Carbon\Carbon::parse(
                                $stock->manufactured_date
                            )->format('Y-m-d')
                            : ''
                    ) }}"
                >

                @error('manufactured_date')
                    <span class="field-error">{{ $message }}</span>
                @enderror

            </div>


            {{-- Expiry Date --}}
            <div class="form-group">

                <label for="expiry_date">
                    Expiry Date <span class="required">*</span>
                </label>

                <input
                    type="date"
                    name="expiry_date"
                    id="expiry_date"
                    value="{{ old(
                        'expiry_date',
                        $stock->expiry_date
                            ? \Carbon\Carbon::parse(
                                $stock->expiry_date
                            )->format('Y-m-d')
                            : ''
                    ) }}"
                    required
                >

                @error('expiry_date')
                    <span class="field-error">{{ $message }}</span>
                @enderror

            </div>


            {{-- Received Date --}}
            <div class="form-group">

                <label for="received_date">
                    Received Date
                </label>

                <input
                    type="date"
                    name="received_date"
                    id="received_date"
                    value="{{ old(
                        'received_date',
                        $stock->received_date
                            ? \Carbon\Carbon::parse(
                                $stock->received_date
                            )->format('Y-m-d')
                            : ''
                    ) }}"
                >

                @error('received_date')
                    <span class="field-error">{{ $message }}</span>
                @enderror

            </div>


            {{-- Status --}}
            <div class="form-group">

                <label for="status">
                    Status <span class="required">*</span>
                </label>

                <select
                    name="status"
                    id="status"
                    required
                >

                    <option
                        value="1"
                        {{ old(
                            'status',
                            $stock->status
                        ) == 1 ? 'selected' : '' }}
                    >
                        Active
                    </option>

                    <option
                        value="0"
                        {{ old(
                            'status',
                            $stock->status
                        ) == 0 ? 'selected' : '' }}
                    >
                        Inactive
                    </option>

                </select>

                @error('status')
                    <span class="field-error">{{ $message }}</span>
                @enderror

            </div>

        </div>


        {{-- Form Actions --}}
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
                Update Stock
            </button>

        </div>

    </form>

</div>


<style>

.form-container {
    background: #ffffff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    max-width: 1000px;
    margin: 0 auto;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 7px;
    color: #333;
}

.required {
    color: #dc2626;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 11px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
    background: #fff;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #1e3a8a;
    box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.10);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.error-message {
    background: #fee2e2;
    color: #991b1b;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.error-message strong {
    display: block;
    margin-bottom: 8px;
}

.error-message ul {
    margin: 0;
    padding-left: 20px;
}

.success-message {
    background: #dcfce7;
    color: #166534;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.field-error {
    color: #dc2626;
    font-size: 12px;
    margin-top: 5px;
}

@media (max-width: 700px) {

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .form-actions .btn {
        width: 100%;
        text-align: center;
    }

}

</style>

@endsection