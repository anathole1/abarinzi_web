<x-app-layout-public> {{-- Or your main public layout --}}
    <x-slot name="title">
        Our Work & Vision - {{ $aboutContent->page_main_title ?? 'Abarinzi Family' }}
    </x-slot>

    <div class="bg-blue-600 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-bold text-white">
                Our Core Work and Future Vision
            </h1>
        </div>
    </div>

    <div class="py-12 lg:py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

            {{-- Core Objectives and Activities Section --}}
            @if($coreObjectives->count() > 0)
            <section id="core-objectives">
                <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-6 border-b pb-3">{{ $aboutContent->core_objectives_section_title ?? 'Core Objectives and Activities' }}</h2>
                <div class="space-y-8">
                    @foreach($coreObjectives as $item)
                    <div class="p-6 bg-gray-50 rounded-lg shadow">
                        <h3 class="text-xl font-semibold text-blue-700 mb-2">{{ $item->title }}</h3>
                        <div class="prose max-w-none text-gray-600 leading-relaxed">
                            {!! nl2br(e($item->content)) !!}
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Vision for the Future Section --}}
            @if($visionItems->count() > 0)
            <section id="vision" class="mt-12">
                <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-4 border-b pb-3">{{ $aboutContent->vision_section_title ?? 'Vision for the Future' }}</h2>
                @if($aboutContent->vision_section_intro_content)
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed mb-8">
                    {!! nl2br(e($aboutContent->vision_section_intro_content)) !!}
                </div>
                @endif
                <div class="grid md:grid-cols-1 gap-8"> {{-- Or keep lg:grid-cols-3 if you prefer columns --}}
                    @foreach($visionItems as $item)
                    <div class="p-6 bg-blue-50 rounded-lg shadow">
                        <h3 class="text-xl font-semibold text-blue-700 mb-2">{{ $item->title }}</h3>
                        <div class="prose max-w-none text-gray-600 text-sm leading-relaxed">
                            {!! nl2br(e($item->content)) !!}
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Concluding Statement --}}
            @if($aboutContent->concluding_statement)
            <section class="mt-12 border-t pt-10">
                 <h2 class="text-2xl font-semibold text-gray-800 mb-4">Our Commitment Continues</h2>
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                     {!! nl2br(e($aboutContent->concluding_statement)) !!}
                </div>
            </section>
            @endif

            <div class="mt-16 text-center">
                <a href="{{ route('welcome') }}#about" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    ← Back to Main About
                </a>
            </div>
        </div>
    </div>
</x-app-layout-public>