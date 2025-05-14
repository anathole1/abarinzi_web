<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contact Form Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @include('partials.flash-messages')

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">From</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Received</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($messages as $message)
                                    <tr class="{{ $message->is_read ? '' : 'bg-yellow-50 font-semibold' }}">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm">{{ $message->name }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:{{ $message->email }}" class="text-blue-600 hover:underline">{{ $message->email }}</a>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm">{{ Str::limit($message->subject, 40) }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $message->created_at->diffForHumans() }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                                            @if($message->is_read)
                                                <span class="px-2 inline-flex text-xs leading-5 rounded-full bg-gray-100 text-gray-800">Read</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Unread</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.content.contacts.show', $message) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>

                                             <form action="{{ route('admin.content.contacts.toggleRead', $message) }}" method="POST" class="inline-block mr-3">
                                                 @csrf @method('PATCH')
                                                 <button type="submit" class="text-gray-500 hover:text-gray-800 text-xs">
                                                     {{ $message->is_read ? 'Mark Unread' : 'Mark Read' }}
                                                 </button>
                                             </form>

                                            @can('delete contact messages')
                                            <form action="{{ route('admin.content.contacts.destroy', $message) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this message permanently?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No contact messages found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                         {{ $messages->links() }}
                     </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>