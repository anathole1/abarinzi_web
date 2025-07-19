<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-2 sm:mb-0">
                {{ __('Loans Report') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.reports.loans.pdf', request()->query()) }}" target="_blank"
                   class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                   Export PDF
                </a>
                <a href="{{ route('admin.reports.loans.excel', request()->query()) }}"
                   class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
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
                <form method="GET" action="{{ route('admin.reports.loans') }}" class="mb-6 p-4 bg-gray-50 rounded-md">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label for="user_filter" class="block text-sm font-medium text-gray-700">Member</label>
                            <select name="user_filter" id="user_filter" class="mt-1 block w-full tom-select-simple">
                                <option value="">All Members</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_filter') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status_filter" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status_filter" id="status_filter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status_filter') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status_filter') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="repaid" {{ request('status_filter') == 'repaid' ? 'selected' : '' }}>Repaid</option>
                                <option value="defaulted" {{ request('status_filter') == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
                            </select>
                        </div>
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700">Application Date From</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm">
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Filter</button>
                            @if(request()->getQueryString())
                                <a href="{{ route('admin.reports.loans') }}" class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Clear</a>
                            @endif
                        </div>
                    </div>
                </form>

                <h3 class="text-lg font-semibold mb-4 text-gray-700">Loans List ({{ $loans->count() }} results)</h3>
                @if($loans->isEmpty())
                    <p class="text-gray-600">No loans found matching criteria.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">ID</th>
                                <th class="px-4 py-2 text-left font-semibold">Member</th>
                                <th class="px-4 py-2 text-left font-semibold">Amount Appr.</th>
                                <th class="px-4 py-2 text-left font-semibold">Term</th>
                                <th class="px-4 py-2 text-left font-semibold">Rate (Mo)</th>
                                <th class="px-4 py-2 text-left font-semibold">Total Repay</th>
                                <th class="px-4 py-2 text-left font-semibold">Outstanding</th>
                                <th class="px-4 py-2 text-left font-semibold">Status</th>
                                <th class="px-4 py-2 text-left font-semibold">App. Date</th>
                                <th class="px-4 py-2 text-left font-semibold">Approved By</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($loans as $loan)
                            <tr>
                                <td class="px-4 py-2">#{{$loan->id}}</td>
                                <td class="px-4 py-2">{{ $loan->user->name }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($loan->amount_approved ?? $loan->amount_requested, 2) }}</td>
                                <td class="px-4 py-2">{{ $loan->term_months }} mo.</td>
                                <td class="px-4 py-2 text-right">{{ $loan->interest_rate }}%</td>
                                <td class="px-4 py-2 text-right">{{ $loan->display_total_repayment ? number_format($loan->display_total_repayment, 2) : 'N/A' }}</td>
                                <td class="px-4 py-2 text-right font-semibold text-red-600">{{ $loan->outstanding_balance !== null ? number_format($loan->outstanding_balance, 2) : 'N/A' }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $loan->status == 'approved' || $loan->status == 'active' || $loan->status == 'repaid' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $loan->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $loan->status == 'rejected' || $loan->status == 'defaulted' ? 'bg-red-100 text-red-800' : '' }}
                                        ">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $loan->application_date->format('M d, Y') }}</td>
                                <td class="px-4 py-2">{{ $loan->approver->name ?? 'N/A' }}</td>
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