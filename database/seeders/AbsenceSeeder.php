<?php

namespace Database\Seeders;

use App\Models\Absence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbsenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Absence::create([
            'employee_id' => 2,
            'company_id' => 1,
            'date' => '2026-01-03',
            'reason' => 'Sick leave',
            'created_by' => 1
        ]);

        Absence::create([
            'employee_id' => 6,
            'company_id' => 2,
            'date' => '2026-01-04',
            'reason' => 'Emergency',
            'created_by' => 1
        ]);
    }
}
