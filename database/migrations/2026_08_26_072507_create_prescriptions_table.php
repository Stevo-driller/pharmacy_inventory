<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();

            // Customer who received the prescription
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            // Sale associated with the prescription
            $table->foreignId('sale_id')
                ->nullable()
                ->constrained('sales')
                ->nullOnDelete();

            // Prescription details
            $table->string('prescription_number')->nullable();
            $table->string('prescriber_name');
            $table->date('prescription_date')->nullable();

            $table->text('notes')->nullable();

            // Verification / dispensing information
            $table->boolean('verified')->default(false);
            $table->string('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};