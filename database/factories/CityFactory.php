<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Governorate;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->city,
            'governorate_id' => Governorate::factory(),
        ];
    }
}
