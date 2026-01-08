<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            New Leave Request
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('employee.leave-requests.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Leave Type</label>
                            <input type="text" name="leave_type" value="{{ old('leave_type') }}"
                                class="w-full border-gray-300 rounded" required>
                            @error('leave_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Start Date</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}"
                                class="w-full border-gray-300 rounded" required>
                            @error('start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">End Date</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}"
                                class="w-full border-gray-300 rounded" required>
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Reason (optional)</label>
                            <textarea name="reason" rows="3" class="w-full border-gray-300 rounded">{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <button type="submit"
                                class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600">
                                Submit Request
                            </button>
                            <a href="{{ route('employee.leave-requests') }}"
                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
