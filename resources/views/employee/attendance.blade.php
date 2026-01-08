<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Attendance
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Today's Attendance</h3>

                    @if ($todayAttendance)
                        <p class="mb-2">Check-in: {{ $todayAttendance->check_in_time }}</p>
                        <p class="mb-4">Check-out: {{ $todayAttendance->check_out_time ?? 'Not checked out' }}</p>
                    @else
                        <p class="mb-4">Not checked in today</p>
                    @endif

                    <div class="flex gap-4">
                        @if (!$todayAttendance)
                            <form method="POST" action="{{ route('employee.check-in') }}">
                                @csrf
                                <button type="submit"
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    Check In
                                </button>
                            </form>
                        @elseif(!$todayAttendance->check_out_time)
                            <form method="POST" action="{{ route('employee.check-out') }}">
                                @csrf
                                <button type="submit"
                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Check Out
                                </button>
                            </form>
                        @else
                            <p class="text-gray-600">Attendance complete for today</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Attendance History</h3>

                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Date</th>
                                <th class="text-left py-2">Check In</th>
                                <th class="text-left py-2">Check Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr class="border-b">
                                    <td class="py-2">{{ $attendance->date }}</td>
                                    <td class="py-2">{{ $attendance->check_in_time }}</td>
                                    <td class="py-2">{{ $attendance->check_out_time ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-gray-500">No attendance records</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
