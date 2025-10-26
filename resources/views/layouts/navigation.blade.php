<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Member-Specific Links --}}
                    @if(Auth::user()->can('apply for loans'))
                        <x-nav-link :href="route('member.contributions.index')" :active="request()->routeIs('member.contributions.*')">
                            {{ __('My Contributions') }}
                        </x-nav-link>
                        <x-nav-link :href="route('member.loans.index')" :active="request()->routeIs('member.loans.*', 'member.loan_repayments.*')">
                            {{ __('My Loans') }}
                        </x-nav-link>
                    @endif

                    {{-- Approval & Financial Management Links --}}
                    @canany(['approve memberships', 'approve contributions', 'approve loans'])
                        <x-nav-link :href="route('admin.memberships.index')" :active="request()->routeIs('admin.memberships.*', 'admin.profile-updates.*')">
                            {{ __('Memberships') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.contributions.index')" :active="request()->routeIs('admin.contributions.*')">
                            {{ __('Contributions') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.loans.index')" :active="request()->routeIs('admin.loans.*', 'admin.loan-repayments.*')">
                            {{ __('Loans') }}
                        </x-nav-link>
                    @endcanany

                    {{-- Content Management Links --}}
                    @can('manage website content')
                        <x-nav-link :href="route('admin.content.hero-slides.index')" :active="request()->routeIs('admin.content.*')">
                            {{ __('Website Content') }}
                        </x-nav-link>
                    @endcan

                    {{-- Full Admin System Management Links --}}
                    @can('manage roles and permissions')
                         <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.permissions.*')">
                            {{ __('System Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.offices.index')" :active="request()->routeIs('admin.offices.*')">
                            {{ __('Offices') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.member-categories.index')" :active="request()->routeIs('admin.member-categories.*')">
                            {{ __('Categories') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.reports.members')" :active="request()->routeIs('admin.reports.*')">
                            {{ __('Reports') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                {{-- Notification Icon --}}
                @canany(['manage users', 'approve memberships', 'manage website content'])
                    <a href="{{ route('notifications.index') }}" class="relative inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-lg mr-2">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <div class="absolute inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-1 -right-1">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </div>
                        @endif
                    </a>
                @endcanany

                {{-- User Dropdown --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Account Settings') }}</x-dropdown-link>
                        @if(Auth::user()->memberProfile && Auth::user()->memberProfile->status === 'approved')
                        <x-dropdown-link :href="route('member-profile.edit')">{{ __('Update Membership Info') }}</x-dropdown-link>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
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

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- Member-Specific Links --}}
            @if(Auth::user()->can('apply for loans'))
                <x-responsive-nav-link :href="route('member.contributions.index')" :active="request()->routeIs('member.contributions.*')">
                    {{ __('My Contributions') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('member.loans.index')" :active="request()->routeIs('member.loans.*', 'member.loan_repayments.*')">
                    {{ __('My Loans') }}
                </x-responsive-nav-link>
            @endif
        </div>

        {{-- Approval & Financial Management Links --}}
        @canany(['approve memberships', 'approve contributions', 'approve loans'])
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4"><div class="font-medium text-base text-gray-800">Management</div></div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('admin.memberships.index')" :active="request()->routeIs('admin.memberships.*', 'admin.profile-updates.*')">
                    {{ __('Memberships') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.contributions.index')" :active="request()->routeIs('admin.contributions.*')">
                    {{ __('Contributions') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.loans.index')" :active="request()->routeIs('admin.loans.*', 'admin.loan-repayments.*')">
                    {{ __('Loans') }}
                </x-responsive-nav-link>
            </div>
        </div>
        @endcanany

        {{-- Content Management Links --}}
        @can('manage website content')
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4"><div class="font-medium text-base text-gray-800">Content</div></div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('admin.content.hero-slides.index')" :active="request()->routeIs('admin.content.*')">
                    {{ __('Website Content') }}
                </x-responsive-nav-link>
            </div>
        </div>
        @endcan

        {{-- Full Admin System Management Links --}}
        @can('manage roles and permissions')
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4"><div class="font-medium text-base text-gray-800">System</div></div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.permissions.*')">
                    {{ __('System Users') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.offices.index')" :active="request()->routeIs('admin.offices.*')">
                    {{ __('Offices') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.member-categories.index')" :active="request()->routeIs('admin.member-categories.*')">
                    {{ __('Categories') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reports.members')" :active="request()->routeIs('admin.reports.*')">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
            </div>
        </div>
        @endcan

        {{-- ========================================================= --}}
        {{-- || THIS IS THE BLOCK THAT WAS MISSING - RESTORED BELOW || --}}
        {{-- ========================================================= --}}
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Account Settings') }}
                </x-responsive-nav-link>

                @if(Auth::user()->memberProfile && Auth::user()->memberProfile->status === 'approved')
                <x-responsive-nav-link :href="route('member-profile.edit')">
                    {{ __('Update Membership Info') }}
                </x-responsive-nav-link>
                @endif


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