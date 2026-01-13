<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Absence\StoreAbsenceRequest;
use App\Models\Absence;
use App\Models\Employee;
use App\Services\AbsenceService;
use Exception;

class AbsenceController extends Controller
{
    public function store(StoreAbsenceRequest $request, AbsenceService $service)
    {
        $employee = Employee::findOrFail($request->employee_id);

        try {
            $absence = $service->register(
                $employee,
                $request->date,
                $request->reason,
            );

            return response()->json([
                'message' => 'Absence created successfully',
                'data' => $absence
            ], 201);
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

            $absences = Absence::where('employee_id', $user->employee->id)
                ->orderBy('date', 'desc')
                ->get();
        } else {
            $absences = Absence::orderBy('date', 'desc')->get();
        }

        return response()->json(['data' => $absences]);
    }
}
