<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $employee = Employee::factory()->create();
        $startDate = fake()->dateTimeBetween('now', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, '+1 month');

        return [
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
            'leave_type' => fake()->randomElement(['vacation', 'sick', 'personal']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'reason' => fake()->sentence(),
            'status' => 'pending',
            'created_by' => $employee->user_id,
        ];
    }
}
