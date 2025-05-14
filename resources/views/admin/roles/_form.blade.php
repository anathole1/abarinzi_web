@csrf
<!-- Role Name -->
<div class="mb-4">
    <x-input-label for="name" :value="__('Role Name')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $role->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<!-- Permissions -->
<div class="mb-6">
    <x-input-label :value="__('Assign Permissions')" />
    <div class="mt-2 space-y-2 max-h-96 overflow-y-auto border p-3 rounded-md">
        @forelse ($permissions as $permission)
            <label class="flex items-center">
                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    @if(isset($rolePermissions) && in_array($permission->id, $rolePermissions)) checked @endif
                    @if(is_array(old('permissions')) && in_array($permission->id, old('permissions'))) checked @endif
                >
                <span class="ml-2 text-sm text-gray-600">{{ $permission->name }}</span>
            </label>
        @empty
            <p class="text-sm text-gray-500">No permissions defined yet. <a href="{{ route('admin.permissions.create') }}" class="text-indigo-600 hover:underline">Create one?</a></p>
        @endforelse
    </div>
    <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
    <x-input-error :messages="$errors->get('permissions.*')" class="mt-2" />
</div>


<div class="flex items-center justify-end mt-4">
    <a href="{{ route('admin.roles.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
        Cancel
    </a>
    <x-primary-button>
        {{ isset($role) ? __('Update Role') : __('Create Role') }}
    </x-primary-button>
</div>