@extends('layouts.app')

@section('title', 'Stock Details')

@section('content')

<div class="header">

    <div>
        <h2>Stock Details</h2>

        <p style="margin-top: 5px; color: #666;">
            View detailed information about this medicine stock batch.
        </p>
    </div>

    <div class="header-actions">

        <a
            href="{{ route('stock.edit', $stock) }}"
            class="btn btn-primary"
        >
            Edit Stock
        </a>

        <a
            href="{{ route('stock.index') }}"
            class="btn btn-secondary"
        >
            ← Back to Stock
        </a>

    </div>

</div>


<div class="details-container">

    {{-- Medicine Information --}}
    <div class="details-section">

        <div class="section-title">
            <h3>Medicine Information</h3>
        </div>

        <div class="details-grid">

            <div class="detail-item">

                <span class="detail-label">
                    Medicine Name
                </span>

                <strong class="detail-value">
                    {{ $stock->medicine->name ?? 'N/A' }}
                </strong>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Generic Name
                </span>

                <strong class="detail-value">
                    {{ $stock->medicine->generic_name ?? 'N/A' }}
                </strong>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Brand Name
                </span>

                <strong class="detail-value">
                    {{ $stock->medicine->brand_name ?? 'N/A' }}
                </strong>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Dosage Form
                </span>

                <strong class="detail-value">
                    {{ $stock->medicine->dosage_form ?? 'N/A' }}
                </strong>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Strength
                </span>

                <strong class="detail-value">
                    {{ $stock->medicine->strength ?? 'N/A' }}
                </strong>

            </div>

        </div>

    </div>


    {{-- Batch Information --}}
    <div class="details-section">

        <div class="section-title">
            <h3>Batch Information</h3>
        </div>

        <div class="details-grid">

            <div class="detail-item">

                <span class="detail-label">
                    Batch Number
                </span>

                <strong class="detail-value">
                    {{ $stock->batch_number }}
                </strong>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Quantity
                </span>

                <strong class="detail-value quantity-value">
                    {{ number_format($stock->quantity) }}
                    units
                </strong>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Buying Price
                </span>

                <strong class="detail-value">
                    KSh {{ number_format($stock->buying_price, 2) }}
                </strong>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Selling Price
                </span>

                <strong class="detail-value">
                    KSh {{ number_format($stock->selling_price, 2) }}
                </strong>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Potential Stock Value
                </span>

                <strong class="detail-value">
                    KSh
                    {{ number_format(
                        $stock->quantity * $stock->selling_price,
                        2
                    ) }}
                </strong>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Potential Profit
                </span>

                <strong class="detail-value profit-value">
                    KSh
                    {{ number_format(
                        $stock->quantity *
                        ($stock->selling_price - $stock->buying_price),
                        2
                    ) }}
                </strong>

            </div>

        </div>

    </div>


    {{-- Dates --}}
    <div class="details-section">

        <div class="section-title">
            <h3>Dates</h3>
        </div>

        <div class="details-grid">

            <div class="detail-item">

                <span class="detail-label">
                    Manufactured Date
                </span>

                <strong class="detail-value">

                    @if ($stock->manufactured_date)

                        {{ \Carbon\Carbon::parse(
                            $stock->manufactured_date
                        )->format('d M Y') }}

                    @else

                        N/A

                    @endif

                </strong>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Expiry Date
                </span>

                <strong
                    class="detail-value
                    @if (
                        $stock->expiry_date &&
                        \Carbon\Carbon::parse($stock->expiry_date)->isPast()
                    )
                        expired
                    @elseif (
                        $stock->expiry_date &&
                        \Carbon\Carbon::parse($stock->expiry_date)
                            ->diffInDays(today()) <= 30
                    )
                        expiring
                    @endif"
                >

                    @if ($stock->expiry_date)

                        {{ \Carbon\Carbon::parse(
                            $stock->expiry_date
                        )->format('d M Y') }}

                    @else

                        N/A

                    @endif

                </strong>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Received Date
                </span>

                <strong class="detail-value">

                    @if ($stock->received_date)

                        {{ \Carbon\Carbon::parse(
                            $stock->received_date
                        )->format('d M Y') }}

                    @else

                        N/A

                    @endif

                </strong>

            </div>

        </div>

    </div>


    {{-- Supplier Information --}}
    <div class="details-section">

        <div class="section-title">
            <h3>Supplier Information</h3>
        </div>

        <div class="details-grid">

            <div class="detail-item">

                <span class="detail-label">
                    Supplier
                </span>

                <strong class="detail-value">
                    {{ $stock->supplier->name ?? 'N/A' }}
                </strong>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Company
                </span>

                <strong class="detail-value">
                    {{ $stock->supplier->company_name ?? 'N/A' }}
                </strong>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Phone
                </span>

                <strong class="detail-value">
                    {{ $stock->supplier->phone ?? 'N/A' }}
                </strong>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Email
                </span>

                <strong class="detail-value">
                    {{ $stock->supplier->email ?? 'N/A' }}
                </strong>

            </div>

        </div>

    </div>


    {{-- Status --}}
    <div class="details-section">

        <div class="section-title">
            <h3>Status</h3>
        </div>

        <div class="status-container">

            @if ($stock->status)

                <span class="status active">
                    ● Active
                </span>

            @else

                <span class="status inactive">
                    ● Inactive
                </span>

            @endif


            @if (
                $stock->expiry_date &&
                \Carbon\Carbon::parse($stock->expiry_date)->isPast()
            )

                <span class="status expired-status">
                    ⚠ Expired
                </span>

            @elseif (
                $stock->expiry_date &&
                \Carbon\Carbon::parse($stock->expiry_date)
                    ->diffInDays(today()) <= 30
            )

                <span class="status expiring-status">
                    ⚠ Expiring Soon
                </span>

            @endif

        </div>

    </div>


    {{-- Bottom Actions --}}
    <div class="bottom-actions">

        <a
            href="{{ route('stock.index') }}"
            class="btn btn-secondary"
        >
            ← Back to Stock
        </a>

        <a
            href="{{ route('stock.edit', $stock) }}"
            class="btn btn-primary"
        >
            Edit Stock
        </a>

        <form
            action="{{ route('stock.destroy', $stock) }}"
            method="POST"
            onsubmit="return confirm(
                'Are you sure you want to delete this stock batch?'
            );"
        >

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="btn btn-danger"
            >
                Delete Stock
            </button>

        </form>

    </div>

</div>


<style>

.header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.details-container {
    max-width: 1000px;
    margin: 0 auto;
}

.details-section {
    background: #fff;
    border-radius: 8px;
    padding: 22px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.section-title {
    border-bottom: 1px solid #eee;
    padding-bottom: 12px;
    margin-bottom: 18px;
}

.section-title h3 {
    margin: 0;
    color: #1e3a8a;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.detail-item {
    background: #f8fafc;
    padding: 15px;
    border-radius: 6px;
}

.detail-label {
    display: block;
    color: #777;
    font-size: 13px;
    margin-bottom: 6px;
}

.detail-value {
    display: block;
    color: #333;
    font-size: 15px;
}

.quantity-value {
    color: #1e3a8a;
}

.profit-value {
    color: #15803d;
}

.expired {
    color: #dc2626;
}

.expiring {
    color: #ea580c;
}

.status-container {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.status {
    display: inline-block;
    padding: 7px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.active {
    background: #dcfce7;
    color: #166534;
}

.inactive {
    background: #e5e7eb;
    color: #4b5563;
}

.expired-status {
    background: #fee2e2;
    color: #991b1b;
}

.expiring-status {
    background: #ffedd5;
    color: #9a3412;
}

.bottom-actions {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.bottom-actions form {
    margin: 0;
}

.btn-danger {
    background: #dc2626;
    color: #fff;
    border: none;
    cursor: pointer;
}

.btn-danger:hover {
    background: #b91c1c;
}

@media (max-width: 700px) {

    .details-grid {
        grid-template-columns: 1fr;
    }

    .header-actions {
        width: 100%;
    }

    .header-actions .btn {
        flex: 1;
        text-align: center;
    }

    .bottom-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .bottom-actions .btn {
        width: 100%;
        text-align: center;
    }

    .bottom-actions form {
        width: 100%;
    }

    .bottom-actions form .btn {
        width: 100%;
    }

}

</style>

@endsection