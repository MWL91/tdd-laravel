<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Money\Currency;
use Money\Money;
use Mwl91\Tdd\Domain\Fleet;
use Mwl91\Tdd\Domain\ValueObjects\FleetId;
use Mwl91\Tdd\Domain\ValueObjects\PickupPolicy;
use Tests\TestCase;

class FleetEloquentTest extends TestCase
{
    use WithFaker;

    public function testCanCreateCar(): void
    {
        // Given:
        $id = FleetId::make();
        $currency =  new Currency('EUR');
        $money = fn($from = 0, $to = 50): Money => new Money($this->faker->numberBetween(0, 50), $currency);

        $insuranceCost = $money();
        $cautionPercent = $this->faker->numberBetween(0, 100);
        $officePickupCost = $money();
        $airportPickupCost = $money();
        $addressPickupCost = $money();
        $overtimePickupCost = $money();

        // When:
        $fleet = new Fleet(
            $id,
            [],
            $insuranceCost,
            $cautionPercent,
            new PickupPolicy(
                $officePickupCost,
                $airportPickupCost,
                $addressPickupCost,
                $overtimePickupCost
            )
        );
        \App\Models\Fleet::factory()->fromDomainModel($fleet)->create();

        // Then:
        $this->assertDatabaseHas('fleets', [
            'id' => $id,
            'insurance_cost' => $insuranceCost->getAmount(),
            'caution_percent' => $cautionPercent,
            'office_pickup_cost' => $officePickupCost->getAmount(),
            'airport_pickup_cost' => $airportPickupCost->getAmount(),
            'address_pickup_cost' => $addressPickupCost->getAmount(),
            'overtime_pickup_cost' => $overtimePickupCost->getAmount(),
            'currency' => $currency->getCode(),
        ]);
    }
}
