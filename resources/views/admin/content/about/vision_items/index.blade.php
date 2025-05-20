 <x-app-layout>
     <x-slot name="header">
         <div class="flex justify-between items-center">
             <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Vision Points</h2>
             <a href="{{ route('admin.content.vision-items.create') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">Add Vision Point</a>
         </div>
     </x-slot>
     <div class="py-12">
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                 @include('partials.flash-messages')
                 <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                         <tr>
                             <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                             <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                             <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                         </tr>
                     </thead>
                     <tbody class="bg-white divide-y divide-gray-200">
                         @forelse($items as $item)
                         <tr>
                             <td class="px-4 py-2">{{ $item->order }}</td>
                             <td class="px-4 py-2">{{ $item->title }}</td>
                             <td class="px-4 py-2">
                                 <a href="{{ route('admin.content.vision-items.edit', $item) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                 <form action="{{ route('admin.content.vision-items.destroy', $item) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Are you sure you want to delete this vision point?');">
                                     @csrf
                                     @method('DELETE')
                                     <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                 </form>
                             </td>
                         </tr>
                         @empty
                         <tr><td colspan="3" class="text-center py-4 text-gray-500">No vision points added yet.</td></tr>
                         @endforelse
                     </tbody>
                 </table>
                 <div class="mt-6">
                     <a href="{{ route('admin.content.about.edit') }}" class="text-blue-600 hover:text-blue-800">← Back to Main About Page Edit</a>
                 </div>
             </div>
         </div>
     </div>
 </x-app-layout>