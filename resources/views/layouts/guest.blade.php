<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adventur Reservas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0a1628;
        }

        /* Panel izquierdo - decorativo */
        .auth-panel-left {
            display: none;
            width: 55%;
            position: relative;
            background: linear-gradient(145deg, #0a1628 0%, #0d2047 50%, #0a1628 100%);
            overflow: hidden;
        }

        @media (min-width: 1024px) {
            .auth-panel-left { display: flex; flex-direction: column; justify-content: space-between; padding: 3rem; }
        }

        .auth-panel-left::before {
            content: '';
            position: absolute;
            top: -200px; right: -200px;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(234,179,8,0.12) 0%, transparent 70%);
        }

        .auth-panel-left::after {
            content: '';
            position: absolute;
            bottom: -150px; left: -150px;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 70%);
        }

        .panel-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .panel-logo-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, #eab308, #f59e0b);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }

        .panel-logo-icon svg {
            width: 24px; height: 24px;
            fill: #0a1628;
        }

        .panel-logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        .panel-logo-text span {
            color: #eab308;
        }

        .panel-hero {
            position: relative;
            z-index: 1;
        }

        .panel-hero h1 {
            font-size: 2.8rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;
        }

        .panel-hero h1 span {
            color: #eab308;
        }

        .panel-hero p {
            font-size: 1rem;
            color: rgba(255,255,255,0.55);
            line-height: 1.7;
            max-width: 380px;
        }

        .panel-stats {
            display: flex;
            gap: 2rem;
            position: relative;
            z-index: 1;
        }

        .stat-item {
            border-top: 2px solid rgba(234,179,8,0.4);
            padding-top: 1rem;
        }

        .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
            color: #eab308;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.45);
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* Panel derecho - formulario */
        .auth-panel-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            background: #f8fafc;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
        }

        /* Logo mobile */
        .auth-logo-mobile {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2.5rem;
        }

        @media (min-width: 1024px) {
            .auth-logo-mobile { display: none; }
        }

        .auth-logo-mobile-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #eab308, #f59e0b);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }

        .auth-logo-mobile-icon svg {
            width: 20px; height: 20px;
            fill: #0a1628;
        }

        .auth-logo-mobile-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0a1628;
        }

        .auth-logo-mobile-text span { color: #ca8a04; }

        /* Cabecera del form */
        .auth-header {
            margin-bottom: 2rem;
        }

        .auth-header h2 {
            font-size: 1.65rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.025em;
            margin-bottom: 0.4rem;
        }

        .auth-header p {
            font-size: 0.9rem;
            color: #64748b;
        }

        /* Campos */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.45rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-input {
            width: 100%;
            height: 48px;
            padding: 0 1rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem;
            color: #0f172a;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        .form-input.is-error {
            border-color: #ef4444;
        }

        .form-error {
            font-size: 0.78rem;
            color: #ef4444;
            margin-top: 0.35rem;
        }

        /* Row checkbox + link */
        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            color: #475569;
            cursor: pointer;
        }

        .checkbox-label input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #2563eb;
            cursor: pointer;
        }

        .form-link {
            font-size: 0.875rem;
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .form-link:hover { color: #1d4ed8; text-decoration: underline; }

        /* Botón principal */
        .btn-primary {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
            letter-spacing: 0.01em;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37,99,235,0.35);
        }

        .btn-primary:active { transform: translateY(0); }

        /* Botón secundario */
        .btn-secondary {
            width: 100%;
            height: 50px;
            background: transparent;
            color: #2563eb;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            border: 1.5px solid #2563eb;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            margin-top: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-secondary:hover { background: #eff6ff; }

        /* Alerta de estado */
        .auth-alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .auth-alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .auth-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        /* Footer del card */
        .auth-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 0.82rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <!-- Panel izquierdo (solo desktop) -->
    <div class="auth-panel-left">
        <div class="panel-logo">
            <div class="panel-logo-icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
                </svg>
            </div>
            <span class="panel-logo-text">Adventur <span>Reservas</span></span>
        </div>

        <div class="panel-hero">
            <h1>Gestiona tus<br>reservas con<br><span>total control</span></h1>
            <p>Plataforma integral para administrar tours, pasajeros, pagos y comprobantes en un solo lugar.</p>
        </div>

        <div class="panel-stats">
            <div class="stat-item">
                <div class="stat-number">100%</div>
                <div class="stat-label">Digital</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Disponible</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">+Tours</div>
                <div class="stat-label">Registrados</div>
            </div>
        </div>
    </div>

    <!-- Panel derecho -->
    <div class="auth-panel-right">
        <div class="auth-card">

            <!-- Logo mobile -->
            <div class="auth-logo-mobile">
                <div class="auth-logo-mobile-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
                    </svg>
                </div>
                <span class="auth-logo-mobile-text">Adventur <span>Reservas</span></span>
            </div>

            {{ $slot }}

            <div class="auth-footer">
                &copy; {{ date('Y') }} Adventur Reservas &mdash; Todos los derechos reservados
            </div>
        </div>
    </div>

</body>
</html>