<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Hero Slide: ') }} {{ $heroSlide->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    @include('partials.flash-messages')
                    {{-- IMPORTANT: Add enctype for file uploads --}}
                    <form method="POST" action="{{ route('admin.content.hero-slides.update', $heroSlide) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @include('admin.content.hero_slides._form', ['heroSlide' => $heroSlide])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>