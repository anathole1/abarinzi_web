<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Membership Application Details
            </h2>
            <a href="{{ route('admin.memberships.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                ← Back to All Memberships
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Column 1: Basic Info --}}
                        <div class="md:col-span-1 space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Full Name</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $memberProfile->first_name }} {{ $memberProfile->last_name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Account Name (Username)</h3>
                                <p class="mt-1 text-gray-700">{{ $memberProfile->user->name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Email Address</h3>
                                <p class="mt-1 text-gray-700">{{ $memberProfile->user->email }}</p>
                            </div>
                             <div>
                                <h3 class="text-sm font-medium text-gray-500">Phone Number</h3>
                                <p class="mt-1 text-gray-700">{{ $memberProfile->phone_number }}</p>
                            </div>
                        </div>

                        {{-- Column 2: Profile Details --}}
                        <div class="md:col-span-1 space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">National ID</h3>
                                <p class="mt-1 text-gray-700">{{ $memberProfile->national_id }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Year Left EFOTEC</h3>
                                <p class="mt-1 text-gray-700">{{ $memberProfile->year_left_efotec ?: 'N/A' }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Current Location</h3>
                                <p class="mt-1 text-gray-700">{{ $memberProfile->current_location ?: 'N/A' }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Membership Category</h3>
                                <p class="mt-1 text-gray-700 font-semibold">{{ ucfirst($memberProfile->membership_category) }} ({{ \App\Models\MemberProfile::getMembershipCategoryAmounts()[$memberProfile->membership_category] ?? 0 }} RWF)</p>
                            </div>
                        </div>

                         {{-- Column 3: Status & Timestamps --}}
                        <div class="md:col-span-1 space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Application Status</h3>
                                <p class="mt-1 text-lg font-semibold">
                                     <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                        @if($memberProfile->status == 'approved') bg-green-100 text-green-800 @endif
                                        @if($memberProfile->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                        @if($memberProfile->status == 'rejected') bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($memberProfile->status) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Submitted On</h3>
                                <p class="mt-1 text-gray-700">{{ $memberProfile->created_at->format('M d, Y \a\t H:i A') }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Last Updated</h3>
                                <p class="mt-1 text-gray-700">{{ $memberProfile->updated_at->format('M d, Y \a\t H:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Actions for Pending Applications --}}
                    @if($memberProfile->status == 'pending')
                        <div class="mt-8 pt-6 border-t border-gray-200 flex items-center justify-end space-x-3">
                            <form action="{{ route('admin.memberships.reject', $memberProfile->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this membership application?');">
                                @csrf
                                @method('PATCH')
                                <x-secondary-button type="submit" class="bg-red-100 text-red-700 hover:bg-red-200 border-red-300 focus:ring-red-500">
                                    Reject Application
                                </x-secondary-button>
                            </form>
                            <form action="{{ route('admin.memberships.approve', $memberProfile->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <x-primary-button type="submit">
                                    Approve Application
                                </x-primary-button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>