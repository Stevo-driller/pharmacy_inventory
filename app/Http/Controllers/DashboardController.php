<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Sale;

class DashboardController extends Controller
{
    /**
     * Display the pharmacy dashboard.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC DASHBOARD STATISTICS
        |--------------------------------------------------------------------------
        */

        // Total active medicines
        $totalMedicines = Medicine::where('status', true)->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL USABLE STOCK
        |--------------------------------------------------------------------------
        |
        | Only count stock that:
        | - Has quantity greater than 0
        | - Is active
        | - Has not expired
        |
        */

        $usableBatches = MedicineBatch::where('quantity', '>', 0)
            ->where('status', true)
            ->whereDate('expiry_date', '>=', today())
            ->get();

        $totalStock = $usableBatches->sum('quantity');


        /*
        |--------------------------------------------------------------------------
        | TOTAL INVENTORY VALUE
        |--------------------------------------------------------------------------
        */

        $stockValue = $usableBatches->sum(function ($batch) {
            return $batch->quantity * $batch->buying_price;
        });


        /*
        |--------------------------------------------------------------------------
        | TODAY'S COMPLETED SALES
        |--------------------------------------------------------------------------
        */

        $todaySales = Sale::where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | LOAD ACTIVE MEDICINES WITH USABLE BATCHES
        |--------------------------------------------------------------------------
        */

        $medicines = Medicine::where('status', true)
            ->with([
                'batches' => function ($query) {
                    $query
                        ->where('quantity', '>', 0)
                        ->where('status', true)
                        ->whereDate('expiry_date', '>=', today());
                }
            ])
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | CALCULATE ACTUAL USABLE STOCK PER MEDICINE
        |--------------------------------------------------------------------------
        */

        $medicines->each(function ($medicine) {

            $medicine->total_quantity = $medicine->batches->sum('quantity');

        });


        /*
        |--------------------------------------------------------------------------
        | OUT OF STOCK MEDICINES
        |--------------------------------------------------------------------------
        |
        | No usable stock remaining.
        |
        */

        $outOfStockMedicines = $medicines
            ->filter(function ($medicine) {

                return (int) $medicine->total_quantity === 0;

            })
            ->values();


        $outOfStockCount = $outOfStockMedicines->count();


        /*
        |--------------------------------------------------------------------------
        | LOW STOCK MEDICINES
        |--------------------------------------------------------------------------
        |
        | Stock is greater than zero but at or below reorder level.
        |
        */

        $lowStockMedicines = $medicines
            ->filter(function ($medicine) {

                $stock = (int) $medicine->total_quantity;

                $reorderLevel = (int) ($medicine->reorder_level ?? 0);

                return $stock > 0
                    && $stock <= $reorderLevel;

            })
            ->sortBy(function ($medicine) {

                return (int) $medicine->total_quantity;

            })
            ->values();


        $lowStockCount = $lowStockMedicines->count();


        /*
        |--------------------------------------------------------------------------
        | EXPIRING SOON
        |--------------------------------------------------------------------------
        |
        | Batches with stock that expire:
        |
        | TODAY -> NEXT 30 DAYS
        |
        */

        $expiringBatches = MedicineBatch::with('medicine')
            ->where('quantity', '>', 0)
            ->where('status', true)
            ->whereDate('expiry_date', '>=', today())
            ->whereDate(
                'expiry_date',
                '<=',
                today()->copy()->addDays(30)
            )
            ->orderBy('expiry_date')
            ->get();


        $expiringCount = $expiringBatches->count();


        /*
        |--------------------------------------------------------------------------
        | EXPIRED STOCK
        |--------------------------------------------------------------------------
        |
        | Batches that still contain stock but have expired.
        |
        */

        $expiredBatches = MedicineBatch::with('medicine')
            ->where('quantity', '>', 0)
            ->where('status', true)
            ->whereDate('expiry_date', '<', today())
            ->orderBy('expiry_date')
            ->get();


        $expiredCount = $expiredBatches->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL COMPLETED SALES
        |--------------------------------------------------------------------------
        */

        $totalSales = Sale::where('status', 'completed')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | RECENT SALES
        |--------------------------------------------------------------------------
        */

        $recentSales = Sale::with([
            'items.medicine',
            'customer'
        ])
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RETURN DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(
            'totalMedicines',

            'totalStock',
            'stockValue',
            'todaySales',

            'outOfStockMedicines',
            'outOfStockCount',

            'lowStockMedicines',
            'lowStockCount',

            'expiringBatches',
            'expiringCount',

            'expiredBatches',
            'expiredCount',

            'totalSales',
            'recentSales'
        ));
    }
}