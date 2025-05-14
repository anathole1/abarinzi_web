<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contribution Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Contribution Information</h3>
                            <p class="mt-1 text-sm text-gray-600"><strong>Type:</strong> {{ $contribution->type }}</p>
                            <p class="mt-1 text-sm text-gray-600"><strong>Amount:</strong> RWF {{ number_format($contribution->amount, 2) }}</p>
                            <p class="mt-1 text-sm text-gray-600"><strong>Submitted:</strong> {{ $contribution->created_at->format('M d, Y H:i') }}</p>
                            <p class="mt-1 text-sm text-gray-600"><strong>Status:</strong>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($contribution->status == 'approved') bg-green-100 text-green-800 @endif
                                    @if($contribution->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                    @if($contribution->status == 'rejected') bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($contribution->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Payment Details</h3>
                            <p class="mt-1 text-sm text-gray-600"><strong>Payment Method:</strong> {{ $contribution->payment_method ? ucfirst(str_replace('_', ' ', $contribution->payment_method)) : 'N/A' }}</p>
                            <p class="mt-1 text-sm text-gray-600"><strong>Transaction ID:</strong> {{ $contribution->transaction_id ?: 'N/A' }}</p>
                            <p class="mt-1 text-sm text-gray-600"><strong>Payment Date:</strong> {{ $contribution->payment_date ? $contribution->payment_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>

                    @if($contribution->description)
                    <div class="mt-4">
                        <h3 class="text-lg font-medium text-gray-900">Description/Notes</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $contribution->description }}</p>
                    </div>
                    @endif

                    @if($contribution->status == 'approved' && $contribution->approver)
                    <div class="mt-4 border-t pt-4">
                        <p class="text-sm text-gray-500">Approved by: {{ $contribution->approver->name }} on {{ $contribution->updated_at->format('M d, Y') }}</p>
                    </div>
                    @elseif($contribution->status == 'rejected' && $contribution->approver)
                    <div class="mt-4 border-t pt-4">
                        <p class="text-sm text-gray-500">Rejected by: {{ $contribution->approver->name }} on {{ $contribution->updated_at->format('M d, Y') }}</p>
                    </div>
                    @endif


                    <div class="mt-6">
                        <a href="{{ route('member.contributions.index') }}" class="text-indigo-600 hover:text-indigo-900">
                            ← Back to My Contributions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>