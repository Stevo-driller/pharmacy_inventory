<?php

namespace App\Http\Controllers;

use App\Models\Sale;

class ReceiptController extends Controller
{
    /**
     * Display a public receipt using its private receipt token.
     *
     * The token is used instead of exposing the sale database ID.
     */
    public function show(string $receipt_token)
    {
        $sale = Sale::where('receipt_token', $receipt_token)
            ->with([
                'customer',
                'items.medicine',
                'items.medicineBatch',
            ])
            ->firstOrFail();

        return view('receipts.public', compact('sale'));
    }
}