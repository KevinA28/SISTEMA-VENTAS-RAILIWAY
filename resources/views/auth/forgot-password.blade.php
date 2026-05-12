<x-guest-layout>

    <div class="auth-header">
        <h2>Recuperar contraseña</h2>
        <p>Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.</p>
    </div>

    @if (session('status'))
        <div class="auth-alert auth-alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
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
                placeholder="tucorreo@ejemplo.com"
            />
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary">
            Enviar enlace de recuperación
        </button>

        <a href="{{ route('login') }}" class="btn-secondary">
            ← Volver al inicio de sesión
        </a>
    </form>

</x-guest-layout>