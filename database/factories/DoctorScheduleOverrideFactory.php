<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoctorScheduleOverride>
 */
class DoctorScheduleOverrideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'doctor_id' => Doctor::factory(),
            'date' => $this->faker->date(),
            'start_time' => '16:00:00',
            'end_time' => '17:00:00',
            'available' => false,
        ];
    }
}
