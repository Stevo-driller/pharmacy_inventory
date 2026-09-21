<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{


    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

$table->foreignId('medicine_id')
                ->constrained('medicines')
                ->cascadeOnDelete();

            $table->foreignId('medicine_batch_id')
                ->nullable()
                ->constrained('medicine_batches')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('type', [
                'stock_in',
                'stock_out',
                'adjustment',
                'return',
                'expired',
                'damaged'
            ]);

            $table->integer('quantity');

            $table->integer('quantity_before')->default(0);
            $table->integer('quantity_after')->default(0);

            $table->text('reason')->nullable();

            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
