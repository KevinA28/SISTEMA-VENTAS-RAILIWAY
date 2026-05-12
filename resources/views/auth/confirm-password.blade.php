<x-guest-layout>

    <div class="auth-header">
        <h2>Confirmar contraseña</h2>
        <p>Por seguridad, confirma tu contraseña antes de continuar.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

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

        <button type="submit" class="btn-primary">
            Confirmar y continuar
        </button>
    </form>

</x-guest-layout>