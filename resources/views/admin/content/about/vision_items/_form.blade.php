@csrf
<div class="mb-4">
    <x-input-label for="title" :value="__('Vision Point Title')" />
    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $visionItem->title ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="content" :value="__('Vision Point Content')" />
    <textarea id="content" name="content" rows="5" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('content', $visionItem->content ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('content')" class="mt-2" />
</div>

<div class="mb-6">
    <x-input-label for="order" :value="__('Display Order (0 = first)')" />
    <x-text-input id="order" class="block mt-1 w-24" type="number" name="order" :value="old('order', $visionItem->order ?? 0)" required min="0" />
    <x-input-error :messages="$errors->get('order')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.content.vision-items.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
        Cancel
    </a>
    <x-primary-button>
        {{ isset($visionItem) ? __('Update Vision Point') : __('Create Vision Point') }}
    </x-primary-button>
</div>