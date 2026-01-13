<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $company = Company::factory()->create();

        return [
            'user_id' => User::factory()->create(['role' => 'employee']),
            'company_id' => $company->id,
            'position_id' => Position::factory()->create(['company_id' => $company->id]),
            'hire_date' => fake()->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
