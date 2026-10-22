<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('receipt_token', 40)->nullable()->after('sale_number');
        });

        DB::table('sales')
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, function ($sales) {
                foreach ($sales as $sale) {
                    do {
                        $token = Str::random(40);
                    } while (
                        DB::table('sales')
                            ->where('receipt_token', $token)
                            ->exists()
                    );

                    DB::table('sales')
                        ->where('id', $sale->id)
                        ->update([
                            'receipt_token' => $token,
                        ]);
                }
            });

        Schema::table('sales', function (Blueprint $table) {
            $table->unique('receipt_token');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropUnique(['receipt_token']);
            $table->dropColumn('receipt_token');
        });
    }
};