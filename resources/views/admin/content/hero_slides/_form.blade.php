@csrf
{{-- Title --}}
<div class="mb-4">
    <x-input-label for="title" :value="__('Slide Title')" />
    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $heroSlide->title ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
</div>

{{-- Description --}}
<div class="mb-4">
    <x-input-label for="description" :value="__('Description (Optional)')" />
    <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $heroSlide->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

{{-- Image Upload --}}
<div class="mb-4">
    <x-input-label for="image" :value="__('Slide Image')" />
    <input id="image" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
    <p class="mt-1 text-sm text-gray-500">Recommended size: 1920x1080px. Max 2MB. Allowed: JPG, PNG, GIF, WEBP.</p>
    <x-input-error :messages="$errors->get('image')" class="mt-2" />
    @if (isset($heroSlide) && $heroSlide->image_path)
    <div class="mt-4">
        <p class="text-sm font-medium text-gray-700">Current Image:</p>
        <img src="{{ $heroSlide->image_url }}" alt="Current Slide Image" class="mt-2 h-20 rounded-md object-cover">
        <p class="text-xs text-gray-500 mt-1">Uploading a new image will replace the current one.</p>
    </div>
    @endif
</div>

{{-- Call to Action 1 --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
     <div>
         <x-input-label for="cta1_text" :value="__('Button 1 Text (Optional)')" />
         <x-text-input id="cta1_text" class="block mt-1 w-full" type="text" name="cta1_text" :value="old('cta1_text', $heroSlide->cta1_text ?? '')" />
         <x-input-error :messages="$errors->get('cta1_text')" class="mt-2" />
     </div>
      <div>
         <x-input-label for="cta1_link" :value="__('Button 1 Link (Optional)')" />
         <x-text-input id="cta1_link" class="block mt-1 w-full" type="url" name="cta1_link" :value="old('cta1_link', $heroSlide->cta1_link ?? '')" placeholder="https://..." />
         <x-input-error :messages="$errors->get('cta1_link')" class="mt-2" />
     </div>
</div>

 {{-- Call to Action 2 --}}
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
     <div>
         <x-input-label for="cta2_text" :value="__('Button 2 Text (Optional)')" />
         <x-text-input id="cta2_text" class="block mt-1 w-full" type="text" name="cta2_text" :value="old('cta2_text', $heroSlide->cta2_text ?? '')" />
         <x-input-error :messages="$errors->get('cta2_text')" class="mt-2" />
     </div>
      <div>
         <x-input-label for="cta2_link" :value="__('Button 2 Link (Optional)')" />
         <x-text-input id="cta2_link" class="block mt-1 w-full" type="url" name="cta2_link" :value="old('cta2_link', $heroSlide->cta2_link ?? '')" placeholder="https://..." />
         <x-input-error :messages="$errors->get('cta2_link')" class="mt-2" />
     </div>
</div>

{{-- Order --}}
<div class="mb-4">
    <x-input-label for="order" :value="__('Display Order (0 = first)')" />
    <x-text-input id="order" class="block mt-1 w-24" type="number" name="order" :value="old('order', $heroSlide->order ?? 0)" required min="0" />
    <x-input-error :messages="$errors->get('order')" class="mt-2" />
</div>

{{-- Active Status --}}
 <div class="mb-6">
     <label for="is_active" class="inline-flex items-center">
         <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active', isset($heroSlide) && $heroSlide->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
         <span class="ml-2 text-sm text-gray-600">{{ __('Active (Show this slide on homepage)') }}</span>
     </label>
     <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
 </div>


<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.content.hero-slides.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
        Cancel
    </a>
    <x-primary-button>
        {{ isset($heroSlide) ? __('Update Slide') : __('Create Slide') }}
    </x-primary-button>
</div>