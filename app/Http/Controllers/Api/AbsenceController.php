<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Absence\StoreAbsenceRequest;
use App\Models\Absence;
use App\Models\Employee;

class AbsenceController extends Controller
{
    public function store(StoreAbsenceRequest $request)
    {
        $employee = Employee::findOrFail($request->employee_id);

        $absence = Absence::create([
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
            'date' => $request->date,
            'reason' => $request->reason,
            'created_by' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'Absence created successfully',
            'data' => $absence
        ], 201);
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
