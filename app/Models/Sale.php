<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Prescription;
use App\Models\Customer;

class Sale extends Model
{
    protected $fillable = [
        'sale_number',
        'user_id',
        'customer_id',
        'customer_name',
        'customer_phone',
        'subtotal',
        'discount',
        'total_amount',
        'payment_method',
        'amount_paid',
        'cash_amount',
'mpesa_amount',
'card_amount',
        'change_amount',
        'status',
        
    ];

    protected $casts = [
    'subtotal' => 'decimal:2',
    'discount' => 'decimal:2',
    'total_amount' => 'decimal:2',
    'amount_paid' => 'decimal:2',
    'cash_amount' => 'decimal:2',
    'mpesa_amount' => 'decimal:2',
    'card_amount' => 'decimal:2',
    'change_amount' => 'decimal:2',
];

    /**
     * User/Cashier who made the sale.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Customer/Patient who made the purchase.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Items included in the sale.
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Prescription associated with this sale.
     */
    public function prescription(): HasOne
    {
        return $this->hasOne(Prescription::class);
    }
}