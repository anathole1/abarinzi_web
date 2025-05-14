@csrf
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
</div>