<?php

namespace Database\Seeders;

use App\Models\LeaveRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeaveRequest::create([
            'employee_id' => 2,
            'company_id' => 1,
            'leave_type' => 'vacation',
            'start_date' => '2026-02-01',
            'end_date' => '2026-02-05',
            'reason' => 'Family vacation',
            'status' => 'pending'
        ]);

        LeaveRequest::create([
            'employee_id' => 3,
            'company_id' => 1,
            'leave_type' => 'sick',
            'start_date' => '2025-12-20',
            'end_date' => '2025-12-22',
            'reason' => 'Medical appointment',
            'status' => 'approved',
            'approved_by' => 3,
            'approved_at' => now()->subDays(10)
        ]);

        LeaveRequest::create([
            'employee_id' => 5,
            'company_id' => 2,
            'leave_type' => 'vacation',
            'start_date' => '2026-01-15',
            'end_date' => '2026-01-20',
            'reason' => 'Personal travel',
            'status' => 'rejected',
            'approved_by' => 1,
            'approved_at' => now()->subDays(5),
            'rejection_reason' => 'Insufficient leave balance'
        ]);
    }
}
