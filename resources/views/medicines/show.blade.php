@extends('layouts.app')

@section('title', $medicine->name)

@section('content')

<div class="header">

    <h2>Medicine Details</h2>

    <a href="{{ route('medicines.index') }}"
       class="btn btn-secondary">
        ← Back to Medicines
    </a>

</div>

<div class="form-container">

    <h3>{{ $medicine->name }}</h3>

    <table>

        <tr>
            <th>Generic Name</th>
            <td>{{ $medicine->generic_name ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>Brand Name</th>
            <td>{{ $medicine->brand_name ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>Category</th>
            <td>{{ $medicine->category->name }}</td>
        </tr>

        <tr>
            <th>Dosage Form</th>
            <td>{{ $medicine->dosage_form ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>Strength</th>
            <td>{{ $medicine->strength ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>Barcode</th>
            <td>{{ $medicine->barcode ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>Buying Price</th>
            <td>
                KSh {{ number_format($medicine->buying_price, 2) }}
            </td>
        </tr>

        <tr>
            <th>Selling Price</th>
            <td>
                KSh {{ number_format($medicine->selling_price, 2) }}
            </td>
        </tr>

        <tr>
            <th>Reorder Level</th>
            <td>{{ $medicine->reorder_level }}</td>
        </tr>

        <tr>
            <th>Prescription Required</th>

            <td>
                {{ $medicine->prescription_required ? 'Yes' : 'No' }}
            </td>

        </tr>

        <tr>
            <th>Description</th>

            <td>
                {{ $medicine->description ?? 'N/A' }}
            </td>

        </tr>

    </table>

    <br>

    <a href="{{ route('medicines.edit', $medicine) }}"
       class="btn btn-warning">
        Edit Medicine
    </a>

</div>

@endsection 