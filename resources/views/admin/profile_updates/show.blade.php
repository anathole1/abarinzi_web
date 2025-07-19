<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Review Profile Update Request</h2></x-slot>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Request from: {{ $profileUpdate->user->name }} (Submitted: {{ $profileUpdate->created_at->diffForHumans() }})</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Current Data --}}
                    <div class="p-4 border rounded-md">
                        <h4 class="font-bold mb-3 text-gray-600">Current Profile Data</h4>
                        @php $current = $profileUpdate->memberProfile; @endphp
                        <dl class="text-sm space-y-2">
                            {{-- Display current fields --}}
                            <div><dt class="font-semibold">Name:</dt><dd>{{ $current->first_name }} {{ $current->last_name }}</dd></div>
                            <div><dt class="font-semibold">Email:</dt><dd>{{ $current->email }}</dd></div>
                            <div><dt class="font-semibold">Phone:</dt><dd>{{ $current->phone_number }}</dd></div>
                            <div><dt class="font-semibold">Category:</dt><dd>{{ $current->memberCategory->name ?? 'N/A' }}</dd></div>
                            {{-- Add more current fields as needed --}}
                        </dl>
                    </div>

                    {{-- Requested Changes --}}
                    <div class="p-4 border border-blue-300 bg-blue-50 rounded-md">
                        <h4 class="font-bold mb-3 text-blue-700">Requested Changes</h4>
                        @php $new = $profileUpdate->updated_data; @endphp
                        <dl class="text-sm space-y-2">
                            {{-- Display new data, highlighting changes --}}
                            @foreach($new as $key => $value)
                                @php
                                    $originalValue = data_get($current, Str::snake($key));
                                    // Handle special cases like category ID
                                    if ($key === 'member_category_id') {
                                        $originalValue = $current->memberCategory->name ?? 'N/A';
                                        $value = \App\Models\MemberCategory::find($value)->name ?? 'N/A';
                                    }
                                    $isChanged = strval($originalValue) !== strval($value);
                                @endphp
                                <div class="{{ $isChanged ? 'p-2 bg-yellow-100 rounded' : '' }}">
                                    <dt class="font-semibold">{{ Str::title(str_replace('_', ' ', $key)) }}:</dt>
                                    <dd>{{ $value }}</dd>
                                    @if($isChanged)
                                        <dd class="text-xs text-gray-500 line-through">Old: {{ $originalValue }}</dd>
                                    @endif
                                </div>
                            @endforeach
                        </dl>
                    </div>
                </div>

                {{-- Actions --}}
                 <div class="mt-8 pt-6 border-t flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('rejectForm').classList.toggle('hidden')" class="text-red-600 hover:underline">Reject Request</button>
                    <form action="{{ route('admin.profile-updates.approve', $profileUpdate) }}" method="POST" onsubmit="return confirm('Approve these changes and update the member\'s profile?');">
                        @csrf @method('PATCH')
                        <x-primary-button>Approve Changes</x-primary-button>
                    </form>
                </div>

                <form id="rejectForm" action="{{ route('admin.profile-updates.reject', $profileUpdate) }}" method="POST" class="hidden mt-4 bg-red-50 p-4 rounded-md border border-red-200">
                    @csrf @method('PATCH')
                    <x-input-label for="rejection_reason" :value="__('Reason for Rejection (Required)')" />
                    <textarea name="rejection_reason" id="rejection_reason" rows="3" class="mt-1 block w-full rounded-md" required></textarea>
                    <x-danger-button class="mt-2">Confirm Rejection</x-danger-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>