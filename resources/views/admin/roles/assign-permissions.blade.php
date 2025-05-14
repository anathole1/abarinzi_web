<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assign Permissions to Role: ') }} {{ $role->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @include('partials.flash-messages')
                    <form method="POST" action="{{ route('admin.roles.assignPermissions', $role) }}">
                        @csrf
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Available Permissions</h3>
                            <div class="space-y-2 max-h-96 overflow-y-auto border p-2 rounded-md">
                                @forelse($permissions as $permission)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                               @if(in_array($permission->id, $rolePermissions)) checked @endif>
                                        <span class="ml-2 text-sm text-gray-600">{{ $permission->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">No permissions available.</p>
                                @endforelse
                            </div>
                            <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.roles.show', $role) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Permissions for Role') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>