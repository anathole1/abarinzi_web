<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Manage Offices / Leadership') }}</h2>
            <a href="{{ route('admin.offices.create') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">Add New Office</a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @include('partials.flash-messages')
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Office Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Currently Assigned</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($offices as $office)
                            <tr>
                                <td class="px-4 py-3">{{ $office->display_order }}</td>
                                <td class="px-4 py-3 font-medium">{{ $office->name }}</td>
                                <td class="px-4 py-3">{{ $office->code }}</td>
                                <td class="px-4 py-3">
                                    @if($office->user)
                                        <div class="flex items-center">
                                            <img class="h-8 w-8 rounded-full object-cover mr-2" src="{{ $office->user->memberProfile->full_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ $office->user->name }}">
                                            <span>{{ $office->user->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500 italic">-- Vacant --</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.offices.edit', $office) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                    <form action="{{ route('admin.offices.destroy', $office) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Are you sure you want to delete this office?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-gray-500">No offices found. Please run the seeder or add one.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>