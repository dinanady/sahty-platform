<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Drug>
 */
class DrugFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true), // اسم عشوائي من كلمتين
            'scientific_name' => $this->faker->unique()->word(), // اسم علمي عشوائي
            'description' => $this->faker->sentence(6), // وصف قصير
            'manufacturer' => $this->faker->company(), // اسم شركة
            'price' => $this->faker->randomFloat(2, 5, 300), // من 5 إلى 300 جنيه
            'insurance_covered' => $this->faker->boolean(70), // 70% true
            'category' => $this->faker->word(), // تصنيف عشوائي
            'dosage_form' => $this->faker->randomElement(['tablet', 'capsule', 'syrup', 'injection']), // شكل جرعة بسيط
            'approval_status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'submitted_by_center_id' => $this->faker->optional(0.3)->numberBetween(1, 10),
            'is_government_approved' => $this->faker->boolean(80),
            'is_active' => $this->faker->boolean(90),
            'approved_at' => $this->faker->optional(0.8)->dateTimeThisYear(),
        ];
    }

    public function governmentApproved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_government_approved' => true,
            'approval_status' => 'approved',
            'submitted_by_center_id' => null,
            'approved_at' => $this->faker->dateTimeThisYear(),
        ]);
    }

    public function centerSubmitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_government_approved' => false,
            'approval_status' => $this->faker->randomElement(['pending', 'approved']),
            'submitted_by_center_id' => $this->faker->numberBetween(1, 10),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'rejected',
            'is_active' => false,
        ]);
    }
}
