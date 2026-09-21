@extends('layouts.app')

@section('title', 'Suppliers')

@section('content')

<div class="header">

    <h2>Suppliers</h2>

    <a href="{{ route('suppliers.create') }}"
       class="btn btn-primary">
        + Add Supplier
    </a>

</div>

<div class="table-container">

    @if($suppliers->count())

        <table>

            <thead>

                <tr>
                    <th>#</th>
                    <th>Supplier</th>
                    <th>Company</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>License</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

                @foreach($suppliers as $supplier)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            <strong>
                                {{ $supplier->name }}
                            </strong>
                        </td>

                        <td>
                            {{ $supplier->company_name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $supplier->phone ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $supplier->email ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $supplier->license_number ?? 'N/A' }}
                        </td>

                        <td>

                            @if($supplier->status)

                                <span style="color: green;">
                                    Active
                                </span>

                            @else

                                <span style="color: red;">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="actions">

                                <a href="{{ route('suppliers.show', $supplier) }}"
                                   class="btn btn-primary">
                                    View
                                </a>

                                <a href="{{ route('suppliers.edit', $supplier) }}"
                                   class="btn btn-warning">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('suppliers.destroy', $supplier) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this supplier?');"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger"
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

    @else

        <div class="empty">

            <h3>No suppliers found</h3>

            <p>
                Add your first pharmacy supplier.
            </p>

            <a href="{{ route('suppliers.create') }}"
               class="btn btn-primary">
                Add Supplier
            </a>

        </div>

    @endif

</div>

@endsection