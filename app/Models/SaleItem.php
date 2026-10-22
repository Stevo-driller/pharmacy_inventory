<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'medicine_id',
        'medicine_batch_id',
        'prescription_item_id',
        'quantity',
        'unit_price',
        'subtotal',
        'dosage',
        'frequency',
        'duration',
        'route',
        'instructions',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function medicineBatch(): BelongsTo
    {
        return $this->belongsTo(MedicineBatch::class);
    }

    public function prescriptionItem(): BelongsTo
    {
        return $this->belongsTo(
            PrescriptionItem::class
        );
    }
}