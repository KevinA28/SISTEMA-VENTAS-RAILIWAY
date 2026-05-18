<x-guest-layout>
    <form method="POST" action="{{ route('register', ['token' => $token]) }}">
        @csrf

        <div class="mb-4 text-sm text-gray-600">
            Estás creando tu cuenta con el correo <strong>{{ $invitacion->email }}</strong>
            como <strong>{{ ucfirst($invitacion->rol) }}</strong>.
        </div>

        {{-- Nombre --}}
        <div>
            <x-input-label for="nombre" value="Nombre" />
            <x-text-input id="nombre" class="block mt-1 w-full" type="text"
                name="nombre" :value="old('nombre')" required autofocus />
            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
        </div>

        {{-- Apellido --}}
        <div class="mt-4">
            <x-input-label for="apellido" value="Apellido" />
            <x-text-input id="apellido" class="block mt-1 w-full" type="text"
                name="apellido" :value="old('apellido')" required />
            <x-input-error :messages="$errors->get('apellido')" class="mt-2" />
        </div>

        {{-- Contraseña --}}
        <div class="mt-4">
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirmar contraseña --}}
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar contraseña" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Crear cuenta
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>