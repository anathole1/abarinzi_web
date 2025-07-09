<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Manage Loan Repayments') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @include('partials.flash-messages')

                    <form method="GET" action="{{ route('admin.loan-repayments.index') }}" class="mb-6 p-4 bg-gray-50 rounded-md">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div>
                                <label for="user_filter" class="block text-sm font-medium text-gray-700">Filter by Member</label>
                                <select name="user_filter" id="user_filter" class="mt-1 block w-full tom-select-simple"> {{-- Add class for TomSelect if used --}}
                                    <option value="">All Members</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_filter') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="loan_id_filter" class="block text-sm font-medium text-gray-700">Filter by Loan ID</label>
                                 <select name="loan_id_filter" id="loan_id_filter" class="mt-1 block w-full tom-select-simple">
                                    <option value="">All Loans</option>
                                    @foreach($loans as $loan)
                                        <option value="{{ $loan->id }}" {{ request('loan_id_filter') == $loan->id ? 'selected' : '' }}>
                                            Loan #{{ $loan->id }} ({{ $loan->user->name }}) - RWF {{number_format($loan->amount_approved ?? $loan->amount_requested,0)}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status_filter" class="block text-sm font-medium text-gray-700">Filter by Status</label>
                                <select name="status_filter" id="status_filter" class="mt-1 block w-full ...">
                                    <option value="">All Statuses</option>
                                    <option value="pending_confirmation" {{ request('status_filter') == 'pending_confirmation' ? 'selected' : '' }}>Pending Confirmation</option>
                                    <option value="confirmed" {{ request('status_filter') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="failed" {{ request('status_filter') == 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>
                            <div class="flex space-x-2">
                                <button type="submit" class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Filter</button>
                                @if(request()->hasAny(['status_filter', 'user_filter', 'loan_id_filter']))
                                <a href="{{ route('admin.loan-repayments.index') }}" class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Clear</a>
                                @endif
                            </div>
                        </div>
                    </form>

                    @if($repayments->isEmpty())
                        <p class="text-gray-600">No loan repayments found matching criteria.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Loan ID</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Member</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Amount Paid</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Payment Date</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Method</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($repayments as $repayment)
                                        <tr class="{{ $repayment->status === 'pending_confirmation' ? 'bg-yellow-50 hover:bg-yellow-100' : 'hover:bg-gray-50' }}">
                                            <td class="px-4 py-3"><a href="{{ route('admin.loans.show', $repayment->loan_id) }}" class="text-blue-600 hover:underline">#{{ $repayment->loan_id }}</a></td>
                                            <td class="px-4 py-3">{{ $repayment->user->name }}</td>
                                            <td class="px-4 py-3">RWF {{ number_format($repayment->amount_paid, 2) }}</td>
                                            <td class="px-4 py-3">{{ $repayment->payment_date->format('M d, Y') }}</td>
                                            <td class="px-4 py-3">{{ Str::title(str_replace('_', ' ', $repayment->payment_method)) }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $repayment->status == 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $repayment->status == 'pending_confirmation' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $repayment->status == 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                                    ">
                                                    {{ ucfirst(str_replace('_', ' ', $repayment->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap font-medium">
                                                <a href="{{ route('admin.loan-repayments.show', $repayment) }}" class="text-indigo-600 hover:text-indigo-900">View & Process</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $repayments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>