<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();

            // Prescription this medicine belongs to
            $table->foreignId('prescription_id')
                ->constrained('prescriptions')
                ->cascadeOnDelete();

            // Medicine prescribed
            $table->foreignId('medicine_id')
                ->constrained('medicines')
                ->restrictOnDelete();

            // Instructions from the prescription
            $table->string('dosage');
            $table->string('frequency');
            $table->string('duration');

            // Quantity the doctor prescribed
            $table->unsignedInteger('quantity_prescribed');

            // Optional dispensing information
            $table->unsignedInteger('quantity_dispensed')->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};