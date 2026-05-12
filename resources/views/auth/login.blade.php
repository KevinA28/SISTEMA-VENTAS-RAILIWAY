<x-guest-layout>

    <div class="auth-header">
        <h2>Bienvenido de vuelta</h2>
        <p>Ingresa tus credenciales para acceder al sistema</p>
    </div>

    <x-auth-session-status class="auth-alert auth-alert-success" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">Correo electrónico</label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                required
                autofocus
                autocomplete="username"
                placeholder="tucorreo@ejemplo.com"
            />

            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Contraseña</label>

            <input
                id="password"
                type="password"
                name="password"
                class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                required
                autocomplete="current-password"
                placeholder="••••••••"
            />

            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div style="text-align: right; margin-bottom: 1.5rem;">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="form-link">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <button type="submit" class="btn-primary">
            Iniciar sesión
        </button>

    </form>

</x-guest-layout>