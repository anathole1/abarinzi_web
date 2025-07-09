<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Record Repayment for Loan #{{ $loan->id }}
            </h2>
             <a href="{{ route('member.loans.show', $loan) }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                ← Back to Loan Details
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    @include('partials.flash-messages')

                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md text-sm">
                        <h4 class="font-semibold text-blue-700 mb-2">Loan Summary:</h4>
                        <p><span class="text-gray-600">Original Approved Amount:</span> <span class="font-medium">RWF {{ number_format($loan->amount_approved ?? $loan->amount_requested, 2) }}</span></p>
                        <p><span class="text-gray-600">Purpose:</span> {{ Str::limit($loan->purpose, 50) }}</p>
                        {{-- TODO: Display outstanding balance here. This requires calculation logic. --}}
                        {{-- <p><span class="text-gray-600">Approx. Outstanding:</span> <span class="font-medium">RWF [Calculate This]</span></p> --}}
                    </div>

                    <form method="POST" action="{{ route('member.loan_repayments.store', $loan) }}" enctype="multipart/form-data"> {{-- Add enctype if using file upload for proof --}}
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="amount_paid" :value="__('Amount Paid (RWF)')" />
                                <x-text-input id="amount_paid" type="number" name="amount_paid" :value="old('amount_paid')" class="mt-1 block w-full" required step="0.01" min="1" />
                                <x-input-error :messages="$errors->get('amount_paid')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="payment_date" :value="__('Payment Date')" />
                                <x-text-input id="payment_date" type="date" name="payment_date" :value="old('payment_date', date('Y-m-d'))" class="mt-1 block w-full" required max="{{ date('Y-m-d') }}" />
                                <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="payment_method" :value="__('Payment Method Used')" />
                                <select id="payment_method" name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">-- Select Method --</option>
                                    <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money (MoMo)</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="cash_deposit_to_treasurer" {{ old('payment_method') == 'cash_deposit_to_treasurer' ? 'selected' : '' }}>Cash Deposit to Treasurer</option>
                                    {{-- Add other relevant methods --}}
                                </select>
                                <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                            </div>
                             <div>
                                <x-input-label for="transaction_id" :value="__('Transaction ID / Reference (If applicable)')" />
                                <x-text-input id="transaction_id" type="text" name="transaction_id" :value="old('transaction_id')" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('transaction_id')" class="mt-2" />
                            </div>
                            {{-- Optional: Payment Proof Upload --}}
                            {{--
                            <div class="mt-4">
                                <x-input-label for="payment_proof" :value="__('Payment Proof (Screenshot/Receipt - Optional)')" />
                                <input id="payment_proof" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="payment_proof" accept="image/*,.pdf">
                                <p class="mt-1 text-xs text-gray-500">Max 2MB. Allowed: JPG, PNG, PDF.</p>
                                <x-input-error :messages="$errors->get('payment_proof')" class="mt-2" />
                            </div>
                            --}}
                            <div>
                                <x-input-label for="notes" :value="__('Notes (Optional - e.g., specific instructions)')" />
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>
                        <p class="mt-6 text-sm text-gray-600">
                            After submission, your repayment will be pending confirmation by an administrator.
                        </p>
                        <div class="mt-8 flex items-center justify-end space-x-4">
                             <a href="{{ route('member.loans.show', $loan) }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <x-primary-button>
                                {{ __('Submit Repayment Information') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>




<!-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Record Repayment for Loan #') }}{{ $loan->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    @include('partials.flash-messages')

                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md text-sm">
                        <p><span class="font-medium">Loan Amount:</span> RWF {{ number_format($loan->amount_approved ?? $loan->amount_requested, 2) }}</p>
                        <p><span class="font-medium">Purpose:</span> {{ $loan->purpose }}</p>
                        {{-- You can calculate and show outstanding balance here later --}}
                    </div>

                    <form method="POST" action="{{ route('member.loan_repayments.store', $loan) }}">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="amount_paid" :value="__('Amount Paid (RWF)')" />
                                <x-text-input id="amount_paid" type="number" name="amount_paid" :value="old('amount_paid')" class="mt-1 block w-full" required step="0.01" />
                                <x-input-error :messages="$errors->get('amount_paid')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="payment_date" :value="__('Payment Date')" />
                                <x-text-input id="payment_date" type="date" name="payment_date" :value="old('payment_date', date('Y-m-d'))" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="payment_method" :value="__('Payment Method')" />
                                <select id="payment_method" name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">-- Select Method --</option>
                                    <option value="momo" {{ old('payment_method') == 'momo' ? 'selected' : '' }}>Mobile Money (MoMo)</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="cash_deposit" {{ old('payment_method') == 'cash_deposit' ? 'selected' : '' }}>Cash Deposit</option>
                                </select>
                                <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                            </div>
                             <div>
                                <x-input-label for="transaction_id" :value="__('Transaction ID / Reference (Optional)')" />
                                <x-text-input id="transaction_id" type="text" name="transaction_id" :value="old('transaction_id')" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('transaction_id')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="notes" :value="__('Notes (Optional)')" />
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>
                        <p class="mt-6 text-sm text-gray-600">
                            Note: Your repayment will be marked as "Pending Confirmation" until an administrator verifies it.
                        </p>
                        <div class="mt-8 flex items-center justify-end space-x-4">
                             <a href="{{ route('member.loans.show', $loan) }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <x-primary-button>
                                {{ __('Submit Repayment Proof') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> -->