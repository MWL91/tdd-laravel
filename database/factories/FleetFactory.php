<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mwl91\Tdd\Domain\Fleet;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fleet>
 */
class FleetFactory extends Factory
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

    public function fromDomainModel(Fleet $fleet): Factory
    {
        return $this->state(function (array $attributes) use ($fleet) {
            return [
                'id' => $fleet->getKey(),
                'insurance_cost' => $fleet->getInsuranceCost()->getAmount(),
                'caution_percent' => $fleet->getCautionPercent(),
                'office_pickup_cost' => $fleet->getOfficePickupCost()->getAmount(),
                'airport_pickup_cost' => $fleet->getAirportPickupCost()->getAmount(),
                'address_pickup_cost' => $fleet->getAddressPickupCost()->getAmount(),
                'overtime_pickup_cost' => $fleet->getOvertimePickupCost()->getAmount(),
                'currency' => $fleet->getInsuranceCost()->getCurrency()->getCode(),
            ];
        });
    }
}
