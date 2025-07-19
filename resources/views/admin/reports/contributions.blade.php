<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-2 sm:mb-0">
                {{ __('Contributions Report') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.reports.contributions.pdf', request()->query()) }}" target="_blank"
                   class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1.5 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export PDF
                </a>
                <a href="{{ route('admin.reports.contributions.excel', request()->query()) }}"
                   class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                     <svg class="w-4 h-4 mr-1.5 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M4 10h16M4 14h16M4 18h16"></path></svg>
                    Export Excel
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @include('partials.flash-messages')

                {{-- Filter Form --}}
                <form method="GET" action="{{ route('admin.reports.contributions') }}" class="mb-6 p-4 bg-gray-50 rounded-md">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                        <div>
                            <label for="user_filter" class="block text-sm font-medium text-gray-700">Member</label>
                            <select name="user_filter" id="user_filter" class="mt-1 block w-full tom-select-simple"> {{-- TomSelect can enhance this --}}
                                <option value="">All Members</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_filter') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="type_filter" class="block text-sm font-medium text-gray-700">Type</label>
                            <select name="type_filter" id="type_filter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Types</option>
                                <option value="monthly_membership" {{ request('type_filter') == 'monthly_membership' ? 'selected' : '' }}>Monthly Membership</option>
                                <option value="social_contribution" {{ request('type_filter') == 'social_contribution' ? 'selected' : '' }}>Social Contribution</option>
                                <option value="other" {{ request('type_filter') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="status_filter" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status_filter" id="status_filter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status_filter') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status_filter') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Filter</button>
                            @if(request()->getQueryString())
                                <a href="{{ route('admin.reports.contributions') }}" class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Clear</a>
                            @endif
                        </div>
                    </div>
                </form>

                <h3 class="text-lg font-semibold mb-4 text-gray-700">Contributions List ({{ $contributions->count() }} results)</h3>
                 @if($contributions->isEmpty())
                    <p class="text-gray-600">No contributions found matching criteria.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">ID</th>
                                <th class="px-4 py-2 text-left font-semibold">Member</th>
                                <th class="px-4 py-2 text-left font-semibold">Type</th>
                                <th class="px-4 py-2 text-left font-semibold">Amount (RWF)</th>
                                <th class="px-4 py-2 text-left font-semibold">Payment Method</th>
                                <th class="px-4 py-2 text-left font-semibold">Status</th>
                                <th class="px-4 py-2 text-left font-semibold">Payment Date</th>
                                <th class="px-4 py-2 text-left font-semibold">Approved By</th>
                                <th class="px-4 py-2 text-left font-semibold">Submitted On</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($contributions as $item)
                            <tr>
                                <td class="px-4 py-2">{{ $item->id }}</td>
                                <td class="px-4 py-2">{{ $item->user->name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">{{ Str::title(str_replace('_', ' ', $item->type)) }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($item->amount, 2) }}</td>
                                <td class="px-4 py-2">{{ Str::title(str_replace('_', ' ', $item->payment_method ?? '')) }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $item->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $item->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $item->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                        ">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $item->payment_date ? $item->payment_date->format('M d, Y') : 'N/A' }}</td>
                                <td class="px-4 py-2">{{ $item->approver->name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">{{ $item->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>