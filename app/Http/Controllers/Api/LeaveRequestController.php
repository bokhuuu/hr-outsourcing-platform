<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest\StoreLeaveRequest;
use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Exception;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class LeaveRequestController extends Controller
{
    /**
     * Create leave request for employee
     */
    public function store(StoreLeaveRequest $request, LeaveRequestService $service)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return response()->json([
                'message' => 'Employee record not found'
            ], 404);
        }

        try {
            $leaveRequest =  $service->createRequest($employee, $request->validated());

            return response()->json([
                'message' => 'Leave request created successfully',
                'data' => $leaveRequest
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Approve leave request
     */
    public function approve(LeaveRequest $leaveRequest, LeaveRequestService $service)
    {
        try {
            $approvedLeaveRequest = $service->approve($leaveRequest, auth()->user()->id);

            return response()->json([
                'message' => 'Leave request approved successfully',
                'data' => $approvedLeaveRequest
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Reject leave request
     */
    public function reject(LeaveRequest $leaveRequest, Request $request, LeaveRequestService $service)
    {
        $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        try {
            $rejectedLeaveRequest = $service->reject($leaveRequest, auth()->user()->id, $request->rejection_reason);

            return response()->json([
                'message' => 'Leave request rejected successfully',
                'data' => $rejectedLeaveRequest
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
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
