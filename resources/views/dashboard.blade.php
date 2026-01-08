<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (Auth::user()->role === 'employee')
                @include('dashboard.employee')
            @elseif(Auth::user()->role === 'company_admin')
                @include('dashboard.company-admin')
            @elseif(Auth::user()->role === 'hr')
                @include('dashboard.hr')
            @else
                @include('dashboard.admin')
            @endif
        </div>
    </div>
</x-app-layout>
