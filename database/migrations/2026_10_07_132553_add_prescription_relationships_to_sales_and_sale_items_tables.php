<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('prescription_id')
                ->nullable()
                ->after('customer_id')
                ->constrained('prescriptions')
                ->nullOnDelete();
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->foreignId('prescription_item_id')
                ->nullable()
                ->after('medicine_batch_id')
                ->constrained('prescription_items')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropForeign([
                'prescription_item_id',
            ]);

            $table->dropColumn('prescription_item_id');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign([
                'prescription_id',
            ]);

            $table->dropColumn('prescription_id');
        });
    }
};