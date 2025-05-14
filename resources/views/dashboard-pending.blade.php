<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">{{ __("Welcome, ") }} {{ Auth::user()->name }}!</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __("Your membership application has been submitted and is currently pending approval.") }}
                    </p>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __("You will be notified once it's reviewed. You cannot access other features like contributions until your membership is approved.") }}
                    </p>
                    @if(session('status'))
                        <div class="mt-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif
                     @if(session('info'))
                        <div class="mt-4 font-medium text-sm text-blue-600">
                            {{ session('info') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>