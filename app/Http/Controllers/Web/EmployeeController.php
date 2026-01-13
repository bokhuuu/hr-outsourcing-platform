<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest\StoreLeaveRequest;
use App\Models\Absence;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Services\AttendanceService;
use App\Services\LeaveRequestService;
use Exception;

class EmployeeController extends Controller
{
    public function attendanceForm()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            abort(403, 'Employee record not found');
        }

        $attendances = Attendance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->paginate(10);

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->where('date', today())
            ->first();

        return view('employee.attendance', compact('attendances', 'todayAttendance'));
    }

    public function checkIn(AttendanceService $service)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return back()->with('error', 'Employee record not found');
        }

        try {
            $service->checkIn($employee);

            return back()->with('success', 'Checked in successfully');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function checkOut(AttendanceService $service)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return back()->with('error', 'Employee record not found');
        }

        try {
            $service->checkOut($employee);

            return back()->with('success', 'Checked out successfully');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function leaveRequests()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            abort(403, 'Employee record not found');
        }

        $leaveRequests = LeaveRequest::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employee.leave-requests', compact('leaveRequests'));
    }

    public function createLeaveRequest()
    {
        return view('employee.create-leave-request');
    }

    public function storeLeaveRequest(StoreLeaveRequest $request, LeaveRequestService $service)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return back()->with('error', 'Employee record not found');
        }

        try {
            $service->createRequest($employee, $request->validated());

            return redirect()->route('employee.leave-requests')
                ->with('success', 'Leave request submitted successfully');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function absences()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            abort(403, 'Employee record not found');
        }

        $absences = Absence::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('employee.absences', compact('absences'));
    }
}
