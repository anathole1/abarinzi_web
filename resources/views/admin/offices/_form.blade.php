@csrf
<div class="space-y-6">
    <div>
        <x-input-label for="name" :value="__('Office Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $office->name ?? '')" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <x-input-label for="code" :value="__('Code / Acronym (Optional)')" />
            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" :value="old('code', $office->code ?? '')" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="display_order" :value="__('Display Order')" />
            <x-text-input id="display_order" name="display_order" type="number" class="mt-1 block w-full" :value="old('display_order', $office->display_order ?? 0)" required />
            <x-input-error :messages="$errors->get('display_order')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="description" :value="__('Role Description / Responsibilities (Optional)')" />
        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $office->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="user_id" :value="__('Assigned Member (Optional)')" />
        <select id="user_id" name="user_id" class="mt-1 block w-full tom-select-simple"> {{-- Tom Select can enhance this --}}
            <option value="">-- Vacant / Unassigned --</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ (isset($office) && $office->user_id == $user->id) || old('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
    </div>
</div>

<div class="flex items-center justify-end mt-8 pt-6 border-t">
    <a href="{{ route('admin.offices.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
    <x-primary-button>
        {{ isset($office) ? __('Update Office') : __('Create Office') }}
    </x-primary-button>
</div>