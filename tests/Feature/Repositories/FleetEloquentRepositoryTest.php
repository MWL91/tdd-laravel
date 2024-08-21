<?php
declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Repositories\FleetEloquentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Money\Currency;
use Money\Money;
use Mwl91\Tdd\Domain\Fleet;
use Mwl91\Tdd\Domain\ValueObjects\FleetId;
use Mwl91\Tdd\Domain\ValueObjects\PickupPolicy;
use Tests\TestCase;
use Tests\WithCarBuilder;

final class FleetEloquentRepositoryTest extends TestCase
{
    use WithCarBuilder, WithFaker, RefreshDatabase;

    public function testCanCountFleets(): void
    {
        // Given:
        $fleetRepository = new FleetEloquentRepository();
        $fleet = new Fleet(FleetId::make());

        // When:
        $fleetRepository->create($fleet);

        // Then:
        $this->assertEquals(1, $fleetRepository->count());
        $this->assertDatabaseHas('fleets', [
            'id' => $fleet->getKey()
        ]);
    }

    public function testCanCountFleetsAndCars(): void
    {
        // Given:
        $cars = $this->carBuilder->getCars(4);
        $id = FleetId::make();
        $currency =  new Currency('EUR');
        $money = fn($from = 0, $to = 50): Money => new Money($this->faker->numberBetween(0, 50), $currency);

        $insuranceCost = $money();
        $cautionPercent = $this->faker->numberBetween(0, 100);
        $officePickupCost = $money();
        $airportPickupCost = $money();
        $addressPickupCost = $money();
        $overtimePickupCost = $money();


        $fleetRepository = new FleetEloquentRepository();
        $fleet = new Fleet(
            $id,
            $cars,
            $insuranceCost,
            $cautionPercent,
            new PickupPolicy(
                $officePickupCost,
                $airportPickupCost,
                $addressPickupCost,
                $overtimePickupCost
            )
        );

        // When:
        $fleetRepository->create($fleet);
        $fleetModel = \App\Models\Fleet::find($fleet->getKey());

        // Then:
        $this->assertEquals(1, $fleetRepository->count());
        $this->assertDatabaseHas('fleets', [
            'id' => $fleet->getKey()
        ]);
        $this->assertDatabaseCount('cars', 4);
        foreach($cars as $car) {
            $this->assertDatabaseHas('cars', [
                'id' => $car->getKey(),
                'class' => $car->getCarClass(),
                'brand' => $car->getBrand(),
                'model' => $car->getModel(),
                'car_type' => $car->getCarType(),
                'price' => $car->getPrice()?->getAmount() ?? 0,
                'price_currency' => $car->getPrice()?->getCurrency()?->getCode() ?? config('fleet.default_currency'),
                'transmission' => $car->getTransmission(),
                'fuel' => $car->getFuel(),
                'km' => $car->getKm(),
                'engine_capacity' => $car->getEngineCapacity(),
                'fleet_id' => $fleet->getKey()
            ]);
        }
        $this->assertCount(4, $fleetModel->cars);
    }

    public function testCanFindFleet()
    {
        // Given:
        $fleetRepository = new FleetEloquentRepository();

        $cars = $this->carBuilder->getCars(4);
        $id = FleetId::make();
        $currency =  new Currency('EUR');
        $money = fn($from = 0, $to = 50): Money => new Money($this->faker->numberBetween(0, 50), $currency);

        $insuranceCost = $money();
        $cautionPercent = $this->faker->numberBetween(0, 100);
        $officePickupCost = $money();
        $airportPickupCost = $money();
        $addressPickupCost = $money();
        $overtimePickupCost = $money();


        $fleetRepository = new FleetEloquentRepository();
        $fleet = new Fleet(
            $id,
            $cars,
            $insuranceCost,
            $cautionPercent,
            new PickupPolicy(
                $officePickupCost,
                $airportPickupCost,
                $addressPickupCost,
                $overtimePickupCost
            )
        );
        $fleetRepository->create($fleet);

        // When:
        $result = $fleetRepository->find($id);

        // Then:
        $this->assertEquals($fleet->getKey(), $result->getKey());
        $this->assertInstanceOf(Fleet::class, $result);
        $this->assertCount(4, $result->getCars());
    }


}
