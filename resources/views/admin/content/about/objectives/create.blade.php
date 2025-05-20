<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Create New Core Objective</h2></x-slot>
    <div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @include('partials.flash-messages')
        <form method="POST" action="{{ route('admin.content.objectives.store') }}">
            @include('admin.content.about.objectives._form')
        </form>
    </div></div></div>
</x-app-layout>