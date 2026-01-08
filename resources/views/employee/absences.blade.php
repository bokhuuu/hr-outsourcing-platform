<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Absences
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Absence Records</h3>

                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Date</th>
                                <th class="text-left py-2">Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absences as $absence)
                                <tr class="border-b">
                                    <td class="py-2">{{ $absence->date }}</td>
                                    <td class="py-2">{{ $absence->reason ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-4 text-gray-500">No absence records</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $absences->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
