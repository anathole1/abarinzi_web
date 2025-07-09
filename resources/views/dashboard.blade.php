{{-- This is inside your resources/views/dashboard.blade.php --}}
{{-- OR if you have a separate resources/views/admin/dashboard.blade.php, use that --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @hasanyrole('admin|author')
                Admin Dashboard
            @else
                {{ __('Dashboard') }}
            @endhasanyrole
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(Auth::user()->hasRole('admin'))
                        <h3 class="text-xl font-semibold text-gray-800">{{ __("Administrator Dashboard") }}</h3>
                        <p class="mt-1 text-sm text-gray-600 mb-6">
                            {{ __("Welcome, Admin! Manage users, memberships, contributions, loans, content, and generate reports.") }}
                        </p>

                        {{-- Key Statistics --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                            <div class="bg-gray-100 p-6 rounded-lg shadow">
                                <h4 class="font-semibold text-gray-700">Total Registered Users</h4>
                                <p class="text-3xl font-bold text-indigo-600">{{ $totalUsers ?? \App\Models\User::count() }}</p> {{-- Use passed $totalUsers if available --}}
                            </div>
                            <div class="bg-gray-100 p-6 rounded-lg shadow">
                                <h4 class="font-semibold text-gray-700">Active Members</h4>
                                <p class="text-3xl font-bold text-green-600">{{ \App\Models\MemberProfile::where('status', 'approved')->count() }}</p>
                            </div>
                            <div class="bg-gray-100 p-6 rounded-lg shadow">
                                <h4 class="font-semibold text-gray-700">Pending Memberships</h4>
                                <p class="text-3xl font-bold text-yellow-600">{{ $pendingMembershipsCount ?? \App\Models\MemberProfile::where('status', 'pending')->count() }}</p> {{-- Use passed $pendingMembershipsCount --}}
                                <a href="{{ route('admin.memberships.index', ['status_filter' => 'pending']) }}" class="text-sm text-indigo-600 hover:text-indigo-800 block mt-1">Review Pending</a>
                            </div>
                            {{-- Add more stats: Total Contributions, Active Loans etc. --}}
                        </div>

                        {{-- Quick Actions / Management Links --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {{-- Member Management --}}
                            <div class="p-6 bg-blue-50 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                <h4 class="text-lg font-semibold text-blue-700 mb-3">Member Management</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><a href="{{ route('admin.memberships.index') }}" class="text-blue-600 hover:text-blue-800 hover:underline">- View All Members</a></li>
                                    <li><a href="{{ route('admin.memberships.index', ['status_filter' => 'pending']) }}" class="text-blue-600 hover:text-blue-800 hover:underline">- Pending Approvals</a></li>
                                    <li><a href="{{ route('admin.member-categories.index') }}" class="text-blue-600 hover:text-blue-800 hover:underline">- Manage Member Categories</a></li>
                                </ul>
                            </div>

                            {{-- Financial Management --}}
                            <div class="p-6 bg-green-50 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                <h4 class="text-lg font-semibold text-green-700 mb-3">Financials</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><a href="{{ route('admin.contributions.index') }}" class="text-green-600 hover:text-green-800 hover:underline">- Manage Contributions</a></li>
                                    <li><a href="{{ route('admin.loans.index') }}" class="text-green-600 hover:text-green-800 hover:underline">- Manage Loans</a></li>
                                    <li><a href="{{ route('admin.loan-repayments.index') }}" class="text-green-600 hover:text-green-800 hover:underline">- Loan Repayments</a></li>
                                </ul>
                            </div>

                            {{-- Reporting --}}
                            <div class="p-6 bg-yellow-50 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                <h4 class="text-lg font-semibold text-yellow-700 mb-3">Reports</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><a href="{{ route('admin.reports.members') }}" class="text-yellow-600 hover:text-yellow-800 hover:underline">- Member List Report</a></li>
                                    <li><a href="{{ route('admin.reports.contributions') }}" class="text-yellow-600 hover:text-yellow-800 hover:underline">- Contributions Report</a></li>
                                    <li><a href="{{ route('admin.reports.loans') }}" class="text-yellow-600 hover:text-yellow-800 hover:underline">- Loans Report</a></li>
                                    {{-- Add more report links as you create them --}}
                                </ul>
                            </div>

                             {{-- Site & User Administration --}}
                            <div class="p-6 bg-indigo-50 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                <h4 class="text-lg font-semibold text-indigo-700 mb-3">Administration</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">- Manage System Users</a></li>
                                    <li><a href="{{ route('admin.roles.index') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">- Manage Roles</a></li>
                                    <li><a href="{{ route('admin.permissions.index') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">- Manage Permissions</a></li>
                                </ul>
                            </div>

                            {{-- Content Management (if admin also has author role or specific perms) --}}
                            @hasanyrole('admin|author') {{-- Or check specific content permissions --}}
                            <div class="p-6 bg-purple-50 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                <h4 class="text-lg font-semibold text-purple-700 mb-3">Website Content</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><a href="{{ route('admin.content.hero-slides.index') }}" class="text-purple-600 hover:text-purple-800 hover:underline">- Hero Slides</a></li>
                                    <li><a href="{{ route('admin.content.about.edit') }}" class="text-purple-600 hover:text-purple-800 hover:underline">- About Us Page</a></li>
                                    <li><a href="{{ route('admin.content.contacts.index') }}" class="text-purple-600 hover:text-purple-800 hover:underline">- Contact Messages</a></li>
                                </ul>
                            </div>
                            @endhasanyrole
                        </div>

                    {{-- Author Dashboard Content (If admin is NOT also author) --}}
                    @elseif(Auth::user()->hasRole('author') && !Auth::user()->hasRole('admin'))
                        <h3 class="text-xl font-semibold text-gray-800">{{ __("Content Editor Dashboard") }}</h3>
                        <p class="mt-1 text-sm text-gray-600 mb-6">
                            {{ __("Welcome! Manage the website's public content from here.") }}
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div class="p-6 bg-purple-50 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                <h4 class="text-lg font-semibold text-purple-700 mb-3">Website Content</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><a href="{{ route('admin.content.hero-slides.index') }}" class="text-purple-600 hover:text-purple-800 hover:underline">- Hero Slides</a></li>
                                    <li><a href="{{ route('admin.content.about.edit') }}" class="text-purple-600 hover:text-purple-800 hover:underline">- About Us Page</a></li>
                                    <li><a href="{{ route('admin.content.contacts.index') }}" class="text-purple-600 hover:text-purple-800 hover:underline">- Contact Messages</a></li>
                                </ul>
                            </div>
                            {{-- Add other author-specific quick links or info --}}
                        </div>

                    {{-- Member Dashboard Content --}}
                    @elseif(Auth::user()->memberProfile && Auth::user()->memberProfile->status === 'approved')
                        <h3 class="text-lg font-medium text-gray-900">{{ __("Welcome, ") }} {{ Auth::user()->memberProfile->first_name }}!</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __("Your membership is active. You can now access all member features.") }}
                        </p>

                        {{-- Contribution Summary for Approved Members --}}
                        <div class="mt-6 border-t pt-6">
                            <h4 class="text-md font-semibold text-gray-700 mb-4">My Contribution Summary</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-green-50 p-4 rounded-lg shadow">
                                    <div class="text-sm font-medium text-green-700">Total Approved Contribution</div>
                                    <div class="text-2xl font-bold text-green-800">RWF {{ number_format($totalApprovedContributions ?? 0, 2) }}</div>
                                </div>
                                <div class="bg-yellow-50 p-4 rounded-lg shadow">
                                    <div class="text-sm font-medium text-yellow-700">Pending Contributions</div>
                                    <div class="text-2xl font-bold text-yellow-800">{{ $pendingContributionsCount ?? 0 }}</div>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg shadow">
                                    <div class="text-sm font-medium text-blue-700">Total Approved Contributions</div>
                                    <div class="text-2xl font-bold text-blue-800">{{ $approvedContributionsCount ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('member.contributions.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                                    View All My Contributions →
                                </a>
                            </div>
                        </div>

                    @elseif(Auth::user()->memberProfile && Auth::user()->memberProfile->status === 'rejected')
                         <h3 class="text-lg font-medium text-red-700">{{ __("Membership Application Rejected") }}</h3>
                         <p class="mt-1 text-sm text-gray-600">
                            {{ __("Unfortunately, your membership application has been rejected. Please contact support for more information.") }}
                        </p>
                    @else
                         {{ __("You're logged in!") }}
                         {{-- This case might be hit if a user logs in but hasn't completed profile --}}
                         @if(!Auth::user()->memberProfile && Auth::user()->hasRole('member'))
                            <p class="mt-2 text-orange-600">
                                Please <a href="{{ route('member-profile.create') }}" class="underline font-semibold">complete your membership profile</a> to access all features.
                            </p>
                         @endif
                    @endif

                    @includeWhen(session()->has('status') || session()->has('info') || session()->has('warning') || session()->has('error'), 'partials.flash-messages')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



