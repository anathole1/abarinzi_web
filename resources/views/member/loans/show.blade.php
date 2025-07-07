<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Loan Application Details') }}
            </h2>
            <a href="{{ route('member.loans.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                ← Back to My Loan Applications
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash-messages')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Loan Information</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500">Application Date</dt>
                            <dd class="mt-1 text-gray-900">{{ $loan->application_date->format('F j, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-gray-900">
                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $loan->status == 'approved' || $loan->status == 'active' || $loan->status == 'repaid' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $loan->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $loan->status == 'rejected' || $loan->status == 'defaulted' ? 'bg-red-100 text-red-800' : '' }}
                                    ">
                                    {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Amount Requested</dt>
                            <dd class="mt-1 text-gray-900">RWF {{ number_format($loan->amount_requested, 2) }}</dd>
                        </div>
                        @if($loan->amount_approved)
                        <div>
                            <dt class="font-medium text-gray-500">Amount Approved</dt>
                            <dd class="mt-1 text-gray-900">RWF {{ number_format($loan->amount_approved, 2) }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="font-medium text-gray-500">Loan Term</dt>
                            <dd class="mt-1 text-gray-900">{{ $loan->term_months }} months</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Interest Rate (Monthly)</dt>
                            <dd class="mt-1 text-gray-900">{{ $loan->interest_rate }}%</dd>
                        </div>
                        @if($loan->display_total_repayment)
                        <div>
                            <dt class="font-medium text-gray-500">Estimated Total Repayment</dt>
                            <dd class="mt-1 text-gray-900 font-semibold">RWF {{ number_format($loan->display_total_repayment, 2) }}</dd>
                        </div>
                        @endif
                        <div class="sm:col-span-2">
                            <dt class="font-medium text-gray-500">Purpose of Loan</dt>
                            <dd class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $loan->purpose }}</dd>
                        </div>
                        @if($loan->approval_date)
                        <div>
                            <dt class="font-medium text-gray-500">Approval Date</dt>
                            <dd class="mt-1 text-gray-900">{{ $loan->approval_date->format('F j, Y') }}</dd>
                        </div>
                        @endif
                        @if($loan->disbursement_date)
                        <div>
                            <dt class="font-medium text-gray-500">Disbursement Date</dt>
                            <dd class="mt-1 text-gray-900">{{ $loan->disbursement_date->format('F j, Y') }}</dd>
                        </div>
                        @endif
                         @if($loan->first_payment_due_date)
                        <div>
                            <dt class="font-medium text-gray-500">First Payment Due</dt>
                            <dd class="mt-1 text-gray-900">{{ $loan->first_payment_due_date->format('F j, Y') }}</dd>
                        </div>
                        @endif
                        @if($loan->admin_notes && ($loan->status == 'rejected' || Auth::user()->can('manage loans'))) {{-- Show notes if rejected or if admin/author views --}}
                        <div class="sm:col-span-2">
                            <dt class="font-medium text-gray-500">Admin Notes / Reason for Rejection</dt>
                            <dd class="mt-1 text-gray-700 bg-gray-50 p-3 rounded-md whitespace-pre-wrap">{{ $loan->admin_notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Loan Repayments Section --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Repayment History</h3>
                        @can('makeRepayment', $loan) {{-- Policy check --}}
                            <a href="{{ route('member.loan_repayments.create', $loan) }}" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md text-sm font-medium">
                                Record a Repayment
                            </a>
                        @endcan
                    </div>

                    @if($loan->repayments->isEmpty())
                        <p class="text-gray-600">No repayments recorded for this loan yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Payment Date</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Amount Paid</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Method</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Transaction ID</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($loan->repayments as $repayment)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $repayment->payment_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">RWF {{ number_format($repayment->amount_paid, 2) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $repayment->payment_method }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $repayment->transaction_id ?: 'N/A' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $repayment->status == 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $repayment->status == 'pending_confirmation' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $repayment->status == 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                                ">
                                                {{ ucfirst(str_replace('_', ' ', $repayment->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>