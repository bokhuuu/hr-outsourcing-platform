<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $employee = Employee::factory()->create();

        return [
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
            'date' => fake()->dateTimeBetween('-1 month', 'now'),
            'check_in_time' => '09:00:00',
            'check_out_time' => '18:00:00',
        ];
    }
}
