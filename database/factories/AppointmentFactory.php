<?php

namespace Database\Factories;

use App\Models\HealthCenter;
use App\Models\User;
use App\Models\Vaccine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'user_id' => User::factory(),
            'child_name' => $this->faker->firstName,
            'child_birth_date' => $this->faker->date(),
            'vaccine_id' => Vaccine::factory(),
            'health_center_id' => HealthCenter::factory(),
            'appointment_date' => $this->faker->dateTimeBetween('+1 days', '+1 month'),
            'appointment_time' => $this->faker->time('H:i:s'),
            'status' => 'مجدول',
            'dose_number' => 1,
            'notes' => $this->faker->sentence,
        ];
    }
}
