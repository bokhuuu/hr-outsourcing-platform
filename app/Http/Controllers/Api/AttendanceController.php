<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function checkIn()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return response()->json([
                'message' => 'Employee record not found'
            ], 404);
        }

        $today = today();

        $existingAttendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'message' => 'Already checked in today'
            ], 400);
        }

        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
            'date' => $today,
            'check_in_time' => now()->toTimeString(),
        ]);

        return response()->json([
            'message' => 'Checked in successfully',
            'data' => $attendance
        ], 201);
    }

    public function checkOut()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return response()->json([
                'message' => 'Employee record not found'
            ], 404);
        }

        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', today())
            ->first();

        if (!$attendance) {
            return response()->json([
                'message' => 'No check-in record found for today'
            ], 400);
        }

        if ($attendance->check_out_time) {
            return response()->json([
                'message' => 'Already checked out today'
            ], 400);
        }

        $attendance->update([
            'check_out_time' => now()->toTimeString(),
        ]);

        return response()->json([
            'message' => 'Checked out successfully',
            'data' => $attendance->fresh()
        ], 200);
    }

    public function index()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return response()->json([
                'message' => 'Employee record not found'
            ], 404);
        }

        $attendances = Attendance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'data' => $attendances
        ]);
    }
}
