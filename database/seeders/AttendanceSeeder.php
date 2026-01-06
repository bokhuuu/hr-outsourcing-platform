<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Attendance::create([
            'employee_id' => 2,
            'company_id' => 1,
            'date' => today(),
            'check_in_time' => '09:00:00',
            'check_out_time' => '18:00:00'
        ]);

        Attendance::create([
            'employee_id' => 3,
            'company_id' => 1,
            'date' => today(),
            'check_in_time' => '09:15:00',
            'check_out_time' => null
        ]);

        Attendance::create([
            'employee_id' => 5,
            'company_id' => 2,
            'date' => today(),
            'check_in_time' => '08:45:00',
            'check_out_time' => '17:30:00'
        ]);
    }
}
