<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Make a New Contribution') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('member.contributions.store') }}">
                        @csrf

                        <!-- Contribution Type -->
                        <div>
                            <x-input-label for="type" :value="__('Contribution Type (e.g., Regular,Social)')" />
                            {{-- <x-text-input id="type" class="block mt-1 w-full" type="text" name="type" :value="old('type')" required autofocus /> --}}
                             <select id="type" name="type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
        <option value="regular" {{ (isset($contribution) && $contribution->type == 'regular') || old('type') == 'regural' ? 'selected' : '' }}>Regular</option>
        <option value="social" {{ (isset($contribution) && $contribution->type == 'social') || old('type') == 'social' ? 'selected' : '' }}>Social</option>   
    </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- Amount -->
                        <div class="mt-4">
                            <x-input-label for="amount" :value="__('Amount (RWF)')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" name="amount" :value="old('amount')" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <!-- Payment Method -->
                        <div class="mt-4">
                            <x-input-label for="payment_method" :value="__('Payment Method')" />
                            <select id="payment_method" name="payment_method" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">-- Select Payment Method --</option>
                                <option value="momo" {{ old('payment_method') == 'momo' ? 'selected' : '' }}>Mobile Money (MoMo)</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash_to_treasurer" {{ old('payment_method') == 'cash_to_treasurer' ? 'selected' : '' }}>Cash to Treasurer</option>
                                {{-- Add other methods as needed --}}
                            </select>
                            <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                            {{-- You could add dynamic instructions here based on selection --}}
                        </div>

                        <!-- Transaction ID (Optional) -->
                        <div class="mt-4">
                            <x-input-label for="transaction_id" :value="__('Transaction ID (if applicable)')" />
                            <x-text-input id="transaction_id" class="block mt-1 w-full" type="text" name="transaction_id" :value="old('transaction_id')" />
                            <x-input-error :messages="$errors->get('transaction_id')" class="mt-2" />
                        </div>

                        <!-- Payment Date (Optional) -->
                         <div class="mt-4">
                             <x-input-label for="payment_date" :value="__('Payment Date (if already paid)')" />
                             <x-text-input id="payment_date" class="block mt-1 w-full" type="date" name="payment_date" :value="old('payment_date')" />
                             <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                         </div>

                        <!-- Description (Optional) -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description / Notes (Optional)')" />
                            <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Submit Contribution') }}
                            </x-primary-button>
                        </div>
                    </form>
                    <p class="mt-4 text-sm text-gray-600">
                        <strong>Note:</strong> After submitting, your contribution will be marked as 'pending'.
                        Please ensure you complete the payment using your selected method.
                        An admin will review and approve your contribution once payment is confirmed.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>