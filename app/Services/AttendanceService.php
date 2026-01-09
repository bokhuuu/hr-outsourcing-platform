<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use InvalidArgumentException;

class AttendanceService
{
    /**
     * Check in employee for the day
     */
    public function checkIn(Employee $employee): Attendance
    {
        $today = today();

        $existingAttendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        if ($existingAttendance) {
            throw new InvalidArgumentException('Already checked in today');
        }

        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
            'date' => $today,
            'check_in_time' => now()->toTimeString(),
        ]);

        return $attendance;
    }

    /**
     * Check out employee for the day
     */
    public function checkOut(Employee $employee): Attendance
    {

        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', today())
            ->first();

        if (!$attendance) {
            throw new InvalidArgumentException('No check-in record found for today');
        }

        if ($attendance->check_out_time) {
            throw new InvalidArgumentException('Already checked out today');
        }

        $attendance->update([
            'check_out_time' => now()->toTimeString(),
        ]);

        $attendance->refresh();

        return $attendance;
    }
}
