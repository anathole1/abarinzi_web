<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Loan Record for ') }} {{ $loan->user->name }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 md:p-8">
                @include('partials.flash-messages')
                <form method="POST" action="{{ route('admin.loans.update', $loan) }}">
                    @method('PUT')
                    @include('admin.loans._form', ['loan' => $loan, 'selectedUserOption' => $selectedUserOption])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>