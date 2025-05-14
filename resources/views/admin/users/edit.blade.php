<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @include('partials.flash-messages')
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @method('PUT')
                        @include('admin.users._form', ['user' => $user, 'roles' => $roles])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>