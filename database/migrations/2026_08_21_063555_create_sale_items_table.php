<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {

            $table->id();

            // Sale this item belongs to
            $table->foreignId('sale_id')
                ->constrained('sales')
                ->cascadeOnDelete();

            // Medicine being sold
            $table->foreignId('medicine_id')
                ->constrained('medicines')
                ->restrictOnDelete();

            // Specific batch used for the sale
            $table->foreignId('medicine_batch_id')
                ->constrained('medicine_batches')
                ->restrictOnDelete();

            // Quantity sold
            $table->integer('quantity');

            // Price at the time of sale
            $table->decimal('unit_price', 10, 2);

            // quantity × unit_price
            $table->decimal('subtotal', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};