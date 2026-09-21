<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends Model
{
    protected $fillable = [
        'customer_id',
        'sale_id',
        'prescription_number',
        'prescriber_name',
        'prescription_date',
        'notes',
        'verified',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'prescription_date' => 'date',
        'verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Prescription belongs to a customer.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Prescription can belong to a sale.
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Prescription has many medicines.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}