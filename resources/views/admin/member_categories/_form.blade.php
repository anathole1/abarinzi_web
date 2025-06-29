@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="name" :value="__('Category Name (e.g., Bronze)')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $memberCategory->name ?? '')" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="monthly_contribution" :value="__('Monthly Contribution (RWF)')" />
        <x-text-input id="monthly_contribution" name="monthly_contribution" type="number" step="0.01" class="mt-1 block w-full" :value="old('monthly_contribution', $memberCategory->monthly_contribution ?? '')" required />
        <x-input-error :messages="$errors->get('monthly_contribution')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="annual_contribution" :value="__('Annual Contribution (RWF)')" />
        <x-text-input id="annual_contribution" name="annual_contribution" type="number" step="0.01" class="mt-1 block w-full" :value="old('annual_contribution', $memberCategory->annual_contribution ?? '')" required />
        <x-input-error :messages="$errors->get('annual_contribution')" class="mt-2" />
        <p class="text-xs text-gray-500 mt-1">Typically Monthly x 12. Can be set independently.</p>
    </div>
     <div>
        <x-input-label for="social_monthly_contribution" :value="__('Social Monthly Contribution (RWF, Optional)')" />
        <x-text-input id="social_monthly_contribution" name="social_monthly_contribution" type="number" step="0.01" class="mt-1 block w-full" :value="old('social_monthly_contribution', $memberCategory->social_monthly_contribution ?? '')" />
        <x-input-error :messages="$errors->get('social_monthly_contribution')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="percentage_of_loan_allowed" :value="__('Percentage of Loan Allowed (%)')" />
        <x-text-input id="percentage_of_loan_allowed" name="percentage_of_loan_allowed" type="number" step="0.01" class="mt-1 block w-full" :value="old('percentage_of_loan_allowed', $memberCategory->percentage_of_loan_allowed ?? '')" required placeholder="e.g., 50 for 50%" />
        <x-input-error :messages="$errors->get('percentage_of_loan_allowed')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="monthly_interest_rate_loan" :value="__('Monthly Loan Interest Rate (%)')" />
        <x-text-input id="monthly_interest_rate_loan" name="monthly_interest_rate_loan" type="number" step="0.01" class="mt-1 block w-full" :value="old('monthly_interest_rate_loan', $memberCategory->monthly_interest_rate_loan ?? '')" required placeholder="e.g., 1.5 for 1.5%" />
        <x-input-error :messages="$errors->get('monthly_interest_rate_loan')" class="mt-2" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="description" :value="__('Description (Optional)')" />
        <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $memberCategory->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>
     <div class="md:col-span-2">
        <label for="is_active" class="inline-flex items-center">
            <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active', (isset($memberCategory) && $memberCategory->is_active) || !isset($memberCategory) ) ? 'checked' : '' }}>
            <span class="ml-2 text-sm text-gray-600">{{ __('Active (can be selected by members)') }}</span>
        </label>
        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
    </div>
</div>
<div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200"> {{-- Added border-top --}}
    <a href="{{ route('admin.member-categories.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
        Cancel
    </a>
    <x-primary-button>
        {{ isset($memberCategory) ? __('Update Category') : __('Create Category') }}
    </x-primary-button>
</div>