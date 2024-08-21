<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mwl91\Tdd\Domain\ValueObjects\FleetId;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fleets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('insurance_cost', 5, 2)->default(0);
            $table->integer('caution_percent')->default(0);
            $table->decimal('office_pickup_cost', 5, 2)->default(0);
            $table->decimal('airport_pickup_cost', 5, 2)->default(0);
            $table->decimal('address_pickup_cost', 5, 2)->default(0);
            $table->decimal('overtime_pickup_cost', 5, 2)->default(0);
            $table->string('currency');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleets');
    }
};
