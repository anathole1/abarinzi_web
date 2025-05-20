<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Good practice --}}

    <title>{{ $title ?? config('app.name', 'EFOTEC Alumni') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Any additional head content for public pages --}}
    @stack('head')
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-800">
    <div class="min-h-screen flex flex-col">
        <!-- Public Navigation (can be same as welcome page or simpler) -->
        <nav class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('welcome') }}" class="flex-shrink-0">
                            <img class="h-10 w-auto" src="{{ asset('images/logo-placeholder.svg') }}" alt="{{ config('app.name', 'EFOTEC Alumni') }} Logo"> {{-- Replace with your actual logo --}}
                        </a>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="{{ route('welcome') }}#about" class="text-blue-100 hover:bg-blue-500 hover:text-white px-3 py-2 rounded-md text-sm font-medium">About</a>
                                <a href="{{ route('welcome') }}#contact" class="text-blue-100 hover:bg-blue-500 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Contact</a>
                                {{-- Add other relevant public nav links --}}
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
                        <!-- Mobile menu button (you can reuse the JS from welcome.blade.php) -->
                        <button type="button" id="public-mobile-menu-button" class="bg-blue-600 inline-flex items-center justify-center p-2 rounded-md text-blue-200 hover:text-white hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="public-mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                            <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile menu -->
            <div class="md:hidden hidden" id="public-mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="{{ route('welcome') }}#about" class="text-blue-100 hover:bg-blue-500 hover:text-white block px-3 py-2 rounded-md text-base font-medium">About</a>
                    <a href="{{ route('welcome') }}#contact" class="text-blue-100 hover:bg-blue-500 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Contact</a>
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

        <!-- Page Heading (Optional, if passed via slot) -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot }} {{-- This is where the content of your page will be injected --}}
        </main>

        <!-- Public Footer (can be same as welcome page or simpler) -->
        <footer class="bg-blue-800 text-blue-100">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center">
                <p>© {{ date('Y') }} {{ config('app.name', 'EFOTEC Alumni') }}. All rights reserved.</p>
                {{-- Add any other footer links or info --}}
            </div>
        </footer>
    </div>

    {{-- JavaScript for mobile menu (if different from app.js) --}}
    <script>
        // Mobile Menu Toggle for public layout
        const publicMobileMenuButton = document.getElementById('public-mobile-menu-button');
        const publicMobileMenu = document.getElementById('public-mobile-menu');
        if (publicMobileMenuButton && publicMobileMenu) {
            const publicMobileMenuOpenIcon = publicMobileMenuButton.querySelector('svg:first-child');
            const publicMobileMenuCloseIcon = publicMobileMenuButton.querySelector('svg:last-child');

            publicMobileMenuButton.addEventListener('click', () => {
                const isExpanded = publicMobileMenuButton.getAttribute('aria-expanded') === 'true' || false;
                publicMobileMenuButton.setAttribute('aria-expanded', !isExpanded);
                publicMobileMenu.classList.toggle('hidden');
                publicMobileMenuOpenIcon.classList.toggle('hidden');
                publicMobileMenuCloseIcon.classList.toggle('hidden');
            });
        }
    </script>
    @stack('scripts') {{-- For page-specific scripts --}}
</body>
</html>