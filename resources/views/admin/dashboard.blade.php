<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrator Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold text-gray-800">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="mt-1 text-sm text-gray-600 mb-8">
                        Here is a summary of the application's current status and quick links to manage it.
                    </p>

                    {{-- =============================================== --}}
                    {{-- Key Statistics Section --}}
                    {{-- =============================================== --}}
                    @can('manage users') {{-- This section is best for full admins --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gray-50 p-6 rounded-lg border">
                            <h4 class="text-sm font-medium text-gray-500">Total Registered Users</h4>
                            <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $totalUsers ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg border">
                            <h4 class="text-sm font-medium text-gray-500">Active Members</h4>
                            <p class="text-3xl font-bold text-green-600 mt-1">{{ $activeMembersCount ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg border">
                            <h4 class="text-sm font-medium text-gray-500">Pending Memberships</h4>
                            <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $pendingMembershipsCount ?? 'N/A' }}</p>
                            <a href="{{ route('admin.memberships.index', ['status_filter' => 'pending']) }}" class="text-xs text-indigo-600 hover:underline block mt-1">Review Pending</a>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg border">
                            <h4 class="text-sm font-medium text-gray-500">Pending Profile Updates</h4>
                             @php $pendingUpdatesCount = \App\Models\MemberProfileUpdate::where('status', 'pending')->count(); @endphp
                            <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $pendingUpdatesCount }}</p>
                            <a href="{{ route('admin.profile-updates.index') }}" class="text-xs text-indigo-600 hover:underline block mt-1">Review Updates</a>
                        </div>
                    </div>
                    @endcan

                    {{-- =============================================== --}}
                    {{-- Quick Actions / Management Links --}}
                    {{-- =============================================== --}}
                    <h4 class="text-lg font-semibold text-gray-700 mb-4 border-t pt-6">Management Areas</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        {{-- Membership Management Card --}}
                        @can('approve memberships')
                        <div class="p-6 bg-blue-50 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                            <h4 class="text-lg font-semibold text-blue-700 mb-3">Membership Management</h4>
                            <ul class="space-y-2 text-sm">
                                @can('manage all memberships') {{-- Admin-specific view --}}
                                    <li><a href="{{ route('admin.memberships.index') }}" class="text-blue-600 hover:text-blue-800 hover:underline">- View All Members</a></li>
                                @endcan
                                <li><a href="{{ route('admin.memberships.index', ['status_filter' => 'pending']) }}" class="text-blue-600 hover:text-blue-800 hover:underline">- Pending Approvals</a></li>
                                <li><a href="{{ route('admin.profile-updates.index') }}" class="text-blue-600 hover:text-blue-800 hover:underline">- Pending Profile Updates</a></li>
                                @can('manage member categories') {{-- Admin-specific setting --}}
                                    <li><a href="{{ route('admin.member-categories.index') }}" class="text-blue-600 hover:text-blue-800 hover:underline">- Manage Member Categories</a></li>
                                @endcan
                            </ul>
                        </div>
                        @endcan

                        {{-- Financial Management Card --}}
                        @canany(['approve contributions', 'approve loans'])
                        <div class="p-6 bg-green-50 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                            <h4 class="text-lg font-semibold text-green-700 mb-3">Financials</h4>
                            <ul class="space-y-2 text-sm">
                                @can('approve contributions')
                                    <li><a href="{{ route('admin.contributions.index') }}" class="text-green-600 hover:text-green-800 hover:underline">- Manage Contributions</a></li>
                                @endcan
                                @can('approve loans')
                                    <li><a href="{{ route('admin.loans.index') }}" class="text-green-600 hover:text-green-800 hover:underline">- Manage Loans</a></li>
                                @endcan
                                @can('confirm loan repayments')
                                    <li><a href="{{ route('admin.loan-repayments.index') }}" class="text-green-600 hover:text-green-800 hover:underline">- Loan Repayments</a></li>
                                @endcan
                            </ul>
                        </div>
                        @endcanany

                        {{-- Reporting Card --}}
                        @can('generate reports')
                        <div class="p-6 bg-yellow-50 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                            <h4 class="text-lg font-semibold text-yellow-700 mb-3">Reports</h4>
                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ route('admin.reports.members') }}" class="text-yellow-600 hover:text-yellow-800 hover:underline">- Member List Report</a></li>
                                <li><a href="{{ route('admin.reports.contributions') }}" class="text-yellow-600 hover:text-yellow-800 hover:underline">- Contributions Report</a></li>
                                <li><a href="{{ route('admin.reports.loans') }}" class="text-yellow-600 hover:text-yellow-800 hover:underline">- Loans Report</a></li>
                            </ul>
                        </div>
                        @endcan

                        {{-- Site & User Administration Card --}}
                        @can('manage roles and permissions')
                        <div class="p-6 bg-indigo-50 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                            <h4 class="text-lg font-semibold text-indigo-700 mb-3">System Administration</h4>
                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">- Manage System Users</a></li>
                                <li><a href="{{ route('admin.roles.index') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">- Manage Roles</a></li>
                                <li><a href="{{ route('admin.permissions.index') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">- Manage Permissions</a></li>
                                <li><a href="{{ route('admin.offices.index') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">- Manage Offices & Leadership</a></li>
                            </ul>
                        </div>
                        @endcan

                        {{-- Content Management Card --}}
                        @can('manage website content')
                        <div class="p-6 bg-purple-50 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                            <h4 class="text-lg font-semibold text-purple-700 mb-3">Website Content</h4>
                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ route('admin.content.hero-slides.index') }}" class="text-purple-600 hover:text-purple-800 hover:underline">- Hero Slides</a></li>
                                <li><a href="{{ route('admin.content.about.edit') }}" class="text-purple-600 hover:text-purple-800 hover:underline">- About Us Page</a></li>
                                <li><a href="{{ route('admin.content.contacts.index') }}" class="text-purple-600 hover:text-purple-800 hover:underline">- Contact Messages</a></li>
                            </ul>
                        </div>
                        @endcan

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>