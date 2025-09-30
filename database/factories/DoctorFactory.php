<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\HealthCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'specialty' => $this->faker->randomElement([
                'Cardiology',
                'Pediatrics',
                'Neurology',
                'Orthopedics',
                'Dermatology',
                'General Medicine',
                'Ophthalmology',
            ]),
            'phone' => $this->faker->phoneNumber,
            'health_center_id' => HealthCenter::factory(),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Doctor $doctor) {
            // Create 1-3 schedules for each doctor
            \App\Models\DoctorSchedule::factory()
                ->count($this->faker->numberBetween(1, 3))
                ->create(['doctor_id' => $doctor->id]);


            \App\Models\DoctorScheduleException::factory()
                ->count($this->faker->numberBetween(0, 2))
                ->create(['doctor_id' => $doctor->id]);
        });
    }
}
