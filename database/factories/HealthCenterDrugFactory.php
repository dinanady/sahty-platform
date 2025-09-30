<?php

namespace Database\Factories;

use App\Models\Drug;
use App\Models\HealthCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HealthCenterDrug>
 */
class HealthCenterDrugFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'health_center_id' => 4,
            'drug_id' => Drug::factory(),
            'availability' => $this->faker->boolean,
            'stock' => $this->faker->numberBetween(0, 1000),
        ];
    }

}
