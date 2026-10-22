<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class Sale extends Model
{
    protected $fillable = [
        'sale_number',
        'user_id',
        'customer_id',
        'prescription_id',
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
        'receipt_token',
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

    protected static function booted(): void
    {
        static::creating(function (Sale $sale) {
            if (empty($sale->receipt_token)) {
                $sale->receipt_token = static::generateUniqueReceiptToken();
            }
        });
    }

    protected static function generateUniqueReceiptToken(): string
    {
        do {
            $token = Str::random(40);
        } while (static::where('receipt_token', $token)->exists());

        return $token;
    }

    public function receiptUrl(): string
    {
        if (empty($this->receipt_token)) {
            $this->receipt_token = static::generateUniqueReceiptToken();
            $this->saveQuietly();
        }

        return rtrim(config('app.url'), '/') . '/r/' . $this->receipt_token;
    }

    public function qrCodeDataUri(): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        $svg = $writer->writeString(
            $this->receiptUrl()
        );

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Hospital prescription
    |--------------------------------------------------------------------------
    */
    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}