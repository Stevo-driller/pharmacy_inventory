<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Receipt - {{ $sale->sale_number }}
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #f4f6f9;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .receipt-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #1e3a8a;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .divider {
            border-top: 1px dashed #999;
            margin: 15px 0;
        }

        .sale-info {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .sale-info div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 8px 4px;
            font-size: 13px;
            border-bottom: 1px solid #eee;
        }

        th {
            text-align: left;
        }

        td:last-child,
        th:last-child {
            text-align: right;
        }

        .summary {
            margin-top: 15px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #333;
            margin-top: 8px;
            padding-top: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
            color: #666;
        }

        .buttons {
            max-width: 500px;
            margin: 20px auto 0;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #1e3a8a;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        @media print {

            body {
                background: white;
                padding: 0;
            }

            .receipt-container {
                max-width: 100%;
                box-shadow: none;
                padding: 10px;
            }

            .buttons {
                display: none;
            }

        }

    </style>

</head>

<body>

<div class="receipt-container">

    {{-- Pharmacy Header --}}
    <div class="header">

        <h1>
            Pharmacy Inventory System
        </h1>

        <p>
            Sales Receipt
        </p>

    </div>


    <div class="divider"></div>


    {{-- Sale Information --}}
    <div class="sale-info">

        <div>
            <span>Receipt No:</span>

            <strong>
                {{ $sale->sale_number }}
            </strong>
        </div>

        <div>
            <span>Date:</span>

            <span>
                {{ $sale->created_at->format('d M Y, H:i') }}
            </span>
        </div>

        <div>
            <span>Customer:</span>

            <span>
                {{ $sale->customer_name ?? 'Walk-in Customer' }}
            </span>
        </div>

        @if($sale->customer_phone)

            <div>
                <span>Phone:</span>

                <span>
                    {{ $sale->customer_phone }}
                </span>
            </div>

        @endif

        <div>
            <span>Cashier:</span>

            <span>
                {{ $sale->user->name ?? 'System' }}
            </span>
        </div>

    </div>


    <div class="divider"></div>


    {{-- Items --}}
    <table>

        <thead>

            <tr>
                <th>Medicine</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>

        </thead>

        <tbody>

            @foreach($sale->items as $item)

                <tr>

                    <td>
                        {{ $item->medicine->name ?? 'Unknown Medicine' }}
                    </td>

                    <td>
                        {{ $item->quantity }}
                    </td>

                    <td>
                        KSh {{ number_format($item->unit_price, 2) }}
                    </td>

                    <td>
                        KSh {{ number_format($item->subtotal, 2) }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


    <div class="divider"></div>


    {{-- Payment Summary --}}
    <div class="summary">

        <div class="summary-row">

            <span>
                Subtotal
            </span>

            <strong>
                KSh {{ number_format($sale->subtotal, 2) }}
            </strong>

        </div>


        <div class="summary-row">

            <span>
                Discount
            </span>

            <strong>
                KSh {{ number_format($sale->discount, 2) }}
            </strong>

        </div>


        <div class="summary-row total">

            <span>
                TOTAL
            </span>

            <strong>
                KSh {{ number_format($sale->total_amount, 2) }}
            </strong>

        </div>


        <div class="summary-row">

            <span>
                Payment
            </span>

            <strong>
                {{ strtoupper($sale->payment_method) }}
            </strong>

        </div>


        <div class="summary-row">

            <span>
                Amount Paid
            </span>

            <strong>
                KSh {{ number_format($sale->amount_paid, 2) }}
            </strong>

        </div>


        <div class="summary-row">

            <span>
                Change
            </span>

            <strong>
                KSh {{ number_format($sale->change_amount, 2) }}
            </strong>

        </div>

    </div>


    <div class="divider"></div>


    {{-- Footer --}}
    <div class="footer">

        <p>
            Thank you for your purchase!
        </p>

        <p>
            Please keep this receipt for your records.
        </p>

    </div>

</div>


{{-- Buttons --}}
<div class="buttons">

    <a href="{{ route('sales.show', $sale) }}"
       class="btn btn-secondary">
        ← Back to Sale
    </a>

    <button onclick="window.print()"
            class="btn btn-primary">
        🖨 Print Receipt
    </button>

</div>

</body>
</html>