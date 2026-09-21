<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();



$table->foreignId('category_id')
->constrained('categories')
->cascadeOnDelete();

$table->string('name');
$table->string('generic_name')->nullable();
$table->string('brand_name')->nullable();
$table->string('dosage_form')->nullable();
$table->string('strength')->nullable();

$table->string('barcode')->nullable()->unique();

$table->decimal('buying_price' , 10 , 2)->default(0);
$table->decimal('selling_price', 10, 2)->default(0);

            $table->integer('reorder_level')->default(10);

            $table->boolean('prescription_required')->default(false);
            $table->boolean('status')->default(true);

            $table->text('description')->nullable();


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
