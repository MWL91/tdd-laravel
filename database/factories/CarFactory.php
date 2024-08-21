<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mwl91\Tdd\Domain\Car;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public function fromDomainModel(Car $car): Factory
    {
        return $this->state(function (array $attributes) use ($car) {
            return [
                'id' => $car->getKey(),
                'class' => $car->getCarClass(),
                'brand' => $car->getBrand(),
                'model' => $car->getModel(),
                'car_type' => $car->getCarType(),
                'price' => $car->getPrice()->getAmount(),
                'price_currency' => $car->getPrice()->getCurrency()->getCode(),
                'transmission' => $car->getTransmission(),
                'fuel' => $car->getFuel(),
                'km' => $car->getKm(),
                'engine_capacity' => $car->getEngineCapacity()
            ];
        });
    }
}
