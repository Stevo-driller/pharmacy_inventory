@extends('layouts.app')

@section('title', 'Add Medicine')

@section('content')

<div class="header">

    <h2>Add New Medicine</h2>

    <a href="{{ route('medicines.index') }}"
       class="btn btn-secondary">
        ← Back to Medicines
    </a>

</div>

<div class="form-container">

    <form action="{{ route('medicines.store') }}"
          method="POST">

        @csrf

        <div class="form-grid">

            {{-- Medicine Name --}}
            <div class="form-group">

                <label for="name">
                    Medicine Name *
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="e.g. Amoxicillin 500mg"
                    required
                >

                @error('name')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Generic Name --}}
            <div class="form-group">

                <label for="generic_name">
                    Generic Name
                </label>

                <input
                    type="text"
                    id="generic_name"
                    name="generic_name"
                    value="{{ old('generic_name') }}"
                    placeholder="e.g. Amoxicillin"
                >

                @error('generic_name')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Brand Name --}}
            <div class="form-group">

                <label for="brand_name">
                    Brand Name
                </label>

                <input
                    type="text"
                    id="brand_name"
                    name="brand_name"
                    value="{{ old('brand_name') }}"
                    placeholder="e.g. Amoxil"
                >

                @error('brand_name')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Category --}}
            <div class="form-group">

                <label for="category_id">
                    Category *
                </label>

                <select
                    id="category_id"
                    name="category_id"
                    required
                >

                    <option value="">
                        -- Select Category --
                    </option>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>

                    @endforeach

                </select>

                @error('category_id')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Dosage Form --}}
            <div class="form-group">

                <label for="dosage_form">
                    Dosage Form
                </label>

                <select
                    id="dosage_form"
                    name="dosage_form"
                >

                    <option value="">
                        -- Select Form --
                    </option>

                    <option value="Tablet"
                        {{ old('dosage_form') == 'Tablet' ? 'selected' : '' }}>
                        Tablet
                    </option>

                    <option value="Capsule"
                        {{ old('dosage_form') == 'Capsule' ? 'selected' : '' }}>
                        Capsule
                    </option>

                    <option value="Syrup"
                        {{ old('dosage_form') == 'Syrup' ? 'selected' : '' }}>
                        Syrup
                    </option>

                    <option value="Injection"
                        {{ old('dosage_form') == 'Injection' ? 'selected' : '' }}>
                        Injection
                    </option>

                    <option value="Cream"
                        {{ old('dosage_form') == 'Cream' ? 'selected' : '' }}>
                        Cream
                    </option>

                    <option value="Ointment"
                        {{ old('dosage_form') == 'Ointment' ? 'selected' : '' }}>
                        Ointment
                    </option>

                    <option value="Drops"
                        {{ old('dosage_form') == 'Drops' ? 'selected' : '' }}>
                        Drops
                    </option>

                </select>

                @error('dosage_form')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Strength --}}
            <div class="form-group">

                <label for="strength">
                    Strength
                </label>

                <input
                    type="text"
                    id="strength"
                    name="strength"
                    value="{{ old('strength') }}"
                    placeholder="e.g. 500mg"
                >

                @error('strength')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Barcode --}}
            <div class="form-group">

                <label for="barcode">
                    Barcode
                </label>

                <input
                    type="text"
                    id="barcode"
                    name="barcode"
                    value="{{ old('barcode') }}"
                    placeholder="e.g. 1234567890123"
                >

                @error('barcode')
                    <div class="error">
                        {{ $message }}
                    </div>
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
                    placeholder="0.00"
                    required
                >

                @error('buying_price')
                    <div class="error">
                        {{ $message }}
                    </div>
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
                    placeholder="0.00"
                    required
                >

                @error('selling_price')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Reorder Level --}}
            <div class="form-group">

                <label for="reorder_level">
                    Reorder Level *
                </label>

                <input
                    type="number"
                    id="reorder_level"
                    name="reorder_level"
                    value="{{ old('reorder_level', 10) }}"
                    min="0"
                    required
                >

                @error('reorder_level')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Prescription --}}
            <div class="form-group">

                <label>
                    Prescription
                </label>

                <div class="checkbox-group">

                    <input
                        type="checkbox"
                        id="prescription_required"
                        name="prescription_required"
                        value="1"
                        {{ old('prescription_required') ? 'checked' : '' }}
                    >

                    <label for="prescription_required">
                        Prescription required
                    </label>

                </div>

            </div>


            {{-- Description --}}
            <div class="form-group full">

                <label for="description">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    placeholder="Enter medicine description..."
                >{{ old('description') }}</textarea>

                @error('description')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>


        {{-- Buttons --}}
        <div class="form-actions">

            <a href="{{ route('medicines.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Medicine
            </button>

        </div>

    </form>

</div>

@endsection