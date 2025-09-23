<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Drug>
 */
class DrugFactory extends Factory
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
            'scientific_name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'manufacturer' => $this->faker->company,
            'price' => $this->faker->randomFloat(2,10,500),
            'insurance_covered' => $this->faker->boolean,
            'category' => $this->faker->word,
            'dosage_form' => $this->faker->randomElement(['Tablet','Injection','Syrup']),
            'is_active' => true,
        ];
    }
}
