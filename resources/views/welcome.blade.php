<x-guest-layout>
<meta name="application-name" content="Mitra Pelita">
<meta name="theme-color" content="#0d6efd">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('User Name')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text"
                            name="username" :value="old('username')"
                            required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col items-center mt-6 space-y-4">

            <x-primary-button class="w-full justify-center py-2 text-base">
                {{ __('Log in') }}
            </x-primary-button>

            <!-- <div class="flex items-center w-full">
                <div class="border-t w-full"></div>
                <span class="px-2 text-xs text-gray-400">or</span>
                <div class="border-t w-full"></div>
            </div> -->

            <!-- <a href="{{ route('register') }}" 
            class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">
                Register
            </a> -->
        </div>
    </form>
</x-guest-layout>
