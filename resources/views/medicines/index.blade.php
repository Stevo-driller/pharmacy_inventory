@extends('layouts.app')

@section('title', 'Medicines')

@section('content')

<div class="header">

    <h2>Medicines</h2>

    <a href="{{ route('medicines.create') }}"
       class="btn btn-primary">
        + Add Medicine
    </a>

</div>

<div class="table-container">

    @if($medicines->count())

        <table>

            <thead>

                <tr>
                    <th>#</th>
                    <th>Medicine</th>
                    <th>Category</th>
                    <th>Dosage</th>
                    <th>Buying Price</th>
                    <th>Selling Price</th>
                    <th>Reorder Level</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

                @foreach($medicines as $medicine)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>

                            <strong>
                                {{ $medicine->name }}
                            </strong>

                            @if($medicine->generic_name)

                                <br>

                                <small>
                                    {{ $medicine->generic_name }}
                                </small>

                            @endif

                        </td>

                        <td>
                            {{ $medicine->category->name }}
                        </td>

                        <td>
                            {{ $medicine->dosage_form }}
                            {{ $medicine->strength }}
                        </td>

                        <td>
                            KSh
                            {{ number_format($medicine->buying_price, 2) }}
                        </td>

                        <td>
                            KSh
                            {{ number_format($medicine->selling_price, 2) }}
                        </td>

                        <td>
                            {{ $medicine->reorder_level }}
                        </td>

                        <td>

                            <div class="actions">

                                <a href="{{ route('medicines.show', $medicine) }}"
                                   class="btn btn-primary">
                                    View
                                </a>

                                <a href="{{ route('medicines.edit', $medicine) }}"
                                   class="btn btn-warning">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('medicines.destroy', $medicine) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this medicine?');">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger">
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

            <h3>No medicines found</h3>

            <p>
                Start by adding your first medicine.
            </p>

            <a href="{{ route('medicines.create') }}"
               class="btn btn-primary">
                Add Medicine
            </a>

        </div>

    @endif

</div>

@endsection