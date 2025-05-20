@csrf
<!-- Name -->
<div class="mb-6"> {{-- Increased bottom margin --}}
    <x-input-label for="name" :value="__('Name')" class="block text-sm font-medium text-gray-700 mb-1" />
    <x-text-input id="name"
        class="block w-full px-4 py-2.5 text-gray-800 bg-white border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
        type="text"
        name="name"
        :value="old('name', $user->name ?? '')"
        required
        autofocus
        autocomplete="name" />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<!-- Email -->
<div class="mb-6">
    <x-input-label for="email" :value="__('Email')" class="block text-sm font-medium text-gray-700 mb-1" />
    <x-text-input id="email"
        class="block w-full px-4 py-2.5 text-gray-800 bg-white border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
        type="email"
        name="email"
        :value="old('email', $user->email ?? '')"
        required
        autocomplete="email" /> {{-- Standard autocomplete for email --}}
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

<!-- Password -->
<div class="mb-6">
    <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700 mb-1" />
    <x-text-input id="password"
        class="block w-full px-4 py-2.5 text-gray-800 bg-white border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
        type="password"
        name="password"
        {{-- Only required on create form --}}
        {{-- {{ request()->routeIs('admin.users.create') ? 'required' : '' }} --}}
         {{-- @if(request()->routeIs('admin.users.create')) required @endif --}}
        autocomplete="new-password" />
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
    @if(request()->routeIs('admin.users.edit'))
        <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password.</p>
    @endif
</div>

<!-- Confirm Password -->
<div class="mb-6">
    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-sm font-medium text-gray-700 mb-1" />
    <x-text-input id="password_confirmation"
        class="block w-full px-4 py-2.5 text-gray-800 bg-white border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
        type="password"
        name="password_confirmation"
        {{-- Only required on create form --}}
        {{-- {{ request()->routeIs('admin.users.create') ? 'required' : '' }} --}}
        autocomplete="new-password" />
    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
</div>

<!-- Roles -->
<div class="mb-8"> {{-- Increased bottom margin --}}
    <x-input-label :value="__('Roles')" class="block text-sm font-medium text-gray-700 mb-2" />
    <div class="mt-1 space-y-2 max-h-60 overflow-y-auto border border-gray-300 p-4 rounded-md bg-white shadow-sm">
        @forelse ($roles as $role)
            <label class="flex items-center cursor-pointer p-2 hover:bg-gray-50 rounded">
                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4"
                    @if(isset($user) && $user->roles->contains($role->id)) checked @endif
                    @if(is_array(old('roles')) && in_array($role->id, old('roles'))) checked @endif
                >
                <span class="ml-3 text-sm text-gray-700">{{ $role->name }}</span>
            </label>
        @empty
            <p class="text-sm text-gray-500 p-2">No roles defined. Create roles first.</p>
        @endforelse
    </div>
    <x-input-error :messages="$errors->get('roles')" class="mt-2" />
    <x-input-error :messages="$errors->get('roles.*')" class="mt-2" />
</div>

<div class="flex items-center justify-end pt-4 mt-8 border-t border-gray-200"> {{-- Added border-t and more margin --}}
    <a href="{{ route('admin.users.index') }}"
       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
        Cancel
    </a>
    <x-primary-button class="px-6 py-2.5 text-xs uppercase">
        {{ isset($user) ? __('Update User') : __('Create User') }}
    </x-primary-button>
</div>




{{-- @csrf
<!-- Name -->
<div class="mb-4">
    <x-input-label for="name" :value="__('Name')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name ?? '')" required autofocus autocomplete="name" />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<!-- Email -->
<div class="mb-4">
    <x-input-label for="email" :value="__('Email')" />
    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email ?? '')" required autocomplete="username" />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

<!-- Password -->
<div class="mb-4">
    <x-input-label for="password" :value="__('Password')" />
    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" {{ isset($user) ? '' : 'required' }} autocomplete="new-password" />
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
    @if(isset($user))
        <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password.</p>
    @endif
</div>

<!-- Confirm Password -->
<div class="mb-4">
    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                    type="password"
                    name="password_confirmation" {{ isset($user) ? '' : 'required' }} autocomplete="new-password" />
    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
</div>

<!-- Roles -->
<div class="mb-4">
    <x-input-label for="roles" :value="__('Roles')" />
    <div class="mt-1 space-y-2 max-h-60 overflow-y-auto border p-2 rounded-md">
         @forelse ($roles as $role)
             <label class="flex items-center">
                 <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                     class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                     @if(isset($user) && $user->roles->contains($role->id)) checked @endif
                     @if(is_array(old('roles')) && in_array($role->id, old('roles'))) checked @endif
                 >
                 <span class="ml-2 text-sm text-gray-600">{{ $role->name }}</span>
             </label>
         @empty
             <p class="text-sm text-gray-500">No roles defined.</p>
         @endforelse
    </div>
    <x-input-error :messages="$errors->get('roles')" class="mt-2" />
    <x-input-error :messages="$errors->get('roles.*')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
        Cancel
    </a>
    <x-primary-button>
        {{ isset($user) ? __('Update User') : __('Create User') }}
    </x-primary-button>
</div> --}}