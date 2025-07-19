@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
    {{-- User (Member) Selector --}}
    <div class="md:col-span-1">
        <x-input-label for="user_search_select" :value="__('Member')" />
        <select id="user_search_select" class="mt-1 block w-full" placeholder="Search by name, email, or account no..." required {{ isset($contribution) && $contribution->user_id ? 'disabled' : '' }}>
            @if(isset($selectedUserOption) && $selectedUserOption)
                <option value="{{ $selectedUserOption['id'] }}" selected="selected">{{ $selectedUserOption['text'] }}</option>
            @elseif(old('user_id'))
                 @php $oldUser = \App\Models\User::find(old('user_id')); @endphp
                 @if($oldUser)
                    <option value="{{ $oldUser->id }}" selected="selected">{{ $oldUser->name }} ({{ $oldUser->email }})</option>
                 @endif
            @else
                <option value=""></option>
            @endif
        </select>
        <input type="hidden" name="user_id" id="user_id_hidden" value="{{ old('user_id', $contribution->user_id ?? '') }}">
        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
    </div>

    {{-- Contribution Type --}}
    <div class="md:col-span-1">
        <x-input-label for="admin_contribution_type" :value="__('Contribution Type')" />
        <select id="admin_contribution_type" name="type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
            <option value="monthly_membership" {{ (isset($contribution) && $contribution->type == 'monthly_membership') || old('type') == 'monthly_membership' ? 'selected' : '' }}>Monthly Membership</option>
            <option value="social_contribution" {{ (isset($contribution) && $contribution->type == 'social_contribution') || old('type') == 'social_contribution' ? 'selected' : '' }}>Social Contribution</option>
            <option value="other" {{ (isset($contribution) && $contribution->type == 'other') || old('type') == 'other' ? 'selected' : '' }}>Other Donation</option>
        </select>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>

    {{-- Amount --}}
    <div class="md:col-span-2">
        <x-input-label for="admin_contribution_amount" :value="__('Amount (RWF)')" />
        <x-text-input id="admin_contribution_amount" class="block mt-1 w-full" type="number" step="0.01" name="amount" :value="old('amount', $contribution->amount ?? '')" required />
        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
        <p id="admin_amount_helper_text" class="text-xs text-gray-500 mt-1">Amount may auto-fill based on selected member and type. Admin can override.</p>
    </div>

    {{-- Payment Method --}}
    <div class="md:col-span-1">
        <x-input-label for="payment_method" :value="__('Payment Method')" />
        <select id="payment_method" name="payment_method" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
            <option value="">-- Select --</option>
            <option value="mobile_money" {{ (isset($contribution) && $contribution->payment_method == 'mobile_money') || old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
            <option value="bank_transfer" {{ (isset($contribution) && $contribution->payment_method == 'bank_transfer') || old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
            <option value="cash_to_treasurer" {{ (isset($contribution) && $contribution->payment_method == 'cash_to_treasurer') || old('payment_method') == 'cash_to_treasurer' ? 'selected' : '' }}>Cash to Treasurer</option>
            <option value="waived" {{ (isset($contribution) && $contribution->payment_method == 'waived') || old('payment_method') == 'waived' ? 'selected' : '' }}>Waived</option>
        </select>
        <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
    </div>

    {{-- Transaction ID --}}
    <div class="md:col-span-1">
        <x-input-label for="transaction_id" :value="__('Transaction ID (Optional)')" />
        <x-text-input id="transaction_id" class="block mt-1 w-full" type="text" name="transaction_id" :value="old('transaction_id', $contribution->transaction_id ?? '')" />
        <x-input-error :messages="$errors->get('transaction_id')" class="mt-2" />
    </div>

    {{-- Payment Date --}}
    <div class="md:col-span-1">
        <x-input-label for="payment_date" :value="__('Payment Date')" />
        <x-text-input id="payment_date" class="block mt-1 w-full" type="date" name="payment_date" :value="old('payment_date', isset($contribution) && $contribution->payment_date ? $contribution->payment_date->format('Y-m-d') : date('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
    </div>

    {{-- Status --}}
    <div class="md:col-span-1">
        <x-input-label for="status" :value="__('Status')" />
        <select id="status" name="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
            <option value="pending" {{ (isset($contribution) && $contribution->status == 'pending') || old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ (isset($contribution) && $contribution->status == 'approved') || old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ (isset($contribution) && $contribution->status == 'rejected') || old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>

    {{-- Description --}}
    <div class="md:col-span-2">
        <x-input-label for="description" :value="__('Description / Admin Notes (Optional)')" />
        <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">{{ old('description', $contribution->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>
</div>

{{-- Action Buttons --}}
<div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200">
    <a href="{{ route('admin.contributions.index') }}"
       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
        Cancel
    </a>
    <x-primary-button>
        {{ isset($contribution) ? __('Update Contribution') : __('Create Contribution') }}
    </x-primary-button>
</div>


@push('scripts')
<script>
    let preloadedCategoryAmounts = @json($memberCategoryAmounts ?? []);
    let currentUserId = "{{ old('user_id', $contribution->user_id ?? '') }}";

    document.addEventListener('DOMContentLoaded', function () {
        const userSearchSelectEl = document.getElementById('user_search_select');
        const hiddenUserIdInput = document.getElementById('user_id_hidden');
        const typeSelect = document.getElementById('admin_contribution_type');
        const amountInput = document.getElementById('admin_contribution_amount');
        const amountHelperText = document.getElementById('admin_amount_helper_text');
        const categoryDataCache = { ...preloadedCategoryAmounts };

        if (!userSearchSelectEl) {
            console.error('Tom Select target element #user_search_select not found.');
            return;
        }

        if (typeof TomSelect === 'undefined') {
            console.error('TomSelect library is not loaded. Please check your app.js/bootstrap.js imports and run "npm run dev".');
            // Graceful fallback to a simple input if TomSelect is missing
            userSearchSelectEl.style.display = 'none'; // Hide the select
            let fallbackInput = document.createElement('input');
            fallbackInput.type = 'text';
            fallbackInput.className = 'block mt-1 w-full border-red-500';
            fallbackInput.placeholder = 'Error: Search component failed to load.';
            userSearchSelectEl.parentNode.insertBefore(fallbackInput, userSearchSelectEl.nextSibling);
            return;
        }

        const tomSelectInstance = new TomSelect(userSearchSelectEl, {
            valueField: 'id',
            labelField: 'text',
            searchField: ['text'],
            create: false,
            load: function(query, callback) {
                if (!query.length) return callback();
                const url = `{{ route('admin.members.search') }}?q=${encodeURIComponent(query)}`;
                fetch(url)
                    .then(response => response.json())
                    .then(json => {
                        callback(json.items);
                    }).catch(()=>{
                        callback();
                    });
            },
            onChange: function(value) {
                hiddenUserIdInput.value = value;
                currentUserId = value;
                fetchMemberCategoryDataAndUpdateAmount(value);
            }
        });

        function fetchMemberCategoryDataAndUpdateAmount(userId) {
            if (!userId) {
                updateAmountBasedOnSelection();
                return;
            }
            if (categoryDataCache[userId]) {
                updateAmountBasedOnSelection();
                return;
            }
            fetch(`{{ url('/admin/get-member-category-amounts') }}/${userId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    categoryDataCache[userId] = data || { monthly: null, social: null };
                    updateAmountBasedOnSelection();
                })
                .catch(error => {
                    console.error('Error fetching member category amounts:', error);
                    categoryDataCache[userId] = { monthly: null, social: null };
                    updateAmountBasedOnSelection();
                });
        }

        function updateAmountBasedOnSelection() {
            if (!typeSelect || !amountInput || !amountHelperText) return;

            const selectedType = typeSelect.value;
            let newAmount = '';
            let helperMsg = 'Admin can set or override this amount.';
            amountInput.readOnly = false;
            amountInput.classList.remove('bg-gray-100');

            if (currentUserId && categoryDataCache[currentUserId]) {
                const categoryData = categoryDataCache[currentUserId];
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
            }

            const isEditMode = {{ isset($contribution) ? 'true' : 'false' }};
            const isInitialLoad = !amountInput.value || (isEditMode && amountInput.value === "{{ $contribution->amount ?? '' }}");

            if (newAmount !== '' && isInitialLoad) {
                 amountInput.value = newAmount;
            }
            
            // On type change, if not edit mode, always update
            if (newAmount !== '' && !isEditMode) {
                amountInput.value = newAmount;
            }

            amountHelperText.textContent = helperMsg;
        }

        if (typeSelect && amountInput) {
            typeSelect.addEventListener('change', updateAmountBasedOnSelection);
            if (currentUserId) {
                fetchMemberCategoryDataAndUpdateAmount(currentUserId);
            }
        }
    });
</script>
@endpush