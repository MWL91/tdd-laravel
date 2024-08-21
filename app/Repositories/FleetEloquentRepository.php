<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Car;
use App\Models\Fleet;
use Money\Currency;
use Money\Money;
use Mwl91\Tdd\Domain\Enums\CarBrand;
use Mwl91\Tdd\Domain\Enums\CarClass;
use Mwl91\Tdd\Domain\Enums\CarType;
use Mwl91\Tdd\Domain\Enums\Fuel;
use Mwl91\Tdd\Domain\Enums\Transmission;
use Mwl91\Tdd\Domain\Fleet as FleetDomain;
use Mwl91\Tdd\Domain\ValueObjects\CarId;
use Mwl91\Tdd\Domain\ValueObjects\FleetId;
use Mwl91\Tdd\Domain\ValueObjects\PickupPolicy;
use Mwl91\Tdd\Infrastructure\Repositories\Fleet\FleetRepository;

final class FleetEloquentRepository implements FleetRepository
{

    public function count(): int
    {
        return Fleet::count();
    }

    public function find(FleetId $id): ?FleetDomain
    {
        $model = Fleet::with('cars')->find($id);
        $cars = $model->cars->map(fn(Car $car): \Mwl91\Tdd\Domain\Car => new \Mwl91\Tdd\Domain\Car(
            CarId::tryFrom($car->getKey()),
            CarClass::tryFrom($car->class),
            CarBrand::tryFrom($car->brand),
            $car->model,
            CarType::tryFrom($car->car_type),
            new Money($car->price, new Currency($car->price_currency)),
            Transmission::tryFrom($car->transmission),
            Fuel::tryFrom($car->fuel),
            $car->km,
            $car->engine_capacity
        ));

        $currency = new Currency($model->currency);

        return new FleetDomain(
            FleetId::tryFrom($model->getKey()),
            $cars->toArray(),
            new Money($model->insurance_cost, $currency),
            $model->caution_percent,
            new PickupPolicy(
                new Money($model->office_pickup_cost, $currency),
                new Money($model->airport_pickup_cost, $currency),
                new Money($model->address_pickup_cost, $currency),
                new Money($model->overtime_pickup_cost, $currency),
            )
        );
    }

    public function create(FleetDomain $fleet): void
    {
        Fleet::create([
            'id' => $fleet->getKey(),
            'insurance_cost' => $fleet->getInsuranceCost()?->getAmount() ?? 0,
            'caution_percent' => $fleet->getCautionPercent(),
            'office_pickup_cost' => $fleet->getOfficePickupCost()?->getAmount() ?? 0,
            'airport_pickup_cost' => $fleet->getAirportPickupCost()?->getAmount() ?? 0,
            'address_pickup_cost' => $fleet->getAddressPickupCost()?->getAmount() ?? 0,
            'overtime_pickup_cost' => $fleet->getOvertimePickupCost()?->getAmount() ?? 0,
            'currency' => $fleet->getInsuranceCost()?->getCurrency()->getCode() ?? config('fleet.default_currency'),
        ]);

        if(!empty($fleet->getCars())) {
            foreach ($fleet->getCars() as $car) {
                Car::create([
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
                    'engine_capacity' => $car->getEngineCapacity(),
                    'fleet_id' => $fleet->getKey()
                ]);
            }
        }
    }
}
