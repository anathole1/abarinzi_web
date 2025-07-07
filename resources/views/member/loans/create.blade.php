<x-app-layout-public> {{-- Or your member area layout --}}
    <x-slot name="title">Apply for a Loan</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Loan Application') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    @include('partials.flash-messages')

                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                        <h3 class="text-lg font-semibold text-blue-700">Your Loan Eligibility:</h3>
                        <p class="text-sm text-gray-700">Category: <span class="font-medium">{{ $memberCategory->name }}</span></p>
                        <p class="text-sm text-gray-700">Total Contributions: <span class="font-medium">RWF {{ number_format($memberEligibleSavingsForLoan, 0) }}</span></p> {{-- New Line --}}
                        <p class="text-sm text-gray-700">Maximum Loan Percentage: <span class="font-medium">{{ $memberCategory->percentage_of_loan_allowed }}%</span></p> {{-- New Line --}}
                        <p class="text-sm text-gray-700">Your current loan limit: <span class="font-medium">RWF {{ number_format($loanLimit, 0) }}</span></p>
                        <p class="text-sm text-gray-700">Interest Rate: <span class="font-medium">{{ $defaultInterestRate }}% per month</span></p>
                    </div>

                    <form method="POST" action="{{ route('member.loans.store') }}">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="amount_requested" :value="__('Amount Requested (RWF)')" />
                                <x-text-input id="amount_requested" type="number" name="amount_requested" :value="old('amount_requested')" class="mt-1 block w-full" required :max="$loanLimit" step="1000" />
                                <x-input-error :messages="$errors->get('amount_requested')" class="mt-2" />
                                <p class="text-xs text-gray-500 mt-1">Maximum you can request: RWF {{ number_format($loanLimit, 0) }}</p>
                            </div>
                            <div>
                                <x-input-label for="term_months" :value="__('Loan Term (Months)')" />
                                <select id="term_months" name="term_months" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">-- Select Term --</option>
                                    @for ($i = 3; $i <= 24; $i+=3) {{-- Example: 3 to 24 months, steps of 3 --}}
                                        <option value="{{ $i }}" {{ old('term_months') == $i ? 'selected' : '' }}>{{ $i }} Months</option>
                                    @endfor
                                    {{-- Add other specific term options as needed --}}
                                </select>
                                <x-input-error :messages="$errors->get('term_months')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="purpose" :value="__('Purpose of Loan')" />
                                <textarea id="purpose" name="purpose" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('purpose') }}</textarea>
                                <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                            </div>
                            {{-- <div class="mt-4">
                                <label for="agree_to_terms" class="inline-flex items-center">
                                    <input id="agree_to_terms" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="agree_to_terms" required>
                                    <span class="ml-2 text-sm text-gray-600">{{ __('I agree to the loan terms and conditions.') }}</span>
                                </label>
                                <x-input-error :messages="$errors->get('agree_to_terms')" class="mt-2" />
                            </div> --}}
                        </div>
                        <div class="mt-8 flex justify-end">
                            <x-primary-button>
                                {{ __('Submit Loan Application') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout-public>