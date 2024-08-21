<?php
declare(strict_types=1);

namespace Tests;

use Tests\Builder\CarBuilder;

trait WithCarBuilder
{
    protected readonly CarBuilder $carBuilder;

    public function setupWithCarBuilder(): void
    {
        $this->carBuilder = new CarBuilder();
    }
}
