<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('medicine_batches', function (Blueprint $table) {
            $table->id();

$table->foreignId('medicine_id')
->constrained('medicines')
->cascadeOnDelete();


            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained('suppliers')
                ->nullOnDelete();

            $table->string('batch_number');

            $table->integer('quantity')->default(0);

            $table->decimal('buying_price', 10, 2)->default(0);
            $table->decimal('selling_price', 10, 2)->default(0);

            $table->date('manufactured_date')->nullable();
            $table->date('expiry_date');

            $table->date('received_date')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('medicine_batches');
    }
};
