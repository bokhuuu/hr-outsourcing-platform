<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'user_id' => 3,
            'company_id' => 1,
            'position_id' => 3,
            'manager_id' => null,
            'hire_date' => '2024-01-15'
        ]);

        Employee::create([
            'user_id' => 5,
            'company_id' => 1,
            'position_id' => 2,
            'manager_id' => 1,
            'hire_date' => '2024-06-01'
        ]);

        Employee::create([
            'user_id' => 6,
            'company_id' => 1,
            'position_id' => 1,
            'manager_id' => 1,
            'hire_date' => '2024-08-15'
        ]);

        Employee::create([
            'user_id' => 4,
            'company_id' => 2,
            'position_id' => 6,
            'manager_id' => null,
            'hire_date' => '2024-02-01'
        ]);

        Employee::create([
            'user_id' => 7,
            'company_id' => 2,
            'position_id' => 6,
            'manager_id' => 4,
            'hire_date' => '2024-07-10'
        ]);

        Employee::create([
            'user_id' => 8,
            'company_id' => 2,
            'position_id' => 6,
            'manager_id' => 4,
            'hire_date' => '2024-09-01'
        ]);
    }
}
