<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- ... head content ... --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style> /* ... any specific styles from previous example if needed ... */ </style>
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-800">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg sticky top-0 z-50">
              <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex-shrink-0">
                            {{-- Replace with your logo --}}
                            <img class="h-10 w-auto" src="{{asset('logo/logo.png')}}" alt="Abarinzi Family">
                        </a>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="#about" class="text-blue-100 hover:bg-blue-500 hover:text-white px-3 py-2 rounded-md text-sm font-medium">About Us</a>
                                {{-- <a href="#services" class="text-blue-100 hover:bg-blue-500 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Services</a> --}}
                                <a href="#contact" class="text-blue-100 hover:bg-blue-500 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Contact Us</a>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="text-blue-100 hover:bg-blue-500 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="text-blue-100 hover:bg-blue-500 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Log in</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="ml-4 text-blue-100 bg-blue-500 hover:bg-blue-400 px-3 py-2 rounded-md text-sm font-medium">Register</a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <!-- Mobile menu button -->
                        <button type="button" id="mobile-menu-button" class="bg-blue-600 inline-flex items-center justify-center p-2 rounded-md text-blue-200 hover:text-white hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile menu, show/hide based on menu state. -->
            <div class="md:hidden hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="#about" class="text-blue-100 hover:bg-blue-500 hover:text-white block px-3 py-2 rounded-md text-base font-medium">About Us</a>
                    <a href="#contact" class="text-blue-100 hover:bg-blue-500 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Contact Us</a>
                </div>
                <div class="pt-4 pb-3 border-t border-blue-700">
                    <div class="px-2 space-y-1">
                         @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-100 hover:text-white hover:bg-blue-500">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-100 hover:text-white hover:bg-blue-500">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-100 hover:text-white hover:bg-blue-500">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section (Slideshow) -->
        @if(isset($heroSlides) && $heroSlides->count() > 0)
            <x-slideshow :slides="$heroSlides" />
        @else
            <header class="relative h-screen flex items-center justify-center bg-blue-700 text-white">
                <div class="text-center p-4">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to ABARINZI Family</h1>
                    <p class="text-lg md:text-xl mb-6 max-w-2xl">Content for the hero section is being prepared. Please check back soon!</p>
                </div>
            </header>
        @endif


        <!-- Main Content Area -->
        <main class="flex-grow">
            <!-- About Us Section -->
            <!-- In resources/views/welcome.blade.php -->
@if($aboutContent)
<section id="about" class="py-16 lg:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 lg:mb-16">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl lg:text-5xl mb-4">
                {{ $aboutContent->page_main_title ?? 'About Abarinzi Family' }}
            </h2>
            @if($aboutContent->page_main_subtitle)
            <div class="w-24 h-1 bg-blue-600 mx-auto mb-6"></div>
            <p class="max-w-3xl mx-auto text-lg text-gray-600 leading-relaxed">
                {{ $aboutContent->page_main_subtitle }}
            </p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-start">
            {{-- Left Column: Introduction --}}
            <div class="space-y-6">
                <h3 class="text-2xl font-semibold text-gray-800 mb-3">{{ $aboutContent->intro_title ?? 'Introduction' }}</h3>
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($aboutContent->intro_content)) !!}
                </div>
            </div>

            {{-- Right Column: Mission Summary & Link to More --}}
            <div class="space-y-8">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold text-blue-700 mb-2">{{ $aboutContent->mission_title ?? 'Mission Statement' }}</h3>
                    @if($aboutContent->mission_summary)
                        <p class="text-gray-600 leading-relaxed mb-4">{{ $aboutContent->mission_summary }}</p>
                    @else
                        <p class="text-gray-600 leading-relaxed mb-4">{{ Str::limit(strip_tags($aboutContent->mission_content), 200) }}</p>
                    @endif

                    <h3 class="text-xl font-semibold text-blue-700 mb-2 mt-6">{{ $aboutContent->vision_section_title ?? 'Our Vision' }}</h3>
                    @if($aboutContent->vision_section_intro_content)
                         <p class="text-gray-600 leading-relaxed mb-4">{{ Str::limit(strip_tags($aboutContent->vision_section_intro_content), 150) }}</p>
                    @endif

                    <a href="{{ route('about.work-vision') }}"
                       class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-md transition-colors duration-200 text-center w-full sm:w-auto">
                        Read More: Our Work & Vision
                    </a>
                </div>

                {{-- Optional Join Card can still be here --}}
                @if($aboutContent->join_card_title)
                <div class="bg-gradient-to-r from-blue-700 to-blue-900 p-6 rounded-lg shadow-xl text-white transform transition-transform duration-300 hover:scale-105">
                    <h4 class="text-xl font-semibold mb-2">{{ $aboutContent->join_card_title }}</h4>
                    @if($aboutContent->join_card_text)
                    <p class="mb-4 text-blue-100">{{ $aboutContent->join_card_text }}</p>
                    @endif
                    <a href="{{ route('register') }}" class="block w-full sm:w-auto text-center bg-white text-blue-700 px-4 py-2 rounded-md font-medium hover:bg-blue-50 transition-colors duration-200">
                        {{ $aboutContent->join_card_button_text ?? 'Register Now' }}
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Stats Section (if kept on homepage) --}}
        @if($aboutContent->stats_items && count($aboutContent->stats_items) > 0)
        <div class="mt-16 lg:mt-20">
            {{-- ... your stats display code ... --}}
        </div>
        @endif
    </div>
</section>
@endif
            {{-- @if($aboutContent)
            <section id="about" class="py-20 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl mb-4">
                            {{ $aboutContent->main_title ?? 'About Us' }}
                        </h2>
                        <div class="w-20 h-1 bg-blue-600 mx-auto mb-6"></div>
                        @if($aboutContent->main_subtitle)
                        <p class="max-w-2xl mx-auto text-lg text-gray-600">
                            {{ $aboutContent->main_subtitle }}
                        </p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                        <div class="space-y-6">
                            <h3 class="text-2xl font-bold text-gray-900">
                                {{ $aboutContent->story_title ?? 'Our Story' }}
                            </h3>
                            @if($aboutContent->story_paragraph1)
                            <p class="text-gray-600">{{ $aboutContent->story_paragraph1 }}</p>
                            @endif
                            @if($aboutContent->story_paragraph2)
                            <p class="text-gray-600">{{ $aboutContent->story_paragraph2 }}</p>
                            @endif
                            <div class="pt-4">
                                <a href="#contact" class="inline-flex items-center text-blue-600 font-medium hover:text-blue-800 transition-colors duration-200">
                                    Contact us to learn more
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @if(is_array($aboutContent->value_cards))
                                @foreach($aboutContent->value_cards as $card)
                                <div class="bg-white p-6 rounded-lg shadow-md transform transition-transform duration-300 hover:scale-105">
                                    @if(isset($card['icon_svg_path']))
                                    <div class="text-blue-600 w-10 h-10 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon_svg_path'] }}" />
                                        </svg>
                                    </div>
                                    @endif
                                    <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ $card['title'] ?? 'Value' }}</h4>
                                    <p class="text-gray-600">{{ $card['description'] ?? '' }}</p>
                                </div>
                                @endforeach
                            @endif
                            <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-lg shadow-md text-white transform transition-transform duration-300 hover:scale-105">
                                <h4 class="text-xl font-semibold mb-2">{{ $aboutContent->join_title ?? 'Join Us' }}</h4>
                                @if($aboutContent->join_text)
                                <p class="mb-4">{{ $aboutContent->join_text }}</p>
                                @endif
                                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-4 py-2 rounded-md font-medium hover:bg-blue-50 transition-colors duration-200">
                                    {{ $aboutContent->join_button_text ?? 'Register Now' }}
                                </a>
                            </div>
                        </div>
                    </div>


                    @if(is_array($aboutContent->stats) && count($aboutContent->stats) > 0)
                    <div class="mt-20">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                            @foreach($aboutContent->stats as $stat)
                            <div class="p-4">
                                <p class="text-4xl font-bold text-blue-600 mb-2">{{ $stat['value'] ?? '0' }}</p>
                                <p class="text-gray-600">{{ $stat['label'] ?? 'Stat' }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </section>
            @else
            <section id="about" class="py-20 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <p class="text-gray-600">About Us content is being updated. Please check back soon!</p>
                </div>
            </section>
            @endif --}}

            <!-- Call to Action / Other Sections -->
            <section class="py-16 bg-blue-700 text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-3xl font-extrabold sm:text-4xl">Ready to Reconnect?</h2>
                    <p class="mt-4 text-lg text-blue-100 max-w-2xl mx-auto">
                        Become an active member of the Abarinzi Family. Register today to access exclusive resources, events, and networking opportunities.
                    </p>
                    <div class="mt-8">
                        <a href="{{ route('register') }}" class="inline-block bg-yellow-500 hover:bg-yellow-400 text-blue-800 font-bold py-3 px-10 rounded-lg text-lg transition duration-300 ease-in-out transform hover:scale-105">
                            Register Now
                        </a>
                    </div>
                </div>
            </section>
            {{-- ... your existing CTA section ... --}}

            <!-- Contact Us Section -->
            @if(isset($contactDetails))
            <section id="contact" class="py-20 bg-gradient-to-b from-white to-blue-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl mb-4">
                            {{ $contactDetails['title'] ?? 'Contact Us' }}
                        </h2>
                        <div class="w-20 h-1 bg-blue-600 mx-auto mb-6"></div>
                        <p class="max-w-2xl mx-auto text-lg text-gray-600">
                            {{ $contactDetails['subtitle'] ?? 'Get in touch with us.' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        {{-- Contact Info Panel --}}
                        <div class="bg-blue-700 text-white rounded-lg shadow-xl p-8">
                            <h3 class="text-2xl font-bold mb-6">Get In Touch</h3>
                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div class="text-blue-300 w-6 h-6 mr-4 mt-1 flex-shrink-0"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg></div>
                                    <div>
                                        <h4 class="font-semibold mb-1">Our Location</h4>
                                        <p>{{ $contactDetails['location_line1'] ?? 'Address Line 1' }}</p>
                                        <p>{{ $contactDetails['location_line2'] ?? 'City, Country' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="text-blue-300 w-6 h-6 mr-4 mt-1 flex-shrink-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg></div>
                                    <div>
                                        <h4 class="font-semibold mb-1">Call Us</h4>
                                        <p>{{ $contactDetails['phone'] ?? 'N/A' }}</p>
                                        @if(isset($contactDetails['phone_hours']))<p class="text-sm text-blue-200">{{ $contactDetails['phone_hours'] }}</p>@endif
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="text-blue-300 w-6 h-6 mr-4 mt-1 flex-shrink-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg></div>
                                    <div>
                                        <h4 class="font-semibold mb-1">Email Us</h4>
                                        <p><a href="mailto:{{ $contactDetails['email'] ?? '#' }}" class="hover:underline">{{ $contactDetails['email'] ?? 'N/A' }}</a></p>
                                        @if(isset($contactDetails['email_response_time']))<p class="text-sm text-blue-200">{{ $contactDetails['email_response_time'] }}</p>@endif
                                    </div>
                                </div>
                            </div>
                            @if(isset($contactDetails['social_links']) && is_array($contactDetails['social_links']) && count($contactDetails['social_links']) > 0)
                            <div class="mt-12">
                                <h4 class="font-semibold mb-4">Follow Us</h4>
                                <div class="flex space-x-4">
                                    @foreach($contactDetails['social_links'] as $social)
                                    <a href="{{ $social['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="h-10 w-10 flex items-center justify-center rounded-full bg-blue-600 hover:bg-blue-500 transition-colors duration-200" aria-label="{{ $social['platform'] ?? 'Social Media' }}">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"> <path d="{{ $social['icon_svg_path'] ?? '' }}"/></svg>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        {{-- Contact Form Panel (keep existing form structure) --}}
                        <div class="bg-white rounded-lg shadow-xl p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Send a Message</h3>

                {{-- Success Message --}}
                @if(session('contact_success'))
                    <div class="mb-6 bg-green-100 text-green-700 p-4 rounded-md" role="alert">
                        {{ session('contact_success') }}
                    </div>
                @endif

                {{-- Error Message --}}
                @if(session('contact_error'))
                    <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-md" role="alert">
                        {{ session('contact_error') }}
                    </div>
                @endif
                 @if ($errors->any() && old('form_type') === 'contact_form')
                    <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-md" role="alert">
                        <p class="font-semibold">Please correct the errors below:</p>
                        <ul class="list-disc list-inside mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" id="contactForm">
                    @csrf
                    <input type="hidden" name="form_type" value="contact_form">
                    <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-8">
                        <div>
                            <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Full Name
                            </label>
                            <input
                                type="text"
                                id="contact_name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                class="w-full px-4 py-3 border @error('name', 'contact_form') border-red-500 @else border-gray-300 @enderror rounded-md focus:ring-blue-500 focus:border-blue-500"
                                placeholder="John Doe"
                            />
                            @error('name', 'contact_form') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address
                            </label>
                            <input
                                type="email"
                                id="contact_email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                class="w-full px-4 py-3 border @error('email', 'contact_form') border-red-500 @else border-gray-300 @enderror rounded-md focus:ring-blue-500 focus:border-blue-500"
                                placeholder="john@example.com"
                            />
                             @error('email', 'contact_form') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="contact_subject" class="block text-sm font-medium text-gray-700 mb-1">
                                Subject
                            </label>
                            <select
                                id="contact_subject"
                                name="subject"
                                required
                                class="w-full px-4 py-3 border @error('subject', 'contact_form') border-red-500 @else border-gray-300 @enderror rounded-md focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="" disabled {{ old('subject') ? '' : 'selected' }}>Select a subject</option>
                                <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                <option value="Membership" {{ old('subject') == 'Membership' ? 'selected' : '' }}>Membership Question</option>
                                <option value="Events" {{ old('subject') == 'Events' ? 'selected' : '' }}>Event Information</option>
                                <option value="Partnership" {{ old('subject') == 'Partnership' ? 'selected' : '' }}>Partnership Opportunity</option>
                                <option value="Feedback" {{ old('subject') == 'Feedback' ? 'selected' : '' }}>Website Feedback</option>
                                <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('subject', 'contact_form') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="contact_message" class="block text-sm font-medium text-gray-700 mb-1">
                                Message
                            </label>
                            <textarea
                                id="contact_message"
                                name="message"
                                required
                                rows="6"
                                class="w-full px-4 py-3 border @error('message', 'contact_form') border-red-500 @else border-gray-300 @enderror rounded-md focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Your message here..."
                            >{{ old('message') }}</textarea>
                            @error('message', 'contact_form') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <button
                                type="submit"
                                id="contactSubmitButton"
                                class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                                Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
                           {{-- ... your existing contact form from previous answer ... --}}
                           {{-- Make sure form action points to route('contact.submit') --}}
                        </div>
                    </div>


                </div>
            </section>
            @endif
        </main>

        <!-- Footer -->
        @if(isset($contactDetails)) {{-- You can create separate $footerDetails if needed --}}
        <footer class="bg-blue-900 text-white">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center">
                <p>© {{ date('Y') }} Abarinzi Family. All rights reserved.</p>
                <p class="text-sm mt-1">Designed with <span class="text-red-400">♥</span> for our Community</p>
            </div>
        </footer>
        @endif
    </div>
    {{-- Your main script for slideshow and mobile menu --}}
    <script> /* ... from previous welcome.blade.php ... */ </script>
    {{-- Contact form submit button disabling script (if you kept it separate) --}}
    <script> /* ... from previous contact form answer ... */ </script>
</body>
</html>