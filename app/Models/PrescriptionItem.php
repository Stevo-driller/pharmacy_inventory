<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrescriptionItem extends Model
{
    protected $fillable = [
        'prescription_id',
        'medicine_id',
        'dosage',
        'frequency',
        'duration',
        'quantity_prescribed',
        'quantity_dispensed',
        'notes',
    ];

    protected $casts = [
        'quantity_prescribed' => 'integer',
        'quantity_dispensed' => 'integer',
    ];

    /**
     * Item belongs to a prescription.
     */
    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    /**
     * Item belongs to a medicine.
     */
    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}