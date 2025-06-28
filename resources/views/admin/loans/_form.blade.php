@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="user_id" :value="__('Member Applicant')" />
        <select id="user_id" name="user_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required {{ isset($loan) && $loan->user_id ? 'disabled' : '' }}>
            <option value="">-- Select Member --</option>
            @foreach ($members as $member)
                <option value="{{ $member->id }}"
                    {{ (isset($loan) && $loan->user_id == $member->id) || old('user_id') == $member->id ? 'selected' : '' }}>
                    {{ $member->name }} ({{ $member->email }})
                </option>
            @endforeach
        </select>
        @if(isset($loan) && $loan->user_id) <input type="hidden" name="user_id" value="{{ $loan->user_id }}"> @endif
        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="application_date" :value="__('Application Date')" />
        <x-text-input id="application_date" class="block mt-1 w-full" type="date" name="application_date" :value="old('application_date', isset($loan) && $loan->application_date ? $loan->application_date->format('Y-m-d') : date('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('application_date')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="amount_requested" :value="__('Amount Requested (RWF)')" />
        <x-text-input id="amount_requested" class="block mt-1 w-full" type="number" step="0.01" name="amount_requested" :value="old('amount_requested', $loan->amount_requested ?? '')" required />
        <x-input-error :messages="$errors->get('amount_requested')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="purpose" :value="__('Purpose of Loan')" />
        <textarea id="purpose" name="purpose" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('purpose', $loan->purpose ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
    </div>
</div>

<hr class="my-6">
<h3 class="text-lg font-medium text-gray-700 mb-4">Office Use / Approval Details</h3>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
     <div>
        <x-input-label for="status" :value="__('Loan Status')" />
        <select id="status" name="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
            <option value="pending" {{ (isset($loan) && $loan->status == 'pending') || old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ (isset($loan) && $loan->status == 'approved') || old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ (isset($loan) && $loan->status == 'rejected') || old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            <option value="active" {{ (isset($loan) && $loan->status == 'active') || old('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="repaid" {{ (isset($loan) && $loan->status == 'repaid') || old('status') == 'repaid' ? 'selected' : '' }}>Repaid</option>
            <option value="defaulted" {{ (isset($loan) && $loan->status == 'defaulted') || old('status') == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="amount_approved" :value="__('Amount Approved (RWF, if different)')" />
        <x-text-input id="amount_approved" class="block mt-1 w-full" type="number" step="0.01" name="amount_approved" :value="old('amount_approved', $loan->amount_approved ?? '')" />
        <x-input-error :messages="$errors->get('amount_approved')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="interest_rate" :value="__('Interest Rate (Annual %)')" />
        <x-text-input id="interest_rate" class="block mt-1 w-full" type="number" step="0.01" name="interest_rate" :value="old('interest_rate', $loan->interest_rate ?? '')" placeholder="e.g., 5.5 for 5.5%" />
        <x-input-error :messages="$errors->get('interest_rate')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="term_months" :value="__('Loan Term (Months)')" />
        <x-text-input id="term_months" class="block mt-1 w-full" type="number" name="term_months" :value="old('term_months', $loan->term_months ?? '')" placeholder="e.g., 12" />
        <x-input-error :messages="$errors->get('term_months')" class="mt-2" />
    </div>
    @if(isset($loan) && $loan->total_repayment_amount !== null)
    <div class="md:col-span-2 mt-4 mb-2 p-3 bg-gray-50 rounded-md border">
        <p class="text-sm font-medium text-gray-700">
            Estimated Total Repayment (based on current saved values):
            <span class="font-bold text-gray-900">RWF {{ number_format($loan->total_repayment_amount, 2) }}</span>
        </p>
        <p class="text-xs text-gray-500">This value will update after saving changes to amount, rate, or term.</p>
    </div>
    @endif

     <div>
        <x-input-label for="approval_date" :value="__('Approval Date')" />
        <x-text-input id="approval_date" class="block mt-1 w-full" type="date" name="approval_date" :value="old('approval_date', isset($loan) && $loan->approval_date ? $loan->approval_date->format('Y-m-d') : '')" />
        <x-input-error :messages="$errors->get('approval_date')" class="mt-2" />
    </div>
     <div>
        <x-input-label for="disbursement_date" :value="__('Disbursement Date')" />
        <x-text-input id="disbursement_date" class="block mt-1 w-full" type="date" name="disbursement_date" :value="old('disbursement_date', isset($loan) && $loan->disbursement_date ? $loan->disbursement_date->format('Y-m-d') : '')" />
        <x-input-error :messages="$errors->get('disbursement_date')" class="mt-2" />
    </div>
     <div>
        <x-input-label for="first_payment_due_date" :value="__('First Payment Due Date')" />
        <x-text-input id="first_payment_due_date" class="block mt-1 w-full" type="date" name="first_payment_due_date" :value="old('first_payment_due_date', isset($loan) && $loan->first_payment_due_date ? $loan->first_payment_due_date->format('Y-m-d') : '')" />
        <x-input-error :messages="$errors->get('first_payment_due_date')" class="mt-2" />
    </div>
     <div>
        <x-input-label for="final_due_date" :value="__('Final Due Date')" />
        <x-text-input id="final_due_date" class="block mt-1 w-full" type="date" name="final_due_date" :value="old('final_due_date', isset($loan) && $loan->final_due_date ? $loan->final_due_date->format('Y-m-d') : '')" />
        <x-input-error :messages="$errors->get('final_due_date')" class="mt-2" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="admin_notes" :value="__('Admin Notes')" />
        <textarea id="admin_notes" name="admin_notes" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('admin_notes', $loan->admin_notes ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('admin_notes')" class="mt-2" />
    </div>
</div>

<div class="flex items-center justify-end mt-8 pt-6 border-t">
    <a href="{{ route('admin.loans.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
    <x-primary-button>
        {{ isset($loan) ? __('Update Loan Record') : __('Save Loan Record') }}
    </x-primary-button>
</div>