<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Member Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    @include('partials.flash-messages')

                    <form method="POST" action="{{ route('admin.member-categories.store') }}">
                        @include('admin.member_categories._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>