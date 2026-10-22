<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Customer extends Model
{
    protected $fillable = [
        'patient_number',
        'name',
        'phone',
        'email',
        'address',
        'notes',
    ];

    protected static function booted(): void
    {
        static::creating(function (Customer $customer) {
            if (empty($customer->patient_number)) {
                $customer->patient_number =
                    static::generatePatientNumber();
            }
        });
    }

    protected static function generatePatientNumber(): string
    {
        do {
            $patientNumber =
                'HSP-' .
                now()->format('Y') .
                '-' .
                strtoupper(
                    Str::random(6)
                );
        } while (
            static::where(
                'patient_number',
                $patientNumber
            )->exists()
        );

        return $patientNumber;
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }
}