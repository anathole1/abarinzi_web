<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Complete Your Membership Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('member-profile.store') }}">
                        @csrf

                        <!-- First Name -->
                        <div>
                            <x-input-label for="first_name" :value="__('First Name')" />
                            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>

                        <!-- Last Name -->
                        <div class="mt-4">
                            <x-input-label for="last_name" :value="__('Last Name')" />
                            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>

                        <!-- Phone Number -->
                        <div class="mt-4">
                            <x-input-label for="phone_number" :value="__('Phone Number')" />
                            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" required />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>

                        <!-- Email (Read-only, pre-filled) -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full bg-gray-100" type="email" name="email" :value="auth()->user()->email" readonly />
                        </div>

                        <!-- National ID -->
                        <div class="mt-4">
                            <x-input-label for="national_id" :value="__('National ID')" />
                            <x-text-input id="national_id" class="block mt-1 w-full" type="text" name="national_id" :value="old('national_id')" required />
                            <x-input-error :messages="$errors->get('national_id')" class="mt-2" />
                        </div>

                        <!-- Year Left EFOTEC -->
                        <div class="mt-4">
                            <x-input-label for="year_left_efotec" :value="__('Year Left EFOTEC (e.g., 2010 S6 PCM)')" />
                            <x-text-input id="year_left_efotec" class="block mt-1 w-full" type="text" name="year_left_efotec" :value="old('year_left_efotec')" />
                            <x-input-error :messages="$errors->get('year_left_efotec')" class="mt-2" />
                        </div>

                        <!-- Current Location -->
                        <div class="mt-4">
                            <x-input-label for="current_location" :value="__('Current Location (City, Country)')" />
                            <x-text-input id="current_location" class="block mt-1 w-full" type="text" name="current_location" :value="old('current_location')" />
                            <x-input-error :messages="$errors->get('current_location')" class="mt-2" />
                        </div>

                        <!-- Membership Category -->
                        <div class="mt-4">
                            <x-input-label for="membership_category" :value="__('Membership Category')" />
                            <select id="membership_category" name="membership_category" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">-- Select Category --</option>
                                @foreach ($membershipCategories as $category => $amount)
                                    <option value="{{ $category }}" {{ old('membership_category') == $category ? 'selected' : '' }}>
                                        {{ ucfirst($category) }} - {{ $amount }} RWF
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('membership_category')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
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