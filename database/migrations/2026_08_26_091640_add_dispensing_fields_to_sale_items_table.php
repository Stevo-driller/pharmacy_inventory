<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {

            $table->string('dosage')
                ->nullable()
                ->after('subtotal');

            $table->string('frequency')
                ->nullable()
                ->after('dosage');

            $table->string('duration')
                ->nullable()
                ->after('frequency');

            $table->string('route')
                ->nullable()
                ->after('duration');

            $table->text('instructions')
                ->nullable()
                ->after('route');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {

            $table->dropColumn([
                'dosage',
                'frequency',
                'duration',
                'route',
                'instructions',
            ]);

        });
    }
};