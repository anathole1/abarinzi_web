<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($notifications->isEmpty())
                        <p class="text-gray-600">You have no notifications at the moment.</p>
                    @else
                        <ul class="space-y-4">
                            @foreach($notifications as $notification)
                                <li class="p-4 rounded-md {{ $notification->read_at ? 'bg-white' : 'bg-blue-50 border border-blue-200' }}">
                                    <div class="flex items-start space-x-3">
                                        {{-- Icon Placeholder --}}
                                        <div class="flex-shrink-0">
                                            @if(!$notification->read_at)
                                                <span class="inline-block h-2 w-2 rounded-full bg-blue-500 mt-1.5" title="Unread"></span>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-800">
                                                {{-- The notification's `data` attribute contains the array from toArray() --}}
                                                {{ $notification->data['message'] ?? 'You have a new notification.' }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                            @if(isset($notification->data['action_url']))
                                                <a href="{{ $notification->data['action_url'] }}" class="mt-2 inline-block text-sm text-indigo-600 hover:text-indigo-800 font-semibold">
                                                    View Details →
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>