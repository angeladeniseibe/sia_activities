<x-guest-layout>
    <style>
        div.min-h-screen > div:first-child:not(.w-full) {
            display: none !important;
        }
    </style>

    <div class="flex flex-col items-center justify-center mb-6">
        <a href="/">
            <img 
                src="{{ asset('images/logo.png') }}" 
                alt="Save Energy" 
                class="w-30 h-30 object-contain"
            >
        </a>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full focus:border-[#39b54a] focus:ring-[#39b54a]" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full focus:border-[#39b54a] focus:ring-[#39b54a]" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full focus:border-[#39b54a] focus:ring-[#39b54a]"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full focus:border-[#39b54a] focus:ring-[#39b54a]"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-[#39b54a] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#39b54a]" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 bg-[#1b1b18] hover:bg-[#39b54a] transition-colors">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>