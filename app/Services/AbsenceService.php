<?php

namespace App\Services;

use App\Models\Absence;
use App\Models\Employee;
use InvalidArgumentException;

class AbsenceService
{
    /**
     * Register absence for employee
     */
    public function register(Employee $employee, string $date, ?string $reason, int $createdBy): Absence
    {
        $existingAbsence = Absence::where('employee_id', $employee->id)
            ->where('date', $date)
            ->exists();

        if ($existingAbsence) {
            throw new InvalidArgumentException('Absence already regstered for this date.');
        }

        $absence = Absence::create([
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
            'date' => $date,
            'reason' => $reason,
            'created_by' => $createdBy,
        ]);

        return $absence;
    }
}
