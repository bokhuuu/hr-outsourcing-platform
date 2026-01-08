<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest\StoreLeaveRequest;
use App\Models\Absence;
use App\Models\Attendance;
use App\Models\LeaveRequest;

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

    public function checkIn()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return back()->with('error', 'Employee record not found');
        }

        $existingAttendance = Attendance::where('employee_id', $employee->id)
            ->where('date', today())
            ->first();

        if ($existingAttendance) {
            return back()->with('error', 'Already checked in today');
        }

        Attendance::create([
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
            'date' => today(),
            'check_in_time' => now()->toTimeString(),
        ]);

        return back()->with('success', 'Checked in successfully');
    }

    public function checkOut()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return back()->with('error', 'Employee record not found');
        }

        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', today())
            ->first();

        if (!$attendance) {
            return back()->with('error', 'No check-in record found for today');
        }

        if ($attendance->check_out_time) {
            return back()->with('error', 'Already checked out today');
        }

        $attendance->update([
            'check_out_time' => now()->toTimeString(),
        ]);

        return back()->with('success', 'Checked out successfully');
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

    public function storeLeaveRequest(StoreLeaveRequest $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return back()->with('error', 'Employee record not found');
        }

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('employee.leave-requests')
            ->with('success', 'Leave request submitted successfully');
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
