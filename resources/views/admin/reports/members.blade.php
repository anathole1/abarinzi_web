<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-2 sm:mb-0">
                {{ __('Members Report') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.reports.members.pdf', request()->query()) }}" target="_blank"
                   class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1.5 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export PDF
                </a>
                <a href="{{ route('admin.reports.members.excel', request()->query()) }}"
                   class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                     <svg class="w-4 h-4 mr-1.5 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M4 10h16M4 14h16M4 18h16"></path></svg>
                    Export Excel
                </a>
            </div>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @include('partials.flash-messages') {{-- For any messages after export or filtering --}}

                {{-- Filter Form (Example - you can enhance this) --}}
                <form method="GET" action="{{ route('admin.reports.members') }}" class="mb-6 p-4 bg-gray-50 rounded-md">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
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
                            <label for="category_filter" class="block text-sm font-medium text-gray-700">Filter by Category</label>
                            <select name="category_filter" id="category_filter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Categories</option>
                                @foreach(\App\Models\MemberCategory::orderBy('name')->get() as $category)
                                    <option value="{{ $category->id }}" {{ request('category_filter') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Filter</button>
                            @if(request()->hasAny(['status_filter', 'category_filter']))
                                <a href="{{ route('admin.reports.members') }}" class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Clear Filters</a>
                            @endif
                        </div>
                    </div>
                </form>

                <h3 class="text-lg font-semibold mb-4 text-gray-700">Members List ({{ $members->count() }} results)</h3>
                @if($members->isEmpty())
                    <p>No members found matching criteria.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">Account #</th>
                                <th class="px-4 py-2 text-left font-semibold">Full Name</th>
                                <th class="px-4 py-2 text-left font-semibold">Profile Email</th>
                                <th class="px-4 py-2 text-left font-semibold">User Email</th>
                                <th class="px-4 py-2 text-left font-semibold">Phone</th>
                                <th class="px-4 py-2 text-left font-semibold">National ID</th>
                                <th class="px-4 py-2 text-left font-semibold">Category</th>
                                <th class="px-4 py-2 text-left font-semibold">Status</th>
                                <th class="px-4 py-2 text-left font-semibold">Joined Assoc.</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($members as $profile)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $profile->accountNo }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $profile->first_name }} {{ $profile->last_name }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $profile->email }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $profile->user->email }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $profile->phone_number }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $profile->national_id }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $profile->memberCategory->name ?? 'N/A' }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                     <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $profile->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $profile->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $profile->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                        ">
                                        {{ ucfirst($profile->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $profile->dateJoined ? $profile->dateJoined->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- If using pagination for HTML view: <div class="mt-4">{{ $membersPaginated->links() }}</div> --}}
                @endif
            </div>
        </div>
    </div>
</x-app-layout>