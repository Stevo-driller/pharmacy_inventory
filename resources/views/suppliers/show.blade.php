@extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')

<div class="header">

    <h2>Supplier Details</h2>

    <a href="{{ route('suppliers.index') }}"
       class="btn btn-secondary">
        ← Back to Suppliers
    </a>

</div>

<div class="form-container">

    <h3>{{ $supplier->name }}</h3>

    <table>

        <tr>
            <th>Supplier Name</th>
            <td>{{ $supplier->name }}</td>
        </tr>

        <tr>
            <th>Company</th>
            <td>{{ $supplier->company_name ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>Phone</th>
            <td>{{ $supplier->phone ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>Email</th>
            <td>{{ $supplier->email ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>License Number</th>
            <td>{{ $supplier->license_number ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>Address</th>
            <td>{{ $supplier->address ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>Status</th>
            <td>
                @if($supplier->status)
                    Active
                @else
                    Inactive
                @endif
            </td>
        </tr>

    </table>

    <br>

    <a href="{{ route('suppliers.edit', $supplier) }}"
       class="btn btn-warning">
        Edit Supplier
    </a>

</div>

@endsection