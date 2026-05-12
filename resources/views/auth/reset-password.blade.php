<x-guest-layout>

    <div class="auth-header">
        <h2>Nueva contraseña</h2>
        <p>Crea una contraseña segura para tu cuenta.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group">
            <label class="form-label" for="email">Correo electrónico</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
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
            <label class="form-label" for="password">Nueva contraseña</label>
            <input
                id="password"
                type="password"
                name="password"
                class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                required
                autocomplete="new-password"
                placeholder="Mínimo 8 caracteres"
            />
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                class="form-input {{ $errors->has('password_confirmation') ? 'is-error' : '' }}"
                required
                autocomplete="new-password"
                placeholder="Repite tu contraseña"
            />
            @error('password_confirmation')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary">
            Restablecer contraseña
        </button>

        <a href="{{ route('login') }}" class="btn-secondary">
            ← Volver al inicio de sesión
        </a>
    </form>

</x-guest-layout>