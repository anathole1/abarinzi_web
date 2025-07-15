<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                     @role('admin') {{-- Spatie's directive --}}
                    <x-nav-link :href="route('admin.memberships.index')" :active="request()->routeIs('admin.memberships.index*')">
                        {{ __('Manage Memberships') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.member-categories.index')" :active="request()->routeIs('admin.member-categories.*')">
                        {{ __('Member Categories') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.contributions.index')" :active="request()->routeIs('admin.contributions.*')">
                        {{ __('Manage Contributions') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.loans.index')" :active="request()->routeIs('admin.loans.*')">
                        {{ __('Manage Loans') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.loan-repayments.index')" :active="request()->routeIs('admin.loan-repayments.*')">
                        {{ __('Loan Repayments') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Manage Users') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')">
                        {{ __('Manage Roles') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.permissions.index')" :active="request()->routeIs('admin.permissions.*')">
                        {{ __('Manage Permissions') }}
                    </x-nav-link>
                    @endrole

                     {{-- Content Management Links (For Admin OR Author) --}}
                    @role('author')
                        <x-nav-link :href="route('admin.content.hero-slides.index')" :active="request()->routeIs('admin.content.hero-slides.*')">
                            {{ __('Hero Slides') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.content.about.edit')" :active="request()->routeIs('admin.content.about.edit')">
                            {{ __('About Us Page') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.content.contacts.index')" :active="request()->routeIs('admin.content.contacts.*')">
                            {{ __('Contact Messages') }}
                        </x-nav-link>
                    @endrole

                    @auth {{-- Ensure user is logged in --}}
                    @if(Auth::user()->hasRole('member') && Auth::user()->memberProfile && Auth::user()->memberProfile->status === 'approved')
                        <x-nav-link :href="route('member.contributions.index')" :active="request()->routeIs('member.contributions.*')">
                            {{ __('My Contributions') }}
                        </x-nav-link>
                        <x-nav-link :href="route('member.loans.index')" :active="request()->routeIs('member.loans.*')">
                            {{ __('My Loans') }}
                        </x-nav-link>
                         @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
            {{-- Notification Icon (Only for Admin/Author for this use case) --}}
                @hasanyrole('admin|author')
                    <a href="{{ route('notifications.index') }}" class="relative inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-lg mr-2">
                        {{-- Bell Icon SVG --}}
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span class="sr-only">Notifications</span>

                        {{-- Notification Count Badge --}}
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <div class="absolute inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-1 -right-1">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </div>
                        @endif
                    </a>
                @endhasanyrole
                            <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @role('admin') {{-- Spatie's directive --}}
            <x-responsive-nav-link :href="route('admin.memberships.index')" :active="request()->routeIs('admin.memberships.index')">
                {{ __('Manage Memberships') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.member-categories.index')" :active="request()->routeIs('admin.member-categories.*')">
                {{ __('Member Categories') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.contributions.index')" :active="request()->routeIs('admin.contributions.*')">
                {{ __('Manage Contributions') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.loans.index')" :active="request()->routeIs('admin.loans.*')">
                {{ __('Manage Loans') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.loan-repayments.index')" :active="request()->routeIs('admin.loan-repayments.*')">
                {{ __('Loan Repayments') }}
            </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                {{ __('Manage Users') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')">
                {{ __('Manage Roles') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.permissions.index')" :active="request()->routeIs('admin.permissions.*')">
                {{ __('Manage Permissions') }}
            </x-responsive-nav-link>
            @endrole

            
            {{-- Content Management Links (For Admin OR Author) --}}
            @role('author')
                 <div class="pt-2 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">Content Management</div>
                    </div>
                    <div class="mt-1 space-y-1">
                        <x-responsive-nav-link :href="route('admin.content.hero-slides.index')" :active="request()->routeIs('admin.content.hero-slides.*')">
                            {{ __('Hero Slides') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.content.about.edit')" :active="request()->routeIs('admin.content.about.edit')">
                            {{ __('About Us Page') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.content.contacts.index')" :active="request()->routeIs('admin.content.contacts.*')">
                            {{ __('Contact Messages') }}
                        </x-responsive-nav-link>
                    </div>
                </div>
            @endrole
                    @auth {{-- Ensure user is logged in --}}
                        @if(Auth::user()->hasRole('member') && Auth::user()->memberProfile && Auth::user()->memberProfile->status === 'approved')
                        <x-responsive-nav-link :href="route('member.contributions.index')" :active="request()->routeIs('member.contributions.*')">
                            {{ __('My Contributions') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('member.loans.index')" :active="request()->routeIs('member.loans.*')">
                            {{ __('My Loans') }}
                        </x-responsive-nav-link>
                        @endif
                    @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
