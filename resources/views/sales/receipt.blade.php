<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Receipt - {{ $sale->sale_number }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 15px;
            background: #f3f4f6;
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
        }

        .receipt-wrapper {
            max-width: 550px;
            margin: 0 auto;
        }

        .receipt {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #d1d5db;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #111827;
        }

        .header p {
            margin: 5px 0;
            color: #6b7280;
            font-size: 14px;
        }

        .receipt-title {
            margin-top: 12px;
            font-size: 18px;
            font-weight: bold;
            color: #374151;
        }

        .sale-info {
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 5px 0;
            font-size: 14px;
        }

        .info-label {
            color: #6b7280;
        }

        .info-value {
            text-align: right;
            font-weight: 600;
            color: #111827;
            overflow-wrap: anywhere;
        }

        .prescription-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 12px;
            margin: 15px 0;
        }

        .prescription-box strong {
            color: #1d4ed8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            text-align: left;
            font-size: 12px;
            color: #6b7280;
            padding: 10px 5px;
            border-bottom: 2px solid #e5e7eb;
        }

        td {
            padding: 10px 5px;
            font-size: 13px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
        }

        th:last-child,
        td:last-child {
            text-align: right;
        }

        .medicine-name {
            font-weight: 600;
            color: #111827;
        }

        .batch-number {
            display: block;
            margin-top: 3px;
            font-size: 11px;
            color: #9ca3af;
        }

        .summary {
            margin-top: 20px;
            border-top: 2px dashed #d1d5db;
            padding-top: 15px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 6px 0;
            font-size: 14px;
        }

        .summary-row span:last-child {
            text-align: right;
        }

        .summary-row.total {
            margin-top: 8px;
            padding-top: 12px;
            border-top: 2px solid #111827;
            font-size: 18px;
            font-weight: bold;
        }

        .payment-breakdown {
            margin-top: 10px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .payment-breakdown h4 {
            margin: 0 0 8px;
            font-size: 13px;
            color: #374151;
        }

        .qr-section {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 2px dashed #d1d5db;
            text-align: center;
        }

        .qr-section h3 {
            margin: 0 0 8px;
            font-size: 17px;
            color: #111827;
        }

        .qr-section p {
            margin: 0 0 15px;
            font-size: 13px;
            color: #6b7280;
        }

        #receipt-qrcode {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 180px;
        }

        #receipt-qrcode img,
        #receipt-qrcode canvas {
            max-width: 180px;
            max-height: 180px;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 15px;
            border-top: 2px dashed #d1d5db;
            color: #6b7280;
            font-size: 12px;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            border: none;
            border-radius: 7px;
            padding: 10px 18px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-back {
            background: #e5e7eb;
            color: #111827;
        }

        .btn-print {
            background: #2563eb;
            color: #ffffff;
        }

        .btn:hover {
            opacity: 0.9;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .receipt {
                padding: 20px 15px;
                border-radius: 8px;
            }

            .header h1 {
                font-size: 20px;
            }

            .info-row {
                font-size: 13px;
            }

            th,
            td {
                font-size: 12px;
                padding: 8px 3px;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .receipt-wrapper {
                max-width: 100%;
            }

            .receipt {
                box-shadow: none;
                border-radius: 0;
                padding: 10px;
            }

            .actions {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="receipt-wrapper">
    <div class="receipt">

        {{-- HEADER --}}
        <div class="header">
            <h1>Pharmacy Inventory System</h1>
            <p>Hospital Pharmacy</p>

            <div class="receipt-title">
                SALES RECEIPT
            </div>
        </div>

        {{-- SALE INFORMATION --}}
        <div class="sale-info">
            <div class="info-row">
                <span class="info-label">Receipt No.</span>
                <span class="info-value">{{ $sale->sale_number }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Date</span>
                <span class="info-value">
                    {{ $sale->created_at?->format('d M Y, H:i') }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Customer</span>
                <span class="info-value">
                    {{ $sale->customer_name ?: 'Walk-in Customer' }}
                </span>
            </div>

            @if($sale->customer_phone)
                <div class="info-row">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ $sale->customer_phone }}</span>
                </div>
            @endif

            <div class="info-row">
                <span class="info-label">Cashier</span>
                <span class="info-value">
                    {{ $sale->user?->name ?? 'System' }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Sale Type</span>
                <span class="info-value">
                    @switch($sale->sale_type ?? 'walk_in')
                        @case('hospital_prescription')
                            Hospital Prescription
                            @break

                        @case('external_prescription')
                            External Prescription
                            @break

                        @default
                            Walk-in Customer
                    @endswitch
                </span>
            </div>
        </div>

        {{-- HOSPITAL PRESCRIPTION INFORMATION ONLY --}}
        @if(
            ($sale->sale_type ?? null) === 'hospital_prescription'
            && $sale->prescription
        )
            <div class="prescription-box">
                <strong>Hospital Prescription Sale</strong>

                <div style="margin-top: 5px; font-size: 13px;">
                    Prescription No:
                    {{ $sale->prescription->prescription_number ?: 'N/A' }}
                </div>

                @if($sale->prescription->prescriber_name)
                    <div style="margin-top: 4px; font-size: 13px;">
                        Prescriber:
                        {{ $sale->prescription->prescriber_name }}
                    </div>
                @endif
            </div>
        @endif

        {{-- MEDICINES DISPENSED --}}
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
                @forelse($sale->items as $item)
                    <tr>
                        <td>
                            <span class="medicine-name">
                                {{ $item->medicine?->name ?? 'Medicine' }}
                            </span>

                            @if($item->medicineBatch)
                                <span class="batch-number">
                                    Batch: {{ $item->medicineBatch->batch_number }}
                                </span>
                            @endif
                        </td>

                        <td>{{ $item->quantity }}</td>

                        <td>
                            KSh {{ number_format($item->unit_price, 2) }}
                        </td>

                        <td>
                            KSh {{ number_format($item->subtotal, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No medicines recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAYMENT SUMMARY --}}
        <div class="summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>KSh {{ number_format($sale->subtotal, 2) }}</span>
            </div>

            @if($sale->discount > 0)
                <div class="summary-row">
                    <span>Discount</span>
                    <span>- KSh {{ number_format($sale->discount, 2) }}</span>
                </div>
            @endif

            <div class="summary-row total">
                <span>Total</span>
                <span>KSh {{ number_format($sale->total_amount, 2) }}</span>
            </div>

            <div class="summary-row">
                <span>Payment Method</span>
                <span>{{ ucfirst($sale->payment_method) }}</span>
            </div>

            <div class="summary-row">
                <span>Amount Paid</span>
                <span>KSh {{ number_format($sale->amount_paid, 2) }}</span>
            </div>

            @if($sale->change_amount > 0)
                <div class="summary-row">
                    <span>Change</span>
                    <span>KSh {{ number_format($sale->change_amount, 2) }}</span>
                </div>
            @endif

            {{-- SPLIT PAYMENT BREAKDOWN --}}
            @if($sale->payment_method === 'split')
                <div class="payment-breakdown">
                    <h4>Payment Breakdown</h4>

                    <div class="summary-row">
                        <span>Cash</span>
                        <span>
                            KSh {{ number_format($sale->cash_amount ?? 0, 2) }}
                        </span>
                    </div>

                    <div class="summary-row">
                        <span>M-Pesa</span>
                        <span>
                            KSh {{ number_format($sale->mpesa_amount ?? 0, 2) }}
                        </span>
                    </div>

                    <div class="summary-row">
                        <span>Card</span>
                        <span>
                            KSh {{ number_format($sale->card_amount ?? 0, 2) }}
                        </span>
                    </div>
                </div>
            @endif
        </div>

        {{-- QR CODE --}}
        <div class="qr-section">
            <h3>View Receipt on Your Phone</h3>

            <p>
                Scan this QR code using your phone camera
                to view this receipt.
            </p>

            <div id="receipt-qrcode"></div>
        </div>

        {{-- FOOTER --}}
        <div class="footer">
            <p>Thank you for using our pharmacy.</p>
            <p>Please keep this receipt for your records.</p>
        </div>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="actions">
        <a
            href="{{ route('sales.index') }}"
            class="btn btn-back"
        >
            ← Back to POS
        </a>

        <button
            type="button"
            onclick="window.print()"
            class="btn btn-print"
        >
            🖨 Print Receipt
        </button>
    </div>
</div>

{{-- QR CODE LIBRARY --}}
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const qrContainer = document.getElementById('receipt-qrcode');
        const receiptUrl = @json($mobileReceiptUrl);

        if (!qrContainer || !receiptUrl || typeof QRCode === 'undefined') {
            return;
        }

        new QRCode(qrContainer, {
            text: receiptUrl,
            width: 180,
            height: 180,
            correctLevel: QRCode.CorrectLevel.M
        });
    });
</script>

</body>
</html>