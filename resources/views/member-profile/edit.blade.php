<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Your Membership Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    @include('partials.flash-messages')

                    @if($pendingUpdate)
                        <div class="mb-6 p-4 bg-blue-100 text-blue-700 rounded-md">
                            <p class="font-semibold">You have a pending profile update request.</p>
                            <p>It was submitted on {{ $pendingUpdate->created_at->format('M d, Y') }} and is awaiting administrator review. You cannot submit another update until this one is processed.</p>
                        </div>
                    @else
                        <p class="mb-4 text-sm text-gray-600">
                            Any changes you submit here will be sent to an administrator for review and approval before they are applied to your profile.
                        </p>
                        <form method="POST" action="{{ route('member-profile.update') }}" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Use the same form fields as your create.blade.php --}}
                                {{-- Example: First Name, pre-filled with current profile data --}}
                                <div>
                                    <x-input-label for="first_name" :value="__('First Name')" />
                                    <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $memberProfile->first_name)" required />
                                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="last_name" :value="__('Last Name')" />
                                    <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $memberProfile->last_name)" required />
                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="phone_number" :value="__('Phone Number')" />
                                    <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number', $memberProfile->phone_number)" required />
                                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="current_location" :value="__('Current Location')" />
                                    <x-text-input id="current_location" class="block mt-1 w-full" type="text" name="current_location" :value="old('current_location', $memberProfile->current_location)" required />
                                    <x-input-error :messages="$errors->get('current_location')" class="mt-2" />
                                </div>
                                {{-- ... last_name, email, phone_number, national_id ... --}}
                                {{-- ... year_left_efotec, current_location ... --}}
                                {{-- Occupation --}}
                                <div>
                                    <x-input-label for="occupation" :value="__('Current Occupation / Sector')" />
                                    <select id="occupation" name="occupation" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                        <option value="">-- Select --</option>
                                        <option value="private_sector" {{ old('occupation', $memberProfile->occupation) == 'private_sector' ? 'selected' : '' }}>Private Sector</option>
                                        <option value="public_sector" {{ old('occupation', $memberProfile->occupation) == 'public_sector' ? 'selected' : '' }}>Public Sector</option>
                                        <option value="self_employed" {{ old('occupation', $memberProfile->occupation) == 'self_employed' ? 'selected' : '' }}>Self-Employed</option>
                                        <option value="other" {{ old('occupation', $memberProfile->occupation) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('occupation')" class="mt-2" />
                                </div>
                                {{-- ... dateJoined (probably shouldn't be editable by member) ... --}}
                                {{-- Membership Category --}}
                                <div>
                                    <x-input-label for="member_category_id" :value="__('Membership Category')" />
                                    <select id="member_category_id" name="member_category_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach ($membershipCategories as $category)
                                            <option value="{{ $category->id }}" {{ old('member_category_id', $memberProfile->member_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('member_category_id')" class="mt-2" />
                                </div>

                                {{-- Profile Photo --}}
                                <div class="md:col-span-2">
                                    <x-input-label for="photo" :value="__('Update Profile Photo (Optional)')" />
                                    <input id="photo" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" type="file" name="photo">
                                    @if($memberProfile->photoUrl)
                                        <div class="mt-2">
                                            <img src="{{ $memberProfile->full_photo_url }}" alt="Current Photo" class="h-20 w-20 rounded-full object-cover">
                                            <p class="text-xs text-gray-500 mt-1">Current photo. Uploading a new one will request a replacement.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-8 pt-6 border-t">
                                <x-primary-button>
                                    {{ __('Submit Update Request') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>