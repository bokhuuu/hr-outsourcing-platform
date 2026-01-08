<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Leave Requests
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Leave Requests</h3>
                        <a href="{{ route('employee.leave-requests.create') }}"
                            class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600">
                            New Request
                        </a>
                    </div>

                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Type</th>
                                <th class="text-left py-2">Start Date</th>
                                <th class="text-left py-2">End Date</th>
                                <th class="text-left py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveRequests as $request)
                                <tr class="border-b">
                                    <td class="py-2">{{ $request->leave_type }}</td>
                                    <td class="py-2">{{ $request->start_date }}</td>
                                    <td class="py-2">{{ $request->end_date }}</td>
                                    <td class="py-2">
                                        <span
                                            class="px-2 py-1 rounded text-sm
                                            @if ($request->status === 'pending') @elseif($request->status === 'approved') 
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-gray-500">No leave requests</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $leaveRequests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
