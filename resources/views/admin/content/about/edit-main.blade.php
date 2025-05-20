<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Main About Page Content') }}
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

                        <fieldset class="mb-8 border p-4 rounded-md">
                            <legend class="text-lg font-semibold text-gray-800 px-2">Page Header</legend>
                            <div class="mt-4 mb-4">
                                <x-input-label for="page_main_title" :value="__('Main Page Title')" />
                                <x-text-input id="page_main_title" class="block mt-1 w-full" type="text" name="page_main_title" :value="old('page_main_title', $content->page_main_title)" required />
                                <x-input-error :messages="$errors->get('page_main_title')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="page_main_subtitle" :value="__('Main Page Subtitle/Introductory Sentence')" />
                                <textarea id="page_main_subtitle" name="page_main_subtitle" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('page_main_subtitle', $content->page_main_subtitle) }}</textarea>
                                <x-input-error :messages="$errors->get('page_main_subtitle')" class="mt-2" />
                            </div>
                        </fieldset>

                        <fieldset class="mb-8 border p-4 rounded-md">
                            <legend class="text-lg font-semibold text-gray-800 px-2">Introduction Section</legend>
                            <div class="mt-4 mb-4">
                                <x-input-label for="intro_title" :value="__('Introduction Title')" />
                                <x-text-input id="intro_title" class="block mt-1 w-full" type="text" name="intro_title" :value="old('intro_title', $content->intro_title)" required />
                                <x-input-error :messages="$errors->get('intro_title')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="intro_content" :value="__('Introduction Content')" />
                                <textarea id="intro_content" name="intro_content" rows="6" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('intro_content', $content->intro_content) }}</textarea>
                                <x-input-error :messages="$errors->get('intro_content')" class="mt-2" />
                            </div>
                        </fieldset>

                        <fieldset class="mb-8 border p-4 rounded-md">
                            <legend class="text-lg font-semibold text-gray-800 px-2">Mission Statement Section</legend>
                            <div class="mt-4 mb-4">
                                <x-input-label for="mission_title" :value="__('Mission Title')" />
                                <x-text-input id="mission_title" class="block mt-1 w-full" type="text" name="mission_title" :value="old('mission_title', $content->mission_title)" required />
                                <x-input-error :messages="$errors->get('mission_title')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="mission_summary" :value="__('Mission Summary (for homepage)')" />
                                <textarea id="mission_summary" name="mission_summary" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('mission_summary', $content->mission_summary) }}</textarea>
                                <x-input-error :messages="$errors->get('mission_summary')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="mission_content" :value="__('Mission Full Content (for details page)')" />
                                <textarea id="mission_content" name="mission_content" rows="6" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('mission_content', $content->mission_content) }}</textarea>
                                <x-input-error :messages="$errors->get('mission_content')" class="mt-2" />
                            </div>
                        </fieldset>

                        <fieldset class="mb-8 border p-4 rounded-md">
                            <legend class="text-lg font-semibold text-gray-800 px-2">Core Objectives Section</legend>
                            <div class="mt-4 mb-4">
                                <x-input-label for="core_objectives_section_title" :value="__('Section Title (e.g., Core Objectives and Activities)')" />
                                <x-text-input id="core_objectives_section_title" class="block mt-1 w-full" type="text" name="core_objectives_section_title" :value="old('core_objectives_section_title', $content->core_objectives_section_title)" required />
                                <x-input-error :messages="$errors->get('core_objectives_section_title')" class="mt-2" />
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('admin.content.objectives.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Manage Objective Items
                                </a>
                            </div>
                        </fieldset>

                        <fieldset class="mb-8 border p-4 rounded-md">
                            <legend class="text-lg font-semibold text-gray-800 px-2">Vision for the Future Section</legend>
                            <div class="mt-4 mb-4">
                                <x-input-label for="vision_section_title" :value="__('Section Title (e.g., Vision for the Future)')" />
                                <x-text-input id="vision_section_title" class="block mt-1 w-full" type="text" name="vision_section_title" :value="old('vision_section_title', $content->vision_section_title)" required />
                                <x-input-error :messages="$errors->get('vision_section_title')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="vision_section_intro_content" :value="__('Vision Introductory Paragraph')" />
                                <textarea id="vision_section_intro_content" name="vision_section_intro_content" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('vision_section_intro_content', $content->vision_section_intro_content) }}</textarea>
                                <x-input-error :messages="$errors->get('vision_section_intro_content')" class="mt-2" />
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('admin.content.vision-items.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Manage Vision Points
                                </a>
                            </div>
                        </fieldset>

                        <fieldset class="mb-8 border p-4 rounded-md">
                            <legend class="text-lg font-semibold text-gray-800 px-2">Concluding Statement</legend>
                            <div class="mt-4 mb-4">
                                <x-input-label for="concluding_statement" :value="__('Concluding Paragraph(s)')" />
                                <textarea id="concluding_statement" name="concluding_statement" rows="6" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('concluding_statement', $content->concluding_statement) }}</textarea>
                                <x-input-error :messages="$errors->get('concluding_statement')" class="mt-2" />
                            </div>
                        </fieldset>

                        {{-- You can add Join Card and Stats Items sections here if needed --}}
                        {{-- For stats_items (JSON), you can use a textarea like before, or a repeater if you build JS for it --}}


                        <div class="flex items-center justify-end mt-8">
                            <x-primary-button>
                                {{ __('Update Main About Content') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>