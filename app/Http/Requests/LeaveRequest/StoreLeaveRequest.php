<?php

namespace App\Http\Requests\LeaveRequest;

use App\Models\LeaveRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreLeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'leave_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $employee = auth()->user()->employee;

            if (!$employee) {
                return;
            }

            $overlapping = LeaveRequest::where('employee_id', $employee->id)
                ->where('status', '!=', 'rejected')
                ->where(function ($query) {
                    $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                        ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                        ->orWhere(function ($q) {
                            $q->where('start_date', '<=', $this->start_date)
                                ->where('end_date', '>=', $this->end_date);
                        });
                })
                ->exists();

            if ($overlapping) {
                $validator->errors()->add(
                    'dates',
                    'You already have a leave request for these dates.'
                );
            }
        });
    }
}
