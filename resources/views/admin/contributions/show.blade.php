<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contribution Details') }}: {{ $contribution->type }} by {{ $contribution->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Contribution Info</h3>
                            <dl class="mt-2 space-y-2">
                                <div class="flex justify-between"><dt class="font-medium">Member:</dt><dd>{{ $contribution->user->name }} ({{ $contribution->user->email }})</dd></div>
                                <div class="flex justify-between"><dt class="font-medium">Type:</dt><dd>{{ $contribution->type }}</dd></div>
                                <div class="flex justify-between"><dt class="font-medium">Amount:</dt><dd>RWF {{ number_format($contribution->amount, 2) }}</dd></div>
                                <div class="flex justify-between"><dt class="font-medium">Submitted:</dt><dd>{{ $contribution->created_at->format('M d, Y H:i') }}</dd></div>
                                <div class="flex justify-between"><dt class="font-medium">Last Updated:</dt><dd>{{ $contribution->updated_at->format('M d, Y H:i') }}</dd></div>
                                <div class="flex justify-between"><dt class="font-medium">Status:</dt><dd>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($contribution->status == 'approved') bg-green-100 text-green-800 @endif
                                        @if($contribution->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                        @if($contribution->status == 'rejected') bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($contribution->status) }}
                                    </span>
                                </dd></div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Payment & Approval</h3>
                            <dl class="mt-2 space-y-2">
                                <div class="flex justify-between"><dt class="font-medium">Payment Method:</dt><dd>{{ $contribution->payment_method ? ucfirst(str_replace('_', ' ', $contribution->payment_method)) : 'N/A' }}</dd></div>
                                <div class="flex justify-between"><dt class="font-medium">Transaction ID:</dt><dd>{{ $contribution->transaction_id ?: 'N/A' }}</dd></div>
                                <div class="flex justify-between"><dt class="font-medium">Payment Date:</dt><dd>{{ $contribution->payment_date ? $contribution->payment_date->format('M d, Y') : 'N/A' }}</dd></div>
                                @if($contribution->approver)
                                <div class="flex justify-between"><dt class="font-medium">{{ ucfirst($contribution->status) }} By:</dt><dd>{{ $contribution->approver->name }}</dd></div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    @if($contribution->description)
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900">Description/Notes</h3>
                        <p class="mt-1 text-sm text-gray-600 whitespace-pre-wrap">{{ $contribution->description }}</p>
                    </div>
                    @endif

                    <div class="mt-8 flex justify-between items-center">
                        <a href="{{ route('admin.contributions.index') }}" class="text-indigo-600 hover:text-indigo-900">
                            ← Back to All Contributions
                        </a>
                        <div>
                            @if($contribution->status == 'pending')
                                <form action="{{ route('admin.contributions.approve', $contribution) }}" method="POST" class="inline-block mr-2">
                                    @csrf @method('PATCH')
                                    <x-secondary-button type="submit" class="bg-green-500 hover:bg-green-600 text-white">Approve</x-secondary-button>
                                </form>
                                <form action="{{ route('admin.contributions.reject', $contribution) }}" method="POST" class="inline-block">
                                    @csrf @method('PATCH')
                                    <x-danger-button type="submit" onclick="return confirm('Reject this contribution?');">Reject</x-danger-button>
                                </form>
                            @endif
                            <a href="{{ route('admin.contributions.edit', $contribution) }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>