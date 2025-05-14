 <x-app-layout>
     <x-slot name="header">
         <div class="flex justify-between items-center">
             <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                 {{ __('Manage Contributions') }}
             </h2>
             <a href="{{ route('admin.contributions.create') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                 {{ __('Add New Contribution') }}
             </a>
         </div>
     </x-slot>

     <div class="py-12">
         <div class="max-w-full mx-auto sm:px-6 lg:px-8"> {{-- Changed to max-w-full for wider table --}}
             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 bg-white border-b border-gray-200">
                     @include('partials.flash-messages') {{-- Create this for success/error messages --}}

                     <!-- Filters -->
                     <form method="GET" action="{{ route('admin.contributions.index') }}" class="mb-6 p-4 bg-gray-50 rounded-md">
                         <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                             <div>
                                 <label for="status_filter" class="block text-sm font-medium text-gray-700">Filter by Status</label>
                                 <select name="status_filter" id="status_filter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                     <option value="">All Statuses</option>
                                     <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                                     <option value="approved" {{ request('status_filter') == 'approved' ? 'selected' : '' }}>Approved</option>
                                     <option value="rejected" {{ request('status_filter') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                 </select>
                             </div>
                             <div>
                                 <label for="user_filter" class="block text-sm font-medium text-gray-700">Filter by Member</label>
                                 <select name="user_filter" id="user_filter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                     <option value="">All Members</option>
                                     @foreach($users as $user)
                                         <option value="{{ $user->id }}" {{ request('user_filter') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                     @endforeach
                                 </select>
                             </div>
                             <div class="self-end">
                                 <button type="submit" class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                     Filter
                                 </button>
                                 @if(request('status_filter') || request('user_filter'))
                                 <a href="{{ route('admin.contributions.index') }}" class="ml-2 w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                     Clear
                                 </a>
                                 @endif
                             </div>
                         </div>
                     </form>


                     @if($contributions->isEmpty())
                         <p>No contributions found.</p>
                     @else
                         <div class="overflow-x-auto">
                             <table class="min-w-full divide-y divide-gray-200">
                                 <thead class="bg-gray-50">
                                     <tr>
                                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                     </tr>
                                 </thead>
                                 <tbody class="bg-white divide-y divide-gray-200">
                                     @foreach ($contributions as $contribution)
                                         <tr>
                                             <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $contribution->user->name }}</td>
                                             <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $contribution->type }}</td>
                                             <td class="px-6 py-4 whitespace-nowrap text-sm">RWF {{ number_format($contribution->amount, 2) }}</td>
                                             <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $contribution->created_at->format('M d, Y') }}</td>
                                             <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                 <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                     @if($contribution->status == 'approved') bg-green-100 text-green-800 @endif
                                                     @if($contribution->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                                     @if($contribution->status == 'rejected') bg-red-100 text-red-800 @endif">
                                                     {{ ucfirst($contribution->status) }}
                                                 </span>
                                             </td>
                                             <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                 @if($contribution->status == 'pending')
                                                     <form action="{{ route('admin.contributions.approve', $contribution) }}" method="POST" class="inline-block">
                                                         @csrf @method('PATCH')
                                                         <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                                     </form>
                                                     <span class="mx-1">|</span>
                                                     <form action="{{ route('admin.contributions.reject', $contribution) }}" method="POST" class="inline-block">
                                                         @csrf @method('PATCH')
                                                         <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Reject this contribution?');">Reject</button>
                                                     </form>
                                                     <span class="mx-1">|</span>
                                                 @endif
                                                 <a href="{{ route('admin.contributions.show', $contribution) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                                 <span class="mx-1">|</span>
                                                 <a href="{{ route('admin.contributions.edit', $contribution) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                 <span class="mx-1">|</span>
                                                 <form action="{{ route('admin.contributions.destroy', $contribution) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this contribution? This cannot be undone.');">
                                                     @csrf @method('DELETE')
                                                     <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                 </form>
                                             </td>
                                         </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                         <div class="mt-4">
                             {{ $contributions->appends(request()->query())->links() }} {{-- Preserve filter query params on pagination --}}
                         </div>
                     @endif
                 </div>
             </div>
         </div>
     </div>
</x-app-layout>