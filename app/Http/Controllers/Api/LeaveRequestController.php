<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest\StoreLeaveRequest;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class LeaveRequestController extends Controller
{
    public function store(StoreLeaveRequest $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return response()->json([
                'message' => 'Employee record not found'
            ], 404);
        }

        $leaveRequest = LeaveRequest::create([
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Leave request created successfully',
            'data' => $leaveRequest
        ], 201);
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return response()->json([
                'message' => 'Leave request has already been processed'
            ], 400);
        }

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->user()->id,
            'approved_at' => now(),
        ]);

        return response()->json([
            'message' => 'Leave request approved successfully',
            'data' => $leaveRequest->fresh()
        ], 200);
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        if ($leaveRequest->status !== 'pending') {
            return response()->json([
                'message' => 'Leave request has already been processed'
            ], 400);
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->user()->id,
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason
        ]);

        return response()->json([
            'message' => 'Leave request rejected',
            'data' => $leaveRequest->fresh()
        ], 200);
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'employee') {
            if (!$user->employee) {
                return response()->json([
                    'message' => 'Employee record not found'
                ], 404);
            }

            $leaveRequests = LeaveRequest::where('employee_id', $user->employee->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else if ($user->role === 'hr' || $user->role === 'company_admin') {
            $leaveRequests = LeaveRequest::orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json([
            'data' => $leaveRequests
        ]);
    }
}
