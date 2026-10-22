<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_unavailability_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('prescription_id')
                ->constrained('prescriptions')
                ->cascadeOnDelete();

            $table->foreignId('prescription_item_id')
                ->constrained('prescription_items')
                ->cascadeOnDelete();

            $table->foreignId('medicine_id')
                ->nullable()
                ->constrained('medicines')
                ->nullOnDelete();

            $table->text('reason');

            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(
                ['prescription_id', 'prescription_item_id'],
                'prescription_unavailability_lookup_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_unavailability_records');
    }
};