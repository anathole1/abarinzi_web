<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Member Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- This view now only handles member-related dashboard content --}}

                    @if(isset($memberProfile) && $memberProfile->status === 'approved')
                        {{-- =============================================== --}}
                        {{-- START: APPROVED MEMBER DASHBOARD CONTENT --}}
                        {{-- =============================================== --}}
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                            <div>
                                <h3 class="text-2xl font-semibold text-gray-800">Welcome, {{ $memberProfile->first_name }}!</h3>
                                <p class="mt-1 text-sm text-gray-600">Your membership is active. Here is a summary of your profile and contributions.</p>
                            </div>
                            <a href="{{ route('member-profile.edit') }}" class="mt-3 sm:mt-0 inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                Update Profile
                            </a>
                        </div>

                        {{-- Member Profile and Category Summary Section --}}
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                            {{-- Profile Info Column --}}
                            <div class="lg:col-span-1 bg-gray-50 p-6 rounded-lg border">
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ $memberProfile->full_photo_url }}" alt="Profile Photo" class="h-24 w-24 rounded-full object-cover mb-4 shadow-md">
                                    <h4 class="text-lg font-bold text-gray-800">{{ $memberProfile->first_name }} {{ $memberProfile->last_name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $memberProfile->email }}</p>
                                    <p class="text-sm text-gray-500">Account No: <span class="font-mono">{{ $memberProfile->accountNo }}</span></p>
                                </div>
                            </div>

                            {{-- Membership Category Details Column --}}
                            @if($memberProfile->memberCategory)
                                <div class="lg:col-span-2 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                                    <h4 class="text-xl font-semibold text-blue-700 mb-4">
                                        Your Membership Tier:
                                        <span class="text-blue-800 font-bold">{{ $memberProfile->memberCategory->name }}</span>
                                    </h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-600 font-medium">Monthly Dues:</p>
                                            <p class="text-gray-800 text-lg font-semibold">RWF {{ number_format($memberProfile->memberCategory->monthly_contribution, 0) }}</p>
                                        </div>
                                        @if($memberProfile->memberCategory->social_monthly_contribution > 0)
                                        <div>
                                            <p class="text-gray-600 font-medium">Social Contribution:</p>
                                            <p class="text-gray-800 text-lg font-semibold">RWF {{ number_format($memberProfile->memberCategory->social_monthly_contribution, 0) }}</p>
                                        </div>
                                        @endif
                                        <div>
                                            <p class="text-gray-600 font-medium">Loan Limit:</p>
                                            <p class="text-gray-800 text-lg font-semibold">{{ $memberProfile->memberCategory->percentage_of_loan_allowed }}% <span class="text-xs">(of savings)</span></p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 font-medium">Loan Interest Rate:</p>
                                            <p class="text-gray-800 text-lg font-semibold">{{ $memberProfile->memberCategory->monthly_interest_rate_loan }}% <span class="text-xs">(monthly)</span></p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Contribution Summary Section --}}
                        <div class="border-t pt-6">
                            <h4 class="text-md font-semibold text-gray-700 mb-4">My Contribution Summary</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="bg-sky-50 p-4 rounded-lg shadow"><div class="text-sm font-medium text-sky-700">Approved Dues</div><div class="text-2xl font-bold text-sky-800">RWF {{ number_format($totalApprovedRegular ?? 0, 2) }}</div></div>
                                <div class="bg-rose-50 p-4 rounded-lg shadow"><div class="text-sm font-medium text-rose-700">Approved Social</div><div class="text-2xl font-bold text-rose-800">RWF {{ number_format($totalApprovedSocial ?? 0, 2) }}</div></div>
                                @if(isset($totalApprovedOther) && $totalApprovedOther > 0)
                                <div class="bg-gray-100 p-4 rounded-lg shadow"><div class="text-sm font-medium text-gray-600">Approved Other</div><div class="text-2xl font-bold text-gray-700">RWF {{ number_format($totalApprovedOther ?? 0, 2) }}</div></div>
                                @endif
                                <div class="bg-yellow-50 p-4 rounded-lg shadow"><div class="text-sm font-medium text-yellow-700">Pending</div><div class="text-2xl font-bold text-yellow-800">{{ $pendingContributionsCount ?? 0 }}</div></div>
                                <div class="bg-green-50 p-4 rounded-lg shadow"><div class="text-sm font-medium text-green-700">Total Approved Items</div><div class="text-2xl font-bold text-green-800">{{ $approvedContributionsCount ?? 0 }}</div></div>
                            </div>
                            <div class="mt-4"><a href="{{ route('member.contributions.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">View All My Contributions &rarr;</a></div>
                        </div>
                        {{-- =============================================== --}}
                        {{-- END: APPROVED MEMBER DASHBOARD CONTENT --}}
                        {{-- =============================================== --}}

                    @elseif(isset($memberProfile) && $memberProfile->status === 'rejected')
                        <h3 class="text-lg font-medium text-red-700">{{ __("Membership Application Rejected") }}</h3>
                        <p class="mt-1 text-sm text-gray-600">
                           {{ __("Unfortunately, your membership application has been rejected. Please review your information and resubmit if you believe this was in error, or contact support for more information.") }}
                        </p>
                         <div class="mt-6">
                            <a href="{{ route('member-profile.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900">
                                Review and Resubmit Profile
                            </a>
                        </div>
                    @else
                        {{-- Fallback for any other authenticated user who lands here (should be rare) --}}
                        <p>{{ __("Welcome to your dashboard!") }}</p>
                    @endif

                    @includeWhen(session()->has('status') || session()->has('info') || session()->has('warning') || session()->has('error'), 'partials.flash-messages')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

