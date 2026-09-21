@extends('layouts.app')

@section('title', 'Edit Medicine')

@section('content')

<div class="header">

    <h2>Edit Medicine</h2>

    <a href="{{ route('medicines.index') }}"
       class="btn btn-secondary">
        ← Back to Medicines
    </a>

</div>

<div class="form-container">

    <form action="{{ route('medicines.update', $medicine) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">

                <label for="name">
                    Medicine Name *
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $medicine->name) }}"
                    required
                >

            </div>


            <div class="form-group">

                <label for="generic_name">
                    Generic Name
                </label>

                <input
                    type="text"
                    id="generic_name"
                    name="generic_name"
                    value="{{ old('generic_name', $medicine->generic_name) }}"
                >

            </div>


            <div class="form-group">

                <label for="brand_name">
                    Brand Name
                </label>

                <input
                    type="text"
                    id="brand_name"
                    name="brand_name"
                    value="{{ old('brand_name', $medicine->brand_name) }}"
                >

            </div>


            <div class="form-group">

                <label for="category_id">
                    Category *
                </label>

                <select
                    id="category_id"
                    name="category_id"
                    required
                >

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id', $medicine->category_id) == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="form-group">

                <label for="dosage_form">
                    Dosage Form
                </label>

                <select
                    id="dosage_form"
                    name="dosage_form"
                >

                    @foreach([
                        'Tablet',
                        'Capsule',
                        'Syrup',
                        'Injection',
                        'Cream',
                        'Ointment',
                        'Drops'
                    ] as $form)

                        <option
                            value="{{ $form }}"
                            {{ old('dosage_form', $medicine->dosage_form) == $form ? 'selected' : '' }}
                        >
                            {{ $form }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="form-group">

                <label for="strength">
                    Strength
                </label>

                <input
                    type="text"
                    id="strength"
                    name="strength"
                    value="{{ old('strength', $medicine->strength) }}"
                >

            </div>


            <div class="form-group">

                <label for="barcode">
                    Barcode
                </label>

                <input
                    type="text"
                    id="barcode"
                    name="barcode"
                    value="{{ old('barcode', $medicine->barcode) }}"
                >

            </div>


            <div class="form-group">

                <label for="buying_price">
                    Buying Price (KSh) *
                </label>

                <input
                    type="number"
                    id="buying_price"
                    name="buying_price"
                    value="{{ old('buying_price', $medicine->buying_price) }}"
                    min="0"
                    step="0.01"
                    required
                >

            </div>


            <div class="form-group">

                <label for="selling_price">
                    Selling Price (KSh) *
                </label>

                <input
                    type="number"
                    id="selling_price"
                    name="selling_price"
                    value="{{ old('selling_price', $medicine->selling_price) }}"
                    min="0"
                    step="0.01"
                    required
                >

            </div>


            <div class="form-group">

                <label for="reorder_level">
                    Reorder Level *
                </label>

                <input
                    type="number"
                    id="reorder_level"
                    name="reorder_level"
                    value="{{ old('reorder_level', $medicine->reorder_level) }}"
                    min="0"
                    required
                >

            </div>


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
                        {{ old('prescription_required', $medicine->prescription_required) ? 'checked' : '' }}
                    >

                    <label for="prescription_required">
                        Prescription required
                    </label>

                </div>

            </div>


            <div class="form-group full">

                <label for="description">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                >{{ old('description', $medicine->description) }}</textarea>

            </div>

        </div>


        <div class="form-actions">

            <a href="{{ route('medicines.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Update Medicine
            </button>

        </div>

    </form>

</div>

@endsection