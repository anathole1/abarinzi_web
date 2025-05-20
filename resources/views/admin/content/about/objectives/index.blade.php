<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Core Objectives</h2>
            <a href="{{ route('admin.content.objectives.create') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">Add Objective</a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @include('partials.flash-messages')
                <table class="min-w-full divide-y divide-gray-200">
                    {{-- Table headers: Order, Title, Actions --}}
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="px-4 py-2">{{ $item->order }}</td>
                            <td class="px-4 py-2">{{ $item->title }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.content.objectives.edit', $item) }}" class="text-indigo-600">Edit</a>
                                <form action="{{ route('admin.content.objectives.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')"> @csrf @method('DELETE') <button type="submit" class="text-red-600">Delete</button></form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4">No objectives added yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4"><a href="{{ route('admin.content.about.edit') }}" class="text-blue-600">← Back to Main About Page Edit</a></div>
            </div>
        </div>
    </div>
</x-app-layout>