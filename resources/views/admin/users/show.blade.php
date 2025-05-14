<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
             <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                 {{ __('User Details: ') }} {{ $user->name }}
             </h2>
             <div>
                 <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 mr-2">Edit User</a>
                 <a href="{{ route('admin.users.roles', $user) }}" class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">Manage Roles</a>
                 {{-- <a href="{{ route('admin.users.permissions', $user) }}" class="px-4 py-2 bg-teal-500 text-white rounded hover:bg-teal-600 ml-2">Manage Direct Permissions</a> --}}
             </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
             @include('partials.flash-messages')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                        <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Name</dt><dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd></div>
                        <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Email</dt><dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd></div>
                        <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Email Verified</dt><dd class="mt-1 text-sm text-gray-900">{{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i') : 'No' }}</dd></div>
                        <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Joined</dt><dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</dd></div>
                    </dl>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Assigned Roles</h3>
                    @forelse($user->roles as $role)
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                            {{ $role->name }}
                        </span>
                    @empty
                        <p class="text-sm text-gray-500">No roles assigned.</p>
                    @endforelse
                </div>
            </div>

             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Direct Permissions (excluding those via roles)</h3>
                    @forelse($user->getDirectPermissions() as $permission)
                        <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                            {{ $permission->name }}
                        </span>
                    @empty
                        <p class="text-sm text-gray-500">No direct permissions assigned.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">All Permissions (including via roles)</h3>
                    @forelse($user->getAllPermissions() as $permission)
                        <span class="inline-block bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                            {{ $permission->name }}
                        </span>
                    @empty
                        <p class="text-sm text-gray-500">No permissions assigned.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-6">
                 <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-900">← Back to All Users</a>
            </div>
        </div>
    </div>
</x-app-layout>