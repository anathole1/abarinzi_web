
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Contributions') }}
            </h2>
            @can('create', App\Models\Contribution::class)
            <a href="{{ route('member.contributions.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                {{ __('Make New Contribution') }}
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Summary Section -->
            <div class="mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Approved Regular -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-sky-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" ><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Approved Regular
                                </dt>
                                <dd class="text-2xl font-semibold text-gray-900">
                                    RWF {{ number_format($totalApprovedRegular, 2) }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Approved Social -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-rose-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m8.198 0a24.716 24.716 0 0 0-7.734 0m7.734 0a24.733 24.733 0 0 1 3.741 0M6 18.719L6 7.25a6 6 0 0 1 6-6s6 2.686 6 6v11.47Z" /></svg>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Approved Social
                                </dt>
                                <dd class="text-2xl font-semibold text-gray-900">
                                    RWF {{ number_format($totalApprovedSocial, 2) }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                @if($totalApprovedOther > 0) {{-- Optionally show 'Other' --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-gray-400 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" /></svg>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Approved Other
                                </dt>
                                <dd class="text-2xl font-semibold text-gray-900">
                                    RWF {{ number_format($totalApprovedOther, 2) }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                                <!-- Pending Contributions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Pending
                                </dt>
                                <dd class="text-2xl font-semibold text-gray-900">
                                    {{ $pendingCount }}
                                </dd>
                            </div>
                        </div>
                        {{-- You can add a link here to filter the table below if desired --}}
                        {{-- <a href="{{ route('member.contributions.index', ['status_filter' => 'pending']) }}" class="text-xs text-indigo-600 hover:text-indigo-900">View Pending</a> --}}
                    </div>
                </div>
                <!-- Rejected Contributions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                               <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Rejected
                                </dt>
                                <dd class="text-2xl font-semibold text-gray-900">
                                    {{ $rejectedCount }}
                                </dd>
                            </div>
                        </div>
                        {{-- <a href="{{ route('member.contributions.index', ['status_filter' => 'rejected']) }}" class="text-xs text-indigo-600 hover:text-indigo-900">View Rejected</a> --}}
                    </div>
                </div>
            </div>
            <!-- End Summary Section -->


            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Optional: Add filter links if you want the table to react to summary clicks --}}
                    <div class="mb-4">
                        <a href="{{ route('member.contributions.index') }}" class="px-3 py-1 text-sm rounded-md {{ !request('status_filter') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">All</a>
                        <a href="{{ route('member.contributions.index', ['status_filter' => 'pending']) }}" class="px-3 py-1 text-sm rounded-md {{ request('status_filter') == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'text-gray-600 hover:bg-gray-100' }}">Pending ({{$pendingCount}})</a>
                        <a href="{{ route('member.contributions.index', ['status_filter' => 'approved']) }}" class="px-3 py-1 text-sm rounded-md {{ request('status_filter') == 'approved' ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-gray-100' }}">Approved ({{$approvedCount}})</a>
                        <a href="{{ route('member.contributions.index', ['status_filter' => 'rejected']) }}" class="px-3 py-1 text-sm rounded-md {{ request('status_filter') == 'rejected' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-100' }}">Rejected ({{$rejectedCount}})</a>
                    </div>

                    @if($contributions->isEmpty() && !request('status_filter'))
                        <p>You have not made any contributions yet.</p>
                    @elseif($contributions->isEmpty() && request('status_filter'))
                         <p>No contributions found with the status '{{ request('status_filter') }}'.</p>
                    @else
                        <div class="overflow-x-auto"> {{-- Added for responsiveness of table --}}
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount (RWF)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Submitted</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($contributions as $contribution)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $contribution->type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($contribution->amount, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $contribution->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($contribution->status == 'approved') bg-green-100 text-green-800 @endif
                                                    @if($contribution->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                                    @if($contribution->status == 'rejected') bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($contribution->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @can('view', $contribution)
                                                <a href="{{ route('member.contributions.show', $contribution) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $contributions->appends(request()->query())->links() }} {{-- Keep filters on pagination --}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
