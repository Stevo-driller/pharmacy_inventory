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
        'prescription_file',
        'status',
        'verified',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'prescription_date' => 'date',
        'verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Prescription $prescription) {
            if (empty($prescription->prescription_number)) {
                $prescription->prescription_number =
                    static::generatePrescriptionNumber();
            }

            if (empty($prescription->status)) {
                $prescription->status = 'pending';
            }
        });
    }

    protected static function generatePrescriptionNumber(): string
    {
        $year = now()->format('Y');

        $lastPrescription = static::query()
            ->whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        $nextNumber = $lastPrescription
            ? $lastPrescription->id + 1
            : 1;

        do {
            $prescriptionNumber = sprintf(
                'RX-%s-%06d',
                $year,
                $nextNumber
            );

            $exists = static::where(
                'prescription_number',
                $prescriptionNumber
            )->exists();

            if ($exists) {
                $nextNumber++;
            }
        } while ($exists);

        return $prescriptionNumber;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /*
     * Legacy relationship for prescriptions linked through sale_id.
     * Kept to preserve compatibility with existing records.
     */
    public function legacySale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    /*
     * A prescription can now have multiple dispensing sales.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'prescription_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}