<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vaccine>
 */
class VaccineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'description' => $this->faker->sentence,
            'age_months_min' => 0,
            'age_months_max' => 12,
            'doses_required' => 1,
            'interval_days' => null,
            'side_effects' => $this->faker->sentence,
            'precautions' => $this->faker->sentence,
            'is_active' => true,
        ];
    }
}
