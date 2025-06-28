<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Loan Details for {{ $loan->user->name }}
            </h2>
            <a href="{{ route('admin.loans.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">← Back to All Loans</a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-6">
                        {{-- Column 1 --}}
                        <div class="space-y-3">
                            <div><strong class="text-gray-600">Applicant:</strong> {{ $loan->user->name }} ({{ $loan->user->email }})</div>
                            <div><strong class="text-gray-600">Application Date:</strong> {{ $loan->application_date->format('F j, Y') }}</div>
                            <div><strong class="text-gray-600">Amount Requested:</strong> RWF {{ number_format($loan->amount_requested, 2) }}</div>
                            <div><strong class="text-gray-600">Purpose:</strong> <p class="mt-1 text-gray-700 whitespace-pre-wrap">{{ $loan->purpose }}</p></div>
                        </div>
                        {{-- Column 2 --}}
                       <div class="space-y-3">
                            <div><strong class="text-gray-600">Status:</strong>
                                <span class="ml-2 px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{-- ... status classes ... --}}
                                    ">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </div>
                            <div><strong class="text-gray-600">Amount Approved:</strong> {{ $loan->amount_approved ? 'RWF ' . number_format($loan->amount_approved, 2) : 'N/A' }}</div>
                            <div><strong class="text-gray-600">Interest Rate:</strong> {{ $loan->interest_rate ? $loan->interest_rate . '%' : 'N/A' }}</div>
                            <div><strong class="text-gray-600">Term:</strong> {{ $loan->term_months ? $loan->term_months . ' months' : 'N/A' }}</div>
                            {{-- New field for Total Repayment --}}
                            @if($loan->total_repayment_amount !== null)
                            <div class="mt-1">
                                <strong class="text-gray-600">Est. Total Repayment:</strong>
                                <span class="text-gray-900 font-semibold">RWF {{ number_format($loan->total_repayment_amount, 2) }}</span>
                            </div>
                            @endif
                        </div>
                        {{-- Column 3 --}}
                        <div class="space-y-3">
                            <div><strong class="text-gray-600">Approval Date:</strong> {{ $loan->approval_date ? $loan->approval_date->format('F j, Y') : 'N/A' }}</div>
                            <div><strong class="text-gray-600">Approved By:</strong> {{ $loan->approver->name ?? 'N/A' }}</div>
                            <div><strong class="text-gray-600">Disbursement Date:</strong> {{ $loan->disbursement_date ? $loan->disbursement_date->format('F j, Y') : 'N/A' }}</div>
                            <div><strong class="text-gray-600">First Payment Due:</strong> {{ $loan->first_payment_due_date ? $loan->first_payment_due_date->format('F j, Y') : 'N/A' }}</div>
                            <div><strong class="text-gray-600">Final Due Date:</strong> {{ $loan->final_due_date ? $loan->final_due_date->format('F j, Y') : 'N/A' }}</div>
                        </div>
                    </div>

                    @if($loan->admin_notes)
                    <div class="mt-6 pt-4 border-t">
                        <strong class="text-gray-600">Admin Notes:</strong>
                        <p class="mt-1 text-gray-700 whitespace-pre-wrap">{{ $loan->admin_notes }}</p>
                    </div>
                    @endif

                    <div class="mt-8 pt-6 border-t flex justify-end">
                         <a href="{{ route('admin.loans.edit', $loan) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Edit Loan Record
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>