<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'supplier_id',
        'batch_number',
        'quantity',
        'buying_price',
        'selling_price',
        'manufactured_date',
        'expiry_date',
        'received_date',
        'status',
    ];

    protected $casts = [
        'manufactured_date' => 'date',
        'expiry_date' => 'date',
        'received_date' => 'date',
        'status' => 'boolean',
        'buying_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function saleItems()
{
    return $this->hasMany(SaleItem::class);
}

}