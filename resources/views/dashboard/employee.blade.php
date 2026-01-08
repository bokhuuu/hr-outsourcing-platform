<div class="space-y-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h3 class="text-lg font-semibold mb-4">Welcome, {{ Auth::user()->name }}!</h3>
            <p><strong>Company:</strong> {{ Auth::user()->employee->company->name ?? 'N/A' }}</p>
            <p><strong>Position:</strong> {{ Auth::user()->employee->position->title ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
            <div class="flex flex-col gap-2">
                <a href="{{ route('employee.attendance') }}" class="text-blue-600 hover:text-blue-800">
                    → Attendance (Check-in/Check-out)
                </a>
                <a href="{{ route('employee.leave-requests') }}" class="text-blue-600 hover:text-blue-800">
                    → My Leave Requests
                </a>
                <a href="{{ route('employee.absences') }}" class="text-blue-600 hover:text-blue-800">
                    → My Absences
                </a>
            </div>
        </div>
    </div>
</div>
