<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Office: {{ $office->name }}</h2></x-slot>
    <div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 md:p-8">
        @include('partials.flash-messages')
        <form method="POST" action="{{ route('admin.offices.update', $office) }}">
            @method('PUT')
            @include('admin.offices._form', ['office' => $office, 'users' => $users])
        </form>
    </div></div></div>
</x-app-layout>