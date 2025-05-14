<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Roles') }}
            </h2>
            <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                {{ __('Add New Role') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @include('partials.flash-messages')

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Users</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Permissions</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($roles as $role)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $role->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $role->users_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $role->permissions_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.roles.show', $role) }}" class="text-gray-600 hover:text-gray-900 mr-2">View</a>
                                            @if(!in_array($role->name, ['admin', 'member'])) {{-- Prevent editing/deleting core roles --}}
                                                <a href="{{ route('admin.roles.edit', $role) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                                 <a href="{{ route('admin.roles.permissions', $role) }}" class="text-purple-600 hover:text-purple-900 mr-2">Manage Permissions</a>
                                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete role \'{{ $role->name }}\'? This is a sensitive operation.');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            @else
                                                 <span class="text-xs text-gray-400 italic">System Role</span>
                                                  <a href="{{ route('admin.roles.permissions', $role) }}" class="text-purple-600 hover:text-purple-900 ml-2">Manage Permissions</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No roles found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>