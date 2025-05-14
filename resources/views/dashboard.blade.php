<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(Auth::user()->hasRole('admin'))
                        <h3 class="text-lg font-medium text-gray-900">{{ __("Admin Dashboard") }}</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __("Welcome, Admin! You can manage users and approve memberships here.") }}
                        </p>
                         <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Example Admin Stat Cards --}}
                            <div class="bg-gray-100 p-4 rounded-lg shadow">
                                <h4 class="font-semibold">Total Users</h4>
                                <p class="text-2xl">{{ \App\Models\User::count() }}</p>
                            </div>
                            <div class="bg-gray-100 p-4 rounded-lg shadow">
                                <h4 class="font-semibold">Pending Memberships</h4>
                                <p class="text-2xl">{{ \App\Models\MemberProfile::where('status', 'pending')->count() }}</p>
                                <a href="{{ route('admin.memberships.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Manage</a>
                            </div>
                        </div>
                    @elseif(Auth::user()->hasRole('author'))
                        <h3 class="text-lg font-medium text-gray-900">{{ __("Web Editor Dashboard") }}</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __("Welcome, Author! You can edit content of website.") }}
                        </p>

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

                    @include('partials.flash-messages') {{-- If you have a global flash message partial --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>