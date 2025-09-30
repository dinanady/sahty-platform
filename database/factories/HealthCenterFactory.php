<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Governorate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HealthCenter>
 */
class HealthCenterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'phone' => $this->faker->unique()->numerify('01#########'),
            'city_id' => City::factory(),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'working_hours' => json_encode(['sat' => '09:00-17:00']),
            'available_doses' => $this->faker->numberBetween(0, 50),
            'is_active' => true,
        ];
    }
}
