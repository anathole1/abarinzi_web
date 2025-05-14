<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit About Us Page Content') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    @include('partials.flash-messages')

                    <form method="POST" action="{{ route('admin.content.about.update') }}">
                        @method('PUT')
                        @csrf

                        {{-- Main Section --}}
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Main Section</h3>
                        <div class="mb-4">
                            <x-input-label for="main_title" :value="__('Main Title')" />
                            <x-text-input id="main_title" class="block mt-1 w-full" type="text" name="main_title" :value="old('main_title', $content->main_title ?? '')" required />
                            <x-input-error :messages="$errors->get('main_title')" class="mt-2" />
                        </div>
                        <div class="mb-6">
                            <x-input-label for="main_subtitle" :value="__('Main Subtitle / Introduction')" />
                            <textarea id="main_subtitle" name="main_subtitle" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('main_subtitle', $content->main_subtitle ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('main_subtitle')" class="mt-2" />
                        </div>

                        {{-- Our Story Section --}}
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4 mt-8">Our Story Section</h3>
                        <div class="mb-4">
                            <x-input-label for="story_title" :value="__('Story Title')" />
                            <x-text-input id="story_title" class="block mt-1 w-full" type="text" name="story_title" :value="old('story_title', $content->story_title ?? '')" required />
                            <x-input-error :messages="$errors->get('story_title')" class="mt-2" />
                        </div>
                         <div class="mb-4">
                            <x-input-label for="story_paragraph1" :value="__('Story Paragraph 1')" />
                            <textarea id="story_paragraph1" name="story_paragraph1" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('story_paragraph1', $content->story_paragraph1 ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('story_paragraph1')" class="mt-2" />
                        </div>
                        <div class="mb-6">
                            <x-input-label for="story_paragraph2" :value="__('Story Paragraph 2')" />
                            <textarea id="story_paragraph2" name="story_paragraph2" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('story_paragraph2', $content->story_paragraph2 ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('story_paragraph2')" class="mt-2" />
                        </div>

                        {{-- Value Cards (JSON) --}}
                         <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4 mt-8">Value Cards</h3>
                         <div class="mb-6">
                             <x-input-label for="value_cards" :value="__('Value Cards (JSON Data)')" />
                             <textarea id="value_cards" name="value_cards" rows="8" class="block mt-1 w-full font-mono text-sm rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('value_cards', isset($content->value_cards) ? json_encode($content->value_cards, JSON_PRETTY_PRINT) : '') }}</textarea>
                             <p class="mt-2 text-xs text-gray-500">
                                 Enter as valid JSON array of objects. Each object should have 'icon_svg_path', 'title', and 'description' keys.
                                 Example: <code class="text-xs">[{"icon_svg_path": "M16.5...", "title": "Connection", "description": "Fostering bonds..."}, {"icon_svg_path": "M18...", "title": "Support", "description": "Providing resources..."}]</code>
                             </p>
                             <x-input-error :messages="$errors->get('value_cards')" class="mt-2" />
                        </div>

                        {{-- Join Section --}}
                         <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4 mt-8">Join Section Card</h3>
                          <div class="mb-4">
                            <x-input-label for="join_title" :value="__('Join Card Title')" />
                            <x-text-input id="join_title" class="block mt-1 w-full" type="text" name="join_title" :value="old('join_title', $content->join_title ?? '')" required />
                            <x-input-error :messages="$errors->get('join_title')" class="mt-2" />
                        </div>
                         <div class="mb-4">
                            <x-input-label for="join_text" :value="__('Join Card Text')" />
                            <textarea id="join_text" name="join_text" rows="2" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('join_text', $content->join_text ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('join_text')" class="mt-2" />
                        </div>
                        <div class="mb-6">
                            <x-input-label for="join_button_text" :value="__('Join Card Button Text')" />
                            <x-text-input id="join_button_text" class="block mt-1 w-full" type="text" name="join_button_text" :value="old('join_button_text', $content->join_button_text ?? '')" required />
                            <x-input-error :messages="$errors->get('join_button_text')" class="mt-2" />
                        </div>

                        {{-- Stats (JSON) --}}
                         <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4 mt-8">Stats Section</h3>
                         <div class="mb-6">
                             <x-input-label for="stats" :value="__('Stats (JSON Data)')" />
                             <textarea id="stats" name="stats" rows="6" class="block mt-1 w-full font-mono text-sm rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('stats', isset($content->stats) ? json_encode($content->stats, JSON_PRETTY_PRINT) : '') }}</textarea>
                             <p class="mt-2 text-xs text-gray-500">
                                 Enter as valid JSON array of objects. Each object should have 'value' and 'label' keys.
                                 Example: <code class="text-xs">[{"value": "100+", "label": "Successful Events"}, {"value": "500+", "label": "Active Members"}]</code>
                             </p>
                             <x-input-error :messages="$errors->get('stats')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <x-primary-button>
                                {{ __('Update About Content') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>