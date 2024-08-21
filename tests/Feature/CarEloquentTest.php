<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Money\Currency;
use Money\Money;
use Mwl91\Tdd\Domain\Car;
use Mwl91\Tdd\Domain\Enums\CarBrand;
use Mwl91\Tdd\Domain\Enums\CarClass;
use Mwl91\Tdd\Domain\Enums\CarType;
use Mwl91\Tdd\Domain\Enums\Fuel;
use Mwl91\Tdd\Domain\Enums\Transmission;
use Mwl91\Tdd\Domain\ValueObjects\CarId;
use Tests\TestCase;

class CarEloquentTest extends TestCase
{
    public function testCanCreateCar(): void
    {
        // Given:
        $id = CarId::make();
        $carClass = CarClass::G_PLUS;
        $transmission = Transmission::AUTOMATIC;
        $fuel = Fuel::BENZIN;
        $carType = CarType::HATCHBACK;
        $km = 180;
        $engineCapacity = 1368;
        $price = new Money(130000, new Currency("PLN"));
        $brand = CarBrand::FIAT;
        $model = "Abarth";

        // When:
        $car = new Car(
            $id,
            $carClass,
            $brand,
            $model,
            $carType,
            $price,
            $transmission,
            $fuel,
            $km,
            $engineCapacity
        );
        \App\Models\Car::factory()->fromDomainModel($car)->create();

        // Then:
        $this->assertDatabaseHas('cars', [
            'id' => $id,
            'class' => $carClass,
            'brand' => $brand,
            'model' => $model,
            'car_type' => $carType,
            'price' => $price->getAmount(),
            'price_currency' => $price->getCurrency()->getCode(),
            'transmission' => $transmission,
            'fuel' => $fuel,
            'km' => $km,
            'engine_capacity' => $engineCapacity
        ]);
    }
}
