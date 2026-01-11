<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\LeaveRequest;
use InvalidArgumentException;

class LeaveRequestService
{
    /**
     * Create leave request for employee
     */
    public function createRequest(Employee $employee, array $data): LeaveRequest
    {

        $overlapping = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                    ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])
                    ->orWhere(function ($q) use ($data) {
                        $q->where('start_date', '<=', $data['start_date'])
                            ->where('end_date', '>=', $data['end_date']);
                    });
            })
            ->exists();

        if ($overlapping) {
            throw new InvalidArgumentException('You already have a leave request for these dates.');
        }

        return LeaveRequest::create([
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
            'leave_type' => $data['leave_type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'] ?? null,
            'status' => 'pending',
        ]);
    }

    /**
     * Approve leave request
     */
    public function approve(LeaveRequest $leaveRequest, int $approvedBy): LeaveRequest
    {
        if ($leaveRequest->status !== 'pending') {
            throw new InvalidArgumentException('Leave request has already been processed');
        }

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => $approvedBy,
            'approved_at' => now(),
        ]);

        return $leaveRequest->refresh();
    }

    /**
     * Reject leave request
     */
    public function reject(LeaveRequest $leaveRequest, int $rejectedBy, string $rejectionReason): LeaveRequest
    {
        if ($leaveRequest->status !== 'pending') {
            throw new InvalidArgumentException('Leave request has already been processed');
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => $rejectedBy,
            'approved_at' => now(),
            'rejection_reason' => $rejectionReason
        ]);

        return $leaveRequest->refresh();
    }
}
