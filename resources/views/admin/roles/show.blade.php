 <x-app-layout>
     <x-slot name="header">
         <div class="flex justify-between items-center">
             <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                 {{ __('Role Details: ') }} {{ $role->name }}
             </h2>
             <div>
                 @if(!in_array($role->name, ['admin', 'member']))
                     <a href="{{ route('admin.roles.edit', $role) }}" class="px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 mr-2">Edit Role</a>
                 @endif
                  <a href="{{ route('admin.roles.permissions', $role) }}" class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">Manage Permissions</a>
             </div>
         </div>
     </x-slot>

     <div class="py-12">
         <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
             @include('partials.flash-messages')
             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                 <div class="p-6 bg-white border-b border-gray-200">
                     <h3 class="text-lg font-medium text-gray-900 mb-4">Role Information</h3>
                     <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                         <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Name</dt><dd class="mt-1 text-sm text-gray-900">{{ $role->name }}</dd></div>
                         <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Guard Name</dt><dd class="mt-1 text-sm text-gray-900">{{ $role->guard_name }}</dd></div>
                         <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Created At</dt><dd class="mt-1 text-sm text-gray-900">{{ $role->created_at->format('M d, Y H:i') }}</dd></div>
                     </dl>
                 </div>
             </div>

             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                 <div class="p-6 bg-white border-b border-gray-200">
                     <h3 class="text-lg font-medium text-gray-900 mb-2">Assigned Permissions</h3>
                     @forelse($role->permissions as $permission)
                         <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                             {{ $permission->name }}
                         </span>
                     @empty
                         <p class="text-sm text-gray-500">No permissions assigned to this role.</p>
                     @endforelse
                 </div>
             </div>

             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 bg-white border-b border-gray-200">
                     <h3 class="text-lg font-medium text-gray-900 mb-2">Users with this Role (Max 20 shown)</h3>
                     @forelse($role->users->take(20) as $user)
                         <span class="inline-block bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                             <a href="{{ route('admin.users.show', $user) }}" class="hover:underline">{{ $user->name }}</a>
                         </span>
                     @empty
                         <p class="text-sm text-gray-500">No users currently have this role.</p>
                     @endforelse
                     @if($role->users->count() > 20)
                         <p class="text-xs text-gray-500 mt-2">...and {{ $role->users->count() - 20 }} more.</p>
                     @endif
                 </div>
             </div>

             <div class="mt-6">
                 <a href="{{ route('admin.roles.index') }}" class="text-indigo-600 hover:text-indigo-900">← Back to All Roles</a>
             </div>
         </div>
     </div>
 </x-app-layout>