 <x-app-layout>
     <x-slot name="header">
         <div class="flex justify-between items-center">
             <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                 {{ __('Manage Hero Slides') }}
             </h2>
             <a href="{{ route('admin.content.hero-slides.create') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
                 {{ __('Add New Slide') }}
             </a>
         </div>
     </x-slot>

     <div class="py-12">
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 bg-white border-b border-gray-200">
                     @include('partials.flash-messages')

                     <p class="text-sm text-gray-600 mb-4">Slides are displayed in the order specified (lower numbers first). You can edit slides to change their order.</p>

                     <div class="overflow-x-auto">
                         <table class="min-w-full divide-y divide-gray-200">
                             <thead class="bg-gray-50">
                                 <tr>
                                     <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                                     <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                                     <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                     <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                     <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                 </tr>
                             </thead>
                             <tbody class="bg-white divide-y divide-gray-200">
                                 @forelse ($slides as $slide)
                                     <tr>
                                         <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">{{ $slide->order }}</td>
                                         <td class="px-4 py-4 whitespace-nowrap text-sm">
                                             <img src="{{ $slide->image_url }}" alt="Slide Image" class="h-12 w-20 object-cover rounded">
                                         </td>
                                         <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $slide->title }}</td>
                                         <td class="px-4 py-4 whitespace-nowrap text-sm">
                                             @if($slide->is_active)
                                                 <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                             @else
                                                 <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                             @endif
                                         </td>
                                         <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                             <a href="{{ route('admin.content.hero-slides.edit', $slide) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                             <form action="{{ route('admin.content.hero-slides.destroy', $slide) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this slide?');">
                                                 @csrf @method('DELETE')
                                                 <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                             </form>
                                         </td>
                                     </tr>
                                 @empty
                                     <tr>
                                         <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hero slides found. Please add one!</td>
                                     </tr>
                                 @endforelse
                             </tbody>
                         </table>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </x-app-layout>