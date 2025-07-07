<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Loan Applications') }}
            </h2>
            {{-- Add eligibility check here if needed before showing button --}}
            <a href="{{ route('member.loans.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium">
                Apply for New Loan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @include('partials.flash-messages')

                    @if($loans->isEmpty())
                        <p class="text-gray-600">You have not applied for any loans yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applied On</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount Req.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purpose</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($loans as $loan)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loan->application_date->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RWF {{ number_format($loan->amount_requested, 0) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ Str::limit($loan->purpose, 30) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $loan->status == 'approved' || $loan->status == 'active' || $loan->status == 'repaid' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $loan->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $loan->status == 'rejected' || $loan->status == 'defaulted' ? 'bg-red-100 text-red-800' : '' }}
                                                    ">
                                                    {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('member.loans.show', $loan) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                                {{-- Optionally add cancel for pending loans --}}
                                                {{-- @if($loan->status === 'pending')
                                                    <form action="{{ route('member.loans.cancel', $loan) }}" method="POST" class="inline ml-2"> @csrf @method('PATCH') <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Cancel</button></form>
                                                @endif --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $loans->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>