<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mwl91\Tdd\Domain\Enums\CarBrand;
use Mwl91\Tdd\Domain\Enums\CarClass;
use Mwl91\Tdd\Domain\Enums\CarType;
use Mwl91\Tdd\Domain\Enums\Fuel;
use Mwl91\Tdd\Domain\Enums\Transmission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('class', array_map(fn($case) => $case->value, CarClass::cases()));
            $table->enum('brand', array_map(fn($case) => $case->value, CarBrand::cases()));
            $table->string('model')->nullable();
            $table->enum('car_type', array_map(fn($case) => $case->value, CarType::cases()));
            $table->decimal('price')->nullable();
            $table->string('price_currency')->nullable();
            $table->enum('transmission', array_map(fn($case) => $case->value, Transmission::cases()));
            $table->enum('fuel', array_map(fn($case) => $case->value, Fuel::cases()));
            $table->integer('km')->nullable();
            $table->integer('engine_capacity')->nullable();
            $table->uuid('fleet_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
