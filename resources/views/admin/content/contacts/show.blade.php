<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Contact Message Details') }}
            </h2>
            <div>
                @can('delete contact messages')
                <form action="{{ route('admin.content.contacts.destroy', $contactMessage) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this message permanently?');">
                    @csrf @method('DELETE')
                    <x-danger-button type="submit">Delete Message</x-danger-button>
                </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
             @include('partials.flash-messages')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 space-y-4">

                     <div class="flex justify-between items-center border-b pb-2">
                         <h3 class="text-lg font-semibold text-gray-800">Message from {{ $contactMessage->name }}</h3>
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $contactMessage->is_read ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800' }}">
                             {{ $contactMessage->is_read ? 'Read' : 'Unread' }}
                         </span>
                     </div>

                     <div>
                         <span class="font-semibold text-gray-700">Email:</span>
                         <a href="mailto:{{ $contactMessage->email }}" class="text-blue-600 hover:underline ml-2">{{ $contactMessage->email }}</a>
                     </div>

                    <div>
                         <span class="font-semibold text-gray-700">Subject:</span>
                         <span class="ml-2">{{ $contactMessage->subject }}</span>
                     </div>

                     <div>
                         <span class="font-semibold text-gray-700">Received:</span>
                         <span class="ml-2 text-gray-600">{{ $contactMessage->created_at->format('M d, Y \a\t H:i') }} ({{ $contactMessage->created_at->diffForHumans() }})</span>
                     </div>

                     <div class="border-t pt-4 mt-4">
                         <h4 class="font-semibold text-gray-700 mb-2">Message:</h4>
                         <p class="text-gray-800 whitespace-pre-wrap">{{ $contactMessage->message }}</p>
                     </div>

                     <div class="mt-6 flex justify-start space-x-4">
                         <a href="{{ route('admin.content.contacts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                             ← Back to Messages
                         </a>
                         <form action="{{ route('admin.content.contacts.toggleRead', $contactMessage) }}" method="POST">
                             @csrf @method('PATCH')
                             <x-secondary-button type="submit">
                                 {{ $contactMessage->is_read ? 'Mark as Unread' : 'Mark as Read' }}
                             </x-secondary-button>
                         </form>
                     </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>