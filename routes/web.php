<?php

use Illuminate\Support\Facades\Route;
use Money\Currency;
use Money\Money;
use Mwl91\Tdd\Domain\Car;
use Mwl91\Tdd\Domain\Enums\CarBrand;
use Mwl91\Tdd\Domain\Enums\CarClass;
use Mwl91\Tdd\Domain\Enums\CarType;
use Mwl91\Tdd\Domain\Enums\Fuel;
use Mwl91\Tdd\Domain\Enums\Transmission;
use Mwl91\Tdd\Domain\Fleet;
use Mwl91\Tdd\Domain\ValueObjects\CarId;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/list', function () {
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

    echo new Fleet([$car, $car, $car]);
});
