@csrf
<!-- User (Member) -->
<div class="mb-4">
    <x-input-label for="user_id" :value="__('Member')" />
    <select id="user_id" name="user_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
        <option value="">-- Select Member --</option>
        @foreach ($members as $member)
            <option value="{{ $member->id }}" {{ (isset($contribution) && $contribution->user_id == $member->id) || old('user_id') == $member->id ? 'selected' : '' }}>
                {{ $member->name }} ({{ $member->email }})
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
</div>

<!-- Contribution Type -->
<div class="mb-4">
    <x-input-label for="type" :value="__('Contribution Type')" />
    {{-- <x-text-input id="type" class="block mt-1 w-full" type="text" name="type" :value="old('type', $contribution->type ?? '')" required /> --}}
    <select id="type" name="type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
        <option value="regular" {{ (isset($contribution) && $contribution->type == 'regular') || old('type') == 'regural' ? 'selected' : '' }}>Regular</option>
        <option value="social" {{ (isset($contribution) && $contribution->type == 'social') || old('type') == 'social' ? 'selected' : '' }}>Social</option>   
    </select>
    <x-input-error :messages="$errors->get('type')" class="mt-2" />
</div>

<!-- Amount -->
<div class="mb-4">
    <x-input-label for="amount" :value="__('Amount (RWF)')" />
    <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" name="amount" :value="old('amount', $contribution->amount ?? '')" required />
    <x-input-error :messages="$errors->get('amount')" class="mt-2" />
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