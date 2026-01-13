<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Exception;

class AttendanceController extends Controller
{
    public function checkIn(AttendanceService $service)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return response()->json([
                'message' => 'Employee record not found'
            ], 404);
        }

        try {
            $attendance = $service->checkIn($employee);

            return response()->json([
                'message' => 'Checked in successfully',
                'data' => $attendance
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function checkOut(AttendanceService $service)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return response()->json([
                'message' => 'Employee record not found'
            ], 404);
        }

        try {
            $attendance = $service->checkOut($employee);

            return response()->json([
                'message' => 'Checked out successfully',
                'data' => $attendance
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
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
