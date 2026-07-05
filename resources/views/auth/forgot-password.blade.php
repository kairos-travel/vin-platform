<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Восстановление доступа') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="login" :value="__('Телефон или электронная почта')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Отправить') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
