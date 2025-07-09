<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Contributions Report') }}</h2>
        {{-- Add Export Buttons here later --}}
    </x-slot>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- Add filter form here (by member, type, status, date range) --}}
                <h3 class="text-lg font-semibold mb-4">Contributions List</h3>
                 @if($contributions->isEmpty())
                    <p>No contributions found.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Member</th>
                                <th class="px-4 py-2 text-left">Type</th>
                                <th class="px-4 py-2 text-left">Amount (RWF)</th>
                                <th class="px-4 py-2 text-left">Payment Method</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Payment Date</th>
                                <th class="px-4 py-2 text-left">Approved By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contributions as $item)
                            <tr>
                                <td class="px-4 py-2">{{ $item->user->name }}</td>
                                <td class="px-4 py-2">{{ Str::title(str_replace('_', ' ', $item->type)) }}</td>
                                <td class="px-4 py-2">{{ number_format($item->amount, 2) }}</td>
                                <td class="px-4 py-2">{{ Str::title(str_replace('_', ' ', $item->payment_method)) }}</td>
                                <td class="px-4 py-2">{{ ucfirst($item->status) }}</td>
                                <td class="px-4 py-2">{{ $item->payment_date ? $item->payment_date->format('M d, Y') : 'N/A' }}</td>
                                <td class="px-4 py-2">{{ $item->approver->name ?? 'N/A' }}</td>
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