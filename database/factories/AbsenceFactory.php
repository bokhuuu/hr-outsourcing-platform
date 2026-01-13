<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absence>
 */
class AbsenceFactory extends Factory
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
            'reason' => fake()->sentence(),
            'created_by' => User::factory()->create(['role' => 'hr'])->id,
        ];
    }
}
