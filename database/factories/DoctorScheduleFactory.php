<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoctorSchedule>
 */
class DoctorScheduleFactory extends Factory
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
            'day_of_week' => $this->faker->randomElement(['saturday','sunday','monday','tuesday','wednesday','thursday','friday']),
            'start_time' => '09:00:00',
            'end_time' => '15:00:00',
            'available' => true,
        ];
    }
}
