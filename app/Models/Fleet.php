<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fleet extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'insurance_cost',
        'caution_percent',
        'office_pickup_cost',
        'airport_pickup_cost',
        'address_pickup_cost',
        'overtime_pickup_cost',
        'currency',
    ];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
