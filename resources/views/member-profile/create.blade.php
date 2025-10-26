<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->memberProfile && Auth::user()->memberProfile->status === 'rejected' ? __('Resubmit Your Membership Profile') : __('Complete Your Membership Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8"> {{-- Increased max-width --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    @if(Auth::user()->memberProfile && Auth::user()->memberProfile->status === 'rejected')
                        <div class="mb-6 p-4 bg-yellow-100 text-yellow-700 rounded-md">
                            <p class="font-semibold">Your previous application was rejected.</p>
                            <p>Please review your information, make any necessary corrections, and resubmit. If you have questions, contact support.</p>
                            {{-- You could add a field here to show rejection reason if you store it --}}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('member-profile.store') }}" enctype="multipart/form-data"> {{-- Added enctype --}}
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- First Name --}}
                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" />
                                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', Auth::user()->memberProfile->first_name ?? $prefilledFirstName)" required autofocus />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                            </div>

                            {{-- Last Name --}}
                            <div>
                                <x-input-label for="last_name" :value="__('Last Name')" />
                                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', Auth::user()->memberProfile->last_name ?? $prefilledLastName)" required />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>

                            {{-- Email (Profile Specific) --}}
                            <div>
                                <x-input-label for="email" :value="__('Contact Email (for Profile)')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', Auth::user()->memberProfile->email ?? $prefilledEmail)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                <p class="text-xs text-gray-500 mt-1">This email will be associated with your public profile if applicable. Your login email remains {{ Auth::user()->email }}.</p>
                            </div>

                            {{-- Phone Number --}}
                            <div>
                                <x-input-label for="phone_number" :value="__('Phone Number')" />
                                <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number', Auth::user()->memberProfile->phone_number ?? '')" required />
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                            </div>

                            {{-- National ID --}}
                            <div>
                                <x-input-label for="national_id" :value="__('National ID')" />
                                <x-text-input id="national_id" class="block mt-1 w-full" type="text" name="national_id" :value="old('national_id', Auth::user()->memberProfile->national_id ?? '')" required />
                                <x-input-error :messages="$errors->get('national_id')" class="mt-2" />
                            </div>

                            {{-- Year Left EFOTEC --}}
                            <div>
                                <x-input-label for="year_left_efotec" :value="__('Year Left EFOTEC (e.g., 2010 S6 PCM)')" />
                                <x-text-input id="year_left_efotec" class="block mt-1 w-full" type="text" name="year_left_efotec" :value="old('year_left_efotec', Auth::user()->memberProfile->year_left_efotec ?? '')" />
                                <x-input-error :messages="$errors->get('year_left_efotec')" class="mt-2" />
                            </div>

                            {{-- Current Location --}}
                            <div class="md:col-span-2">
                                <x-input-label for="current_location" :value="__('Current Location (City, Country)')" />
                                <x-text-input id="current_location" class="block mt-1 w-full" type="text" name="current_location" :value="old('current_location', Auth::user()->memberProfile->current_location ?? '')" />
                                <x-input-error :messages="$errors->get('current_location')" class="mt-2" />
                            </div>
                            {{-- Occupation --}}
                            <div class="md:col-span-2">
                                <x-input-label for="occupation" :value="__('Current Occupation / Sector')" />
                                <select id="occupation" name="occupation" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">-- Select your sector --</option>
                                    <option value="private_sector" {{ old('occupation', Auth::user()->memberProfile->occupation ?? '') == 'private_sector' ? 'selected' : '' }}>Private Sector</option>
                                    <option value="public_sector" {{ old('occupation', Auth::user()->memberProfile->occupation ?? '') == 'public_sector' ? 'selected' : '' }}>Public Sector</option>
                                    <option value="self_employed" {{ old('occupation', Auth::user()->memberProfile->occupation ?? '') == 'self_employed' ? 'selected' : '' }}>Self-Employed</option>
                                    <option value="other" {{ old('occupation', Auth::user()->memberProfile->occupation ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('occupation')" class="mt-2" />
                            </div>

                            {{-- Date Joined Association --}}
                            <div>
                                <x-input-label for="dateJoined" :value="__('Date Joined Association')" />
                                <x-text-input id="dateJoined" class="block mt-1 w-full" type="date" name="dateJoined" :value="old('dateJoined', Auth::user()->memberProfile && Auth::user()->memberProfile->dateJoined ? Auth::user()->memberProfile->dateJoined->format('Y-m-d') : date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('dateJoined')" class="mt-2" />
                            </div>

                            {{-- Membership Category --}}
                            <div>
                                <x-input-label for="member_category_id" :value="__('Membership Category')" />
                                <select id="member_category_id" name="member_category_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($membershipCategories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('member_category_id', Auth::user()->memberProfile->member_category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }} - {{ number_format($category->monthly_contribution, 0) }} RWF/month
                                            @if($category->social_monthly_contribution > 0)
                                             (+ {{ number_format($category->social_monthly_contribution, 0) }} RWF Social)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('member_category_id')" class="mt-2" />
                            </div>

                            {{-- Profile Photo --}}
                            <div class="md:col-span-2">
                                <x-input-label for="photo" :value="__('Profile Photo (Optional)')" />
                                <input id="photo" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="photo" accept="image/jpeg,image/png,image/jpg,image/gif,webp">
                                <p class="mt-1 text-xs text-gray-500">Max 2MB. Allowed: JPG, PNG, GIF, WEBP.</p>
                                <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                               @if(Auth::user()->memberProfile && Auth::user()->memberProfile->photoUrl)
                                    <div class="mt-2">
                                        <img src="{{ Auth::user()->memberProfile->full_photo_url }}" alt="Current Photo" class="h-20 w-20 rounded-full object-cover">
                                        <p class="text-xs text-gray-500 mt-1">Current photo. Uploading a new one will replace it.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t">
                            <x-primary-button>
                                {{ __('Submit Profile') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>