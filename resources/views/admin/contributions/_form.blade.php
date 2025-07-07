@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
{{-- User (Member) --}}
<div>
        <x-input-label for="user_search_select" :value="__('Member')" />
        {{-- This select will be enhanced by Tom Select --}}
        <select id="user_search_select" name="user_id_tomselect" class="mt-1 block w-full tom-select-member" placeholder="Search by name, email, or account no..." required {{ isset($contribution) && $contribution->user_id ? 'disabled' : '' }}>
            {{-- For Edit form, pre-populate with the selected user --}}
            @if(isset($selectedUserOption) && $selectedUserOption)
                <option value="{{ $selectedUserOption['id'] }}" selected="selected">{{ $selectedUserOption['text'] }}</option>
            @elseif(old('user_id')) {{-- Handle old input --}}
                 @php
                    $oldUser = \App\Models\User::find(old('user_id'));
                 @endphp
                 @if($oldUser)
                    <option value="{{ $oldUser->id }}" selected="selected">{{ $oldUser->name }} ({{ $oldUser->email }})</option>
                 @endif
            @endif
        </select>
        {{-- Hidden input to store the actual user_id for form submission --}}
        <input type="hidden" name="user_id" id="user_id_hidden" value="{{ old('user_id', $contribution->user_id ?? '') }}">
        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
        <x-input-error :messages="$errors->get('user_id_tomselect')" class="mt-2" /> {{-- If you add validation for the TomSelect field itself --}}
    </div>

    {{-- Contribution Type --}}
    <div>
        <x-input-label for="admin_contribution_type" :value="__('Contribution Type')" />
        <select id="admin_contribution_type" name="type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 admin-contribution-type" required>
            <option value="monthly_membership" {{ (isset($contribution) && $contribution->type == 'monthly_membership') || old('type') == 'monthly_membership' ? 'selected' : '' }}>Monthly Membership</option>
            <option value="social_contribution" {{ (isset($contribution) && $contribution->type == 'social_contribution') || old('type') == 'social_contribution' ? 'selected' : '' }}>Social Contribution</option>
            <option value="other" {{ (isset($contribution) && $contribution->type == 'other') || old('type') == 'other' ? 'selected' : '' }}>Other Donation</option>
        </select>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>

    {{-- Amount --}}
    <div class="md:col-span-2"> {{-- Spanning two columns for better layout with helper text --}}
        <x-input-label for="admin_contribution_amount" :value="__('Amount (RWF)')" />
        <x-text-input id="admin_contribution_amount" class="block mt-1 w-full admin-contribution-amount" type="number" step="0.01" name="amount" :value="old('amount', $contribution->amount ?? '')" required />
        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
        <p id="admin_amount_helper_text" class="text-xs text-gray-500 mt-1">Amount may auto-fill based on selected member and type. Admin can override.</p>
    </div>
<!-- Payment Method -->
<div class="mb-4">
    <x-input-label for="payment_method" :value="__('Payment Method')" />
    <select id="payment_method" name="payment_method" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        <option value="">-- Select --</option>
        <option value="momo" {{ (isset($contribution) && $contribution->payment_method == 'momo') || old('payment_method') == 'momo' ? 'selected' : '' }}>Mobile Money</option>
        <option value="bank_transfer" {{ (isset($contribution) && $contribution->payment_method == 'bank_transfer') || old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
        <option value="cash_to_treasurer" {{ (isset($contribution) && $contribution->payment_method == 'cash_to_treasurer') || old('payment_method') == 'cash_to_treasurer' ? 'selected' : '' }}>Cash to Treasurer</option>
        <option value="waived" {{ (isset($contribution) && $contribution->payment_method == 'waived') || old('payment_method') == 'waived' ? 'selected' : '' }}>Waived</option>
    </select>
    <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
</div>

<!-- Transaction ID -->
<div class="mb-4">
    <x-input-label for="transaction_id" :value="__('Transaction ID (Optional)')" />
    <x-text-input id="transaction_id" class="block mt-1 w-full" type="text" name="transaction_id" :value="old('transaction_id', $contribution->transaction_id ?? '')" />
    <x-input-error :messages="$errors->get('transaction_id')" class="mt-2" />
</div>

<!-- Payment Date -->
<div class="mb-4">
    <x-input-label for="payment_date" :value="__('Payment Date (YYYY-MM-DD)')" />
    <x-text-input id="payment_date" class="block mt-1 w-full" type="date" name="payment_date" :value="old('payment_date', isset($contribution) && $contribution->payment_date ? $contribution->payment_date->format('Y-m-d') : '')" />
    <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
</div>

<!-- Status -->
<div class="mb-4">
    <x-input-label for="status" :value="__('Status')" />
    <select id="status" name="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
        <option value="pending" {{ (isset($contribution) && $contribution->status == 'pending') || old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="approved" {{ (isset($contribution) && $contribution->status == 'approved') || old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
        <option value="rejected" {{ (isset($contribution) && $contribution->status == 'rejected') || old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
    </select>
    <x-input-error :messages="$errors->get('status')" class="mt-2" />
</div>

<!-- Description -->
<div class="mb-4">
    <x-input-label for="description" :value="__('Description / Notes (Optional)')" />
    <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $contribution->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.contributions.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
        Cancel
    </a>
    <x-primary-button>
        {{ isset($contribution) ? __('Update Contribution') : __('Create Contribution') }}
    </x-primary-button>
</div>

@push('scripts')
{{-- If you are not importing TomSelect JS globally in app.js --}}
{{-- <script src="path/to/tom-select.complete.min.js"></script> --}}

<script>
    // This might be available globally if imported in bootstrap.js
    // const TomSelect = window.TomSelect;

    // Pass the member category amounts for the currently selected user (on edit) or an empty object
    let memberCategoryAmountsDataForSelectedUser = @json($memberCategoryAmounts ?? []);
    let initialUserId = "{{ old('user_id', $contribution->user_id ?? '') }}";

    document.addEventListener('DOMContentLoaded', function () {
        const userSearchSelectEl = document.getElementById('user_search_select');
        const hiddenUserIdInput = document.getElementById('user_id_hidden');
        const typeSelect = document.getElementById('admin_contribution_type');
        const amountInput = document.getElementById('admin_contribution_amount');
        const amountHelperText = document.getElementById('admin_amount_helper_text');

        if (userSearchSelectEl && typeof TomSelect !== 'undefined') {
            new TomSelect(userSearchSelectEl, {
                valueField: 'id',
                labelField: 'text',
                searchField: ['text'],
                create: false, // Don't allow creating new users from here
                load: function(query, callback) {
                    if (!query.length && !this.settings.loadInitial) return callback(); // Prevent loading on empty unless initial
                    const url = `{{ route('admin.members.search') }}?q=${encodeURIComponent(query)}`;
                    fetch(url)
                        .then(response => response.json())
                        .then(json => {
                            callback(json.items);
                        }).catch(()=>{
                            callback();
                        });
                },
                preload: 'focus', // Load suggestions when input is focused (optional)
                // loadInitial: true, // If you want to load some initial options without typing
                render: {
                    option: function(data, escape) {
                        return `<div>${escape(data.text)}</div>`;
                    },
                    item: function(data, escape) {
                        return `<div>${escape(data.text)}</div>`;
                    }
                },
                onChange: function(value) {
                    if (hiddenUserIdInput) {
                        hiddenUserIdInput.value = value; // Update hidden input
                    }
                    initialUserId = value; // Update for subsequent category fetches
                    // When user changes, fetch their category data and then update amount
                    fetchMemberCategoryDataAndUpdateAmount(value);
                }
            });
        } else if (typeof TomSelect === 'undefined') {
            console.error('TomSelect library is not loaded.');
        }


        function fetchMemberCategoryDataAndUpdateAmount(userId) {
    if (!userId) {
        memberCategoryAmountsDataForSelectedUser = {};
        updateAmountBasedOnSelection();
        return;
    }

    // AJAX call to get specific user's category data
    fetch(`{{ url('/admin/get-member-category-amounts') }}/${userId}`) // Use url() for base path
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data) {
                memberCategoryAmountsDataForSelectedUser = { [userId]: data };
            } else {
                memberCategoryAmountsDataForSelectedUser = { [userId]: { monthly: null, social: null } }; // Handle no category data
            }
            updateAmountBasedOnSelection();
        })
        .catch(error => {
            console.error('Error fetching member category amounts:', error);
            memberCategoryAmountsDataForSelectedUser = {}; // Clear on error
            updateAmountBasedOnSelection(); // Still update UI, just without auto-fill
        });
}


        function updateAmountBasedOnSelection() {
            // ... (The amount auto-fill logic from your previous response,
            //      but now it uses memberCategoryAmountsDataForSelectedUser and initialUserId)

            if (!typeSelect || !amountInput || !amountHelperText) return;

            const selectedType = typeSelect.value;
            let newAmount = ''; // Default to empty, admin needs to fill if no auto-value
            let helperMsg = 'Admin can set or override this amount.';
            amountInput.readOnly = false;
            amountInput.classList.remove('bg-gray-100', 'cursor-not-allowed');

            // Use initialUserId which is updated by TomSelect's onChange
            if (initialUserId && memberCategoryAmountsDataForSelectedUser[initialUserId]) {
                const categoryData = memberCategoryAmountsDataForSelectedUser[initialUserId];
                if (selectedType === 'monthly_membership') {
                    if (categoryData.monthly !== null && categoryData.monthly !== undefined) {
                        newAmount = categoryData.monthly;
                        helperMsg = `Default monthly for selected member: RWF ${parseFloat(newAmount).toFixed(2)}.`;
                    } else {
                         helperMsg = 'Selected member\'s category has no defined monthly contribution.';
                    }
                } else if (selectedType === 'social_contribution') {
                    if (categoryData.social !== null && categoryData.social !== undefined) {
                        newAmount = categoryData.social;
                        helperMsg = `Default social for selected member: RWF ${parseFloat(newAmount).toFixed(2)}.`;
                    } else {
                         helperMsg = 'Selected member\'s category has no defined social contribution.';
                    }
                }
            } else if (initialUserId) {
                helperMsg = 'Selected member has no category data for auto-fill or data not loaded.';
            }


            // Only set if we got a newAmount and not 'other' type,
            // or if it's an existing record and the type and user match the record.
            const isEditMode = {{ isset($contribution) ? 'true' : 'false' }};
            const originalType = "{{ isset($contribution) ? $contribution->type : '' }}";
            const originalUserId = "{{ isset($contribution) ? $contribution->user_id : '' }}";

            if (newAmount !== '' && selectedType !== 'other') {
                 // For new records, or if user/type changed on edit
                if (!isEditMode || (isEditMode && (selectedType !== originalType || initialUserId !== originalUserId))) {
                    amountInput.value = newAmount;
                } else if (isEditMode && selectedType === originalType && initialUserId === originalUserId && !amountInput.value) {
                    // If editing, type and user are same, and amount field is empty, then prefill
                    amountInput.value = newAmount;
                }
            } else if (selectedType === 'other') {
                if (!isEditMode || (isEditMode && selectedType !== originalType)) {
                    amountInput.value = ''; // Clear for "other" if new or type changed
                }
            }
             amountHelperText.textContent = helperMsg;
        }

        if (typeSelect && amountInput) { // userSelect is handled by TomSelect init
            typeSelect.addEventListener('change', updateAmountBasedOnSelection);
            // Initial call for edit form or if old input exists
            if (initialUserId) { // If a user is selected initially (edit form or old input)
                fetchMemberCategoryDataAndUpdateAmount(initialUserId); // This will then call updateAmountBasedOnSelection
            } else {
                updateAmountBasedOnSelection(); // For types where user might not be needed (e.g. 'other')
            }
        }
    });
</script>
@endpush