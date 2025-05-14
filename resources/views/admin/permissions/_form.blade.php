@csrf
<!-- Permission Name -->
<div class="mb-4">
    <x-input-label for="name" :value="__('Permission Name (e.g., edit articles, manage users)')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $permission->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
    <p class="text-xs text-gray-500 mt-1">Use lowercase and underscores or hyphens. e.g., `view-reports` or `delete_posts`.</p>
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('admin.permissions.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
        Cancel
    </a>
    <x-primary-button>
        {{ __('Create Permission') }}
    </x-primary-button>
</div>