<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Manage Loan Applications') }}</h2>
            <a href="{{ route('admin.loans.create') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">Record New Loan</a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8"> {{-- Full width for table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @include('partials.flash-messages')

                    <form method="GET" action="{{ route('admin.loans.index') }}" class="mb-6 p-4 bg-gray-50 rounded-md">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label for="user_filter" class="block text-sm font-medium text-gray-700">Filter by Member</label>
                                <select name="user_filter" id="user_filter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">All Members</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_filter') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status_filter" class="block text-sm font-medium text-gray-700">Filter by Status</label>
                                <select name="status_filter" id="status_filter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status_filter') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status_filter') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="repaid" {{ request('status_filter') == 'repaid' ? 'selected' : '' }}>Repaid</option>
                                    <option value="defaulted" {{ request('status_filter') == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
                                </select>
                            </div>
                            <div class="flex space-x-2">
                                <button type="submit" class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Filter</button>
                                @if(request('status_filter') || request('user_filter'))
                                <a href="{{ route('admin.loans.index') }}" class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Clear</a>
                                @endif
                            </div>
                        </div>
                    </form>

                    @if($loans->isEmpty())
                        <p class="text-gray-600">No loan records found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount Req.</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Est. Total Repay</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">App. Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($loans as $loan)
                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loan->user->name }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">RWF {{ number_format($loan->amount_requested, 2) }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                                @if($loan->total_repayment_amount !== null)
                                                    RWF {{ number_format($loan->total_repayment_amount, 2) }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $loan->status == 'approved' || $loan->status == 'active' || $loan->status == 'repaid' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $loan->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $loan->status == 'rejected' || $loan->status == 'defaulted' ? 'bg-red-100 text-red-800' : '' }}
                                                    ">
                                                    {{ ucfirst($loan->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loan->application_date->format('M d, Y') }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.loans.show', $loan) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                                <a href="{{ route('admin.loans.edit', $loan) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                @if(!in_array($loan->status, ['active', 'repaid']))
                                                <form action="{{ route('admin.loans.destroy', $loan) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this loan record?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                                @endif
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