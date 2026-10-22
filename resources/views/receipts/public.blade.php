@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 px-4 py-6 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-2xl">

        {{-- Receipt Card --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg">

            {{-- Header --}}
            <div class="bg-blue-700 px-6 py-6 text-center text-white">

                <h1 class="text-2xl font-bold">
                    Pharmacy Receipt
                </h1>

                <p class="mt-1 text-sm text-blue-100">
                    Sale Receipt
                </p>

            </div>

            {{-- Receipt Information --}}
            <div class="border-b border-gray-200 px-6 py-5">

                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Sale Number
                        </p>

                        <p class="mt-1 text-lg font-semibold text-gray-900">
                            {{ $sale->sale_number }}
                        </p>
                    </div>

                    <div class="sm:text-right">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Date
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900">
                            {{ $sale->created_at?->format('d M Y, h:i A') }}
                        </p>
                    </div>

                </div>

            </div>

            {{-- Customer Information --}}
            @if($sale->customer || $sale->customer_name || $sale->customer_phone)

                <div class="border-b border-gray-200 px-6 py-5">

                    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                        Customer
                    </h2>

                    <div class="mt-3 space-y-1">

                        @if($sale->customer)
                            <p class="font-medium text-gray-900">
                                {{ $sale->customer->name }}
                            </p>
                        @elseif($sale->customer_name)
                            <p class="font-medium text-gray-900">
                                {{ $sale->customer_name }}
                            </p>
                        @endif

                        @if($sale->customer_phone)
                            <p class="text-sm text-gray-600">
                                {{ $sale->customer_phone }}
                            </p>
                        @endif

                    </div>

                </div>

            @endif

            {{-- Medicines --}}
            <div class="px-6 py-5">

                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500">
                    Medicines
                </h2>

                <div class="space-y-4">

                    @forelse($sale->items as $item)

                        <div class="border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">

                            <div class="flex items-start justify-between gap-4">

                                <div class="min-w-0">

                                    <p class="font-semibold text-gray-900">
                                        {{ $item->medicine?->name ?? 'Medicine' }}
                                    </p>

                                    @if($item->medicine?->strength)
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ $item->medicine->strength }}
                                        </p>
                                    @endif

                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ $item->quantity }}
                                        ×
                                        KSh {{ number_format($item->unit_price, 2) }}
                                    </p>

                                </div>

                                <div class="shrink-0 text-right">

                                    <p class="font-semibold text-gray-900">
                                        KSh {{ number_format($item->subtotal, 2) }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    @empty

                        <p class="text-sm text-gray-500">
                            No medicines found for this sale.
                        </p>

                    @endforelse

                </div>

            </div>

            {{-- Totals --}}
            <div class="border-t border-gray-200 bg-gray-50 px-6 py-5">

                <div class="space-y-3">

                    <div class="flex items-center justify-between text-sm">

                        <span class="text-gray-600">
                            Subtotal
                        </span>

                        <span class="font-medium text-gray-900">
                            KSh {{ number_format($sale->subtotal, 2) }}
                        </span>

                    </div>

                    @if((float) $sale->discount > 0)

                        <div class="flex items-center justify-between text-sm">

                            <span class="text-gray-600">
                                Discount
                            </span>

                            <span class="font-medium text-green-600">
                                - KSh {{ number_format($sale->discount, 2) }}
                            </span>

                        </div>

                    @endif

                    <div class="border-t border-gray-200 pt-3">

                        <div class="flex items-center justify-between">

                            <span class="text-lg font-bold text-gray-900">
                                Total
                            </span>

                            <span class="text-xl font-bold text-blue-700">
                                KSh {{ number_format($sale->total_amount, 2) }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Payment Information --}}
            <div class="border-t border-gray-200 px-6 py-5">

                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500">
                    Payment Information
                </h2>

                <div class="space-y-3">

                    <div class="flex items-center justify-between">

                        <span class="text-sm text-gray-600">
                            Payment Method
                        </span>

                        <span class="font-medium capitalize text-gray-900">
                            {{ $sale->payment_method }}
                        </span>

                    </div>

                    {{-- Split Payment Breakdown --}}
                    @if($sale->payment_method === 'split')

                        @if((float) $sale->cash_amount > 0)
                            <div class="flex items-center justify-between">

                                <span class="text-sm text-gray-600">
                                    Cash
                                </span>

                                <span class="font-medium text-gray-900">
                                    KSh {{ number_format($sale->cash_amount, 2) }}
                                </span>

                            </div>
                        @endif

                        @if((float) $sale->mpesa_amount > 0)
                            <div class="flex items-center justify-between">

                                <span class="text-sm text-gray-600">
                                    M-Pesa
                                </span>

                                <span class="font-medium text-gray-900">
                                    KSh {{ number_format($sale->mpesa_amount, 2) }}
                                </span>

                            </div>
                        @endif

                        @if((float) $sale->card_amount > 0)
                            <div class="flex items-center justify-between">

                                <span class="text-sm text-gray-600">
                                    Card
                                </span>

                                <span class="font-medium text-gray-900">
                                    KSh {{ number_format($sale->card_amount, 2) }}
                                </span>

                            </div>
                        @endif

                    @endif

                    <div class="flex items-center justify-between">

                        <span class="text-sm text-gray-600">
                            Amount Paid
                        </span>

                        <span class="font-medium text-gray-900">
                            KSh {{ number_format($sale->amount_paid, 2) }}
                        </span>

                    </div>

                    @if((float) $sale->change_amount > 0)

                        <div class="flex items-center justify-between">

                            <span class="text-sm text-gray-600">
                                Change
                            </span>

                            <span class="font-medium text-green-600">
                                KSh {{ number_format($sale->change_amount, 2) }}
                            </span>

                        </div>

                    @endif

                </div>

            </div>

            {{-- QR Code --}}
            <div class="border-t border-gray-200 px-6 py-6 text-center">

                <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                    Scan Receipt
                </h2>

                <p class="mt-2 text-sm text-gray-600">
                    Scan this QR code with your phone to view this receipt.
                </p>

                <div class="mt-5 flex justify-center">

                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">

                        <img
                            src="{{ $sale->qrCodeDataUri() }}"
                            alt="Receipt QR Code"
                            class="h-56 w-56"
                        >

                    </div>

                </div>

            </div>

            {{-- Footer --}}
            <div class="border-t border-gray-200 px-6 py-6 text-center">

                <p class="text-sm font-medium text-gray-700">
                    Thank you for your purchase.
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Please keep this receipt for your records.
                </p>

            </div>

        </div>

    </div>

</div>

@endsection