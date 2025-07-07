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
                    @include('partials.flash-messages')
                    <form method="POST" action="{{ route('member.contributions.store') }}" id="contributionForm">
                        @csrf

                        <!-- Contribution Type -->
                        <div>
                            <x-input-label for="type" :value="__('Contribution Type')" />
                            <select id="type" name="type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">-- Select Type --</option>
                                <option value="monthly_membership" {{ old('type') == 'monthly_membership' ? 'selected' : '' }}>Monthly Membership Contribution</option>
                                <option value="social_contribution" {{ old('type') == 'social_contribution' ? 'selected' : '' }}>Social Contribution</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other Donation</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- Amount -->
                        <div class="mt-4">
                            <x-input-label for="amount" :value="__('Amount (RWF)')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" name="amount" :value="old('amount')" /> {{-- Removed required, controller handles it --}}
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            <p id="amount_helper_text" class="text-xs text-gray-500 mt-1"></p>
                        </div>

                        {{-- ... (Payment Method, Transaction ID, Payment Date, Description - same as before) ... --}}
                        <div class="mt-4">
                            <x-input-label for="payment_method" :value="__('Payment Method')" />
                            <select id="payment_method" name="payment_method" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">-- Select --</option>
                                <option value="momo" {{ old('payment_method') == 'momo' ? 'selected' : '' }}>Mobile Money</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash_to_treasurer" {{ old('payment_method') == 'cash_to_treasurer' ? 'selected' : '' }}>Cash to Treasurer</option>
                            </select>
                            <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                           <x-input-label for="transaction_id" :value="__('Transaction ID (if applicable)')" />
                           <x-text-input id="transaction_id" class="block mt-1 w-full" type="text" name="transaction_id" :value="old('transaction_id')" />
                           <x-input-error :messages="$errors->get('transaction_id')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="payment_date" :value="__('Payment Date (if already paid)')" />
                            <x-text-input id="payment_date" class="block mt-1 w-full" type="date" name="payment_date" :value="old('payment_date')" />
                            <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                        </div>
                       <div class="mt-4">
                           <x-input-label for="description" :value="__('Description / Notes (Optional)')" />
                           <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">{{ old('description') }}</textarea>
                           <x-input-error :messages="$errors->get('description')" class="mt-2" />
                       </div>


                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Submit Contribution') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded and parsed'); // Check if this runs

    const typeSelect = document.getElementById('type');
    const amountInput = document.getElementById('amount');
    const amountHelperText = document.getElementById('amount_helper_text');

    console.log('typeSelect:', typeSelect);       // Check if element is found
    console.log('amountInput:', amountInput);     // Check if element is found
    console.log('amountHelperText:', amountHelperText); // Check if element is found

    const defaultMonthlyAmount = {{ $defaultMonthlyAmount ?? 'null' }};
    const defaultSocialAmount = {{ $defaultSocialAmount ?? 'null' }};
    console.log('defaultMonthlyAmount from Blade:', defaultMonthlyAmount);
    console.log('defaultSocialAmount from Blade:', defaultSocialAmount);


    function handleTypeChange() {
        console.log('handleTypeChange called'); // Check if function is called
        if (!typeSelect || !amountInput || !amountHelperText) { // Guard clause
            console.error('One or more required elements are missing in handleTypeChange.');
            return;
        }

        const selectedType = typeSelect.value;
        console.log('Selected type:', selectedType);
        amountHelperText.textContent = '';

        // ... rest of your if/else if logic ...
        // Add console.log inside each block too
        if (selectedType === 'monthly_membership') {
            console.log('monthly_membership selected');
            if (defaultMonthlyAmount !== null) {
                console.log('Setting monthly amount:', defaultMonthlyAmount);
                // ...
            } else {
                console.log('defaultMonthlyAmount is null');
                // ...
            }
        } // ... and so on for other conditions
    }

    if (typeSelect && amountInput && amountHelperText) {
        console.log('Attaching event listener to typeSelect');
        typeSelect.addEventListener('change', handleTypeChange);
        handleTypeChange(); // Initial call
    } else {
        console.error('Crucial elements for contribution form JS not found. Check IDs.');
        if (!typeSelect) console.error('Element with ID "type" not found.');
        if (!amountInput) console.error('Element with ID "amount" not found.');
        if (!amountHelperText) console.error('Element with ID "amount_helper_text" not found.');
    }
});
        // document.addEventListener('DOMContentLoaded', function () {
        //     console.log('DOM fully loaded and parsed'); // Check if this runs
        //     const typeSelect = document.getElementById('type');
        //     const amountInput = document.getElementById('amount');
        //     const amountHelperText = document.getElementById('amount_helper_text');
        //     console.log('typeSelect:', typeSelect);       // Check if element is found
        //     console.log('amountInput:', amountInput);     // Check if element is found
        //     console.log('amountHelperText:', amountHelperText); // Check if element is found

        //     // Values passed from controller
        //     const defaultMonthlyAmount = {{ $defaultMonthlyAmount ?? 'null' }};
        //     const defaultSocialAmount = {{ $defaultSocialAmount ?? 'null' }};

        //     function handleTypeChange() {
        //         console.log('handleTypeChange called'); // Check if function is called
        //         if (!typeSelect || !amountInput || !amountHelperText) { // Guard clause
        //             console.error('One or more required elements are missing in handleTypeChange.');
        //             return;
        //         const selectedType = typeSelect.value;
        //         console.log('Selected type:', selectedType);
        //         amountHelperText.textContent = ''; // Clear helper text

        //         if (selectedType === 'monthly_membership') {
        //             console.log('monthly_membership selected');
        //             if (defaultMonthlyAmount !== null) {
        //                 console.log('Setting monthly amount:', defaultMonthlyAmount);
        //                 amountInput.value = defaultMonthlyAmount;
        //                 amountInput.readOnly = true;
        //                 amountInput.classList.add('bg-gray-100', 'cursor-not-allowed');
        //                 amountHelperText.textContent = 'Amount auto-filled based on your membership category.';
        //             } else {
        //                 console.log('defaultMonthlyAmount is null');
        //                 amountInput.readOnly = false;
        //                 amountInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        //                 amountHelperText.textContent = 'Monthly amount not set for your category. Please contact admin or enter manually if allowed.';
        //             }
        //         } else if (selectedType === 'social_contribution') {
        //             if (defaultSocialAmount !== null) {
        //                 amountInput.value = defaultSocialAmount;
        //                 amountInput.readOnly = true;
        //                 amountInput.classList.add('bg-gray-100', 'cursor-not-allowed');
        //                 amountHelperText.textContent = 'Social contribution amount auto-filled for your category.';
        //             } else {
        //                 amountInput.readOnly = false;
        //                 amountInput.value = ''; // Clear it for user input
        //                 amountInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        //                 amountHelperText.textContent = 'Please enter the amount for your social contribution.';
        //             }
        //         } else if (selectedType === 'other') {
        //             amountInput.readOnly = false;
        //             amountInput.value = ''; // Clear it for user input
        //             amountInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        //             amountHelperText.textContent = 'Please enter the amount for your donation.';
        //         } else { // Default or if "-- Select Type --" is chosen
        //             amountInput.readOnly = false;
        //             amountInput.value = ''; // Clear amount if no specific type or type is empty
        //             amountInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        //         }
        //     }

        //     if (typeSelect && amountInput && amountHelperText) { // Ensure all elements are found
        //         typeSelect.addEventListener('change', handleTypeChange);
        //         // Initial call to set state based on old input or default
        //         handleTypeChange();
        //     } else {
        //         console.error('Slideshow elements not found. Check IDs: type, amount, amount_helper_text');
        //         if (!typeSelect) console.error('typeSelect is null');
        //         if (!amountInput) console.error('amountInput is null');
        //         if (!amountHelperText) console.error('amountHelperText is null');
        //     }
        // });
    </script>
    @endpush
</x-app-layout>