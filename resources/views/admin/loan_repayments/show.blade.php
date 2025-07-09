<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Loan Repayment Details (#{{ $loanRepayment->id }})
            </h2>
            <a href="{{ route('admin.loan-repayments.index', ['loan_id_filter' => $loanRepayment->loan_id]) }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                ← Back to Repayments List
            </a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200 space-y-4">
                    @include('partials.flash-messages')

                    <div><strong class="text-gray-600">Loan ID:</strong> <a href="{{ route('admin.loans.show', $loanRepayment->loan_id) }}" class="text-blue-600 hover:underline">#{{ $loanRepayment->loan_id }}</a></div>
                    <div><strong class="text-gray-600">Member:</strong> {{ $loanRepayment->user->name }} ({{ $loanRepayment->user->email }})</div>
                    <div><strong class="text-gray-600">Amount Paid:</strong> <span class="font-semibold">RWF {{ number_format($loanRepayment->amount_paid, 2) }}</span></div>
                    <div><strong class="text-gray-600">Payment Date:</strong> {{ $loanRepayment->payment_date->format('F j, Y') }}</div>
                    <div><strong class="text-gray-600">Payment Method:</strong> {{ Str::title(str_replace('_', ' ', $loanRepayment->payment_method)) }}</div>
                    <div><strong class="text-gray-600">Transaction ID/Ref:</strong> {{ $loanRepayment->transaction_id ?: 'N/A' }}</div>
                    <div><strong class="text-gray-600">Status:</strong>
                        <span class="ml-2 px-2 py-0.5 inline-flex text-sm leading-5 font-semibold rounded-full
                            {{ $loanRepayment->status == 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $loanRepayment->status == 'pending_confirmation' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $loanRepayment->status == 'failed' ? 'bg-red-100 text-red-800' : '' }}
                            ">
                            {{ ucfirst(str_replace('_', ' ', $loanRepayment->status)) }}
                        </span>
                    </div>
                    @if($loanRepayment->notes)
                        <div class="pt-2"><strong class="text-gray-600">Member Notes:</strong> <div class="mt-1 p-2 bg-gray-50 rounded text-sm whitespace-pre-wrap">{{ $loanRepayment->notes }}</div></div>
                    @endif
                    @if($loanRepayment->confirmer)
                        <div><strong class="text-gray-600">Processed By:</strong> {{ $loanRepayment->confirmer->name }} on {{ $loanRepayment->confirmation_date->format('M d, Y H:i') }}</div>
                    @endif


                    {{-- Actions for Pending Repayments --}}
                    @if($loanRepayment->status == 'pending_confirmation')
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-md font-semibold text-gray-700 mb-3">Process Repayment:</h3>
                            <div class="flex items-center space-x-3">
                                <form action="{{ route('admin.loan-repayments.confirm', $loanRepayment) }}" method="POST" onsubmit="return confirm('Are you sure you want to CONFIRM this repayment? This will update the loan balance.');">
                                    @csrf
                                    @method('PATCH')
                                    <x-primary-button type="submit" class="bg-green-600 hover:bg-green-700 focus:ring-green-500">
                                        Confirm Repayment
                                    </x-primary-button>
                                </form>
                                {{-- Form to mark as failed --}}
                                <button type="button" onclick="document.getElementById('failRepaymentForm').classList.toggle('hidden')" class="text-red-600 hover:text-red-800">Mark as Failed</button>
                            </div>

                            <form id="failRepaymentForm" action="{{ route('admin.loan-repayments.fail', $loanRepayment) }}" method="POST" class="mt-4 hidden bg-red-50 p-4 rounded-md border border-red-200" onsubmit="return confirm('Are you sure you want to mark this repayment as FAILED?');">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <x-input-label for="admin_notes_fail" :value="__('Reason for Failure (Required)')" class="text-red-700" />
                                    <textarea id="admin_notes_fail" name="admin_notes" rows="2" class="mt-1 block w-full rounded-md border-red-300 shadow-sm focus:border-red-500 focus:ring-red-500" required></textarea>
                                    <x-input-error :messages="$errors->get('admin_notes_fail')" class="mt-2" />
                                </div>
                                <div class="mt-3">
                                     <x-danger-button type="submit">Submit Failed Status</x-danger-button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>