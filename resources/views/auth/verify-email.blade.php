<x-guest-layout>

    <div class="auth-header">
        <h2>Verifica tu correo</h2>
        <p>Hemos enviado un enlace de verificación a tu dirección de correo electrónico. Revisa tu bandeja de entrada.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-alert auth-alert-success">
            Se ha enviado un nuevo enlace de verificación a tu correo.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-primary">
            Reenviar correo de verificación
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: 0.75rem;">
        @csrf
        <button type="submit" class="btn-secondary">
            Cerrar sesión
        </button>
    </form>

</x-guest-layout>