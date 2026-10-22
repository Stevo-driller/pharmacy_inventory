<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrescriptionItem extends Model
{
    protected $fillable = [
        'prescription_id',
        'medicine_id',
        'dosage',
        'frequency',
        'duration',
        'route',
        'instructions',
        'quantity_prescribed',
        'quantity_dispensed',
        'notes',
    ];

    protected $casts = [
        'quantity_prescribed' => 'integer',
        'quantity_dispensed' => 'integer',
    ];

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(
            Prescription::class
        );
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(
            Medicine::class
        );
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(
            SaleItem::class
        );
    }

    public function remainingQuantity(): int
    {
        return max(
            0,
            $this->quantity_prescribed -
            $this->quantity_dispensed
        );
    }
}