<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Loans Report') }}</h2>
        {{-- Add Export Buttons here later --}}
    </x-slot>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- Add filter form here (by member, status, date range) --}}
                <h3 class="text-lg font-semibold mb-4">Loans List</h3>
                @if($loans->isEmpty())
                    <p>No loans found.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Member</th>
                                <th class="px-4 py-2 text-left">Amount Appr.</th>
                                <th class="px-4 py-2 text-left">Term</th>
                                <th class="px-4 py-2 text-left">Rate (Monthly)</th>
                                <th class="px-4 py-2 text-left">Total Repay</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Application Date</th>
                                <th class="px-4 py-2 text-left">Approved By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loans as $loan)
                            <tr>
                                <td class="px-4 py-2">{{ $loan->user->name }}</td>
                                <td class="px-4 py-2">{{ $loan->amount_approved ? number_format($loan->amount_approved, 2) : number_format($loan->amount_requested, 2) }}</td>
                                <td class="px-4 py-2">{{ $loan->term_months }} months</td>
                                <td class="px-4 py-2">{{ $loan->interest_rate }}%</td>
                                <td class="px-4 py-2">{{ $loan->display_total_repayment ? number_format($loan->display_total_repayment, 2) : 'N/A' }}</td>
                                <td class="px-4 py-2">{{ ucfirst($loan->status) }}</td>
                                <td class="px-4 py-2">{{ $loan->application_date->format('M d, Y') }}</td>
                                <td class="px-4 py-2">{{ $loan->approver->name ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>