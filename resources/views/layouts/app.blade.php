{{-- =====================================================================
     ARCHIVO: app.blade.php
     UBICACIÓN: resources/views/layouts/app.blade.php
     ===================================================================== --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADVENTUR — @yield('titulo', 'Sistema de Reservas')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { background: #f0f2f7; font-family: 'Segoe UI', sans-serif; margin: 0; }

        /* ══════════════════════════════
           SIDEBAR
        ══════════════════════════════ */
        .sidebar {
            width: 220px;
            min-height: 100vh;
            background: #0f172a;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 200;
        }

        /* Logo */
        .sidebar-brand {
            padding: 22px 20px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #1e293b;
        }
        .sidebar-brand .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #f0a500, #e05c00);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; color: #fff; font-weight: 800;
            flex-shrink: 0;
        }
        .sidebar-brand .logo-text {
            font-size: 1rem; font-weight: 700;
            letter-spacing: 2px; color: #fff;
        }
        .sidebar-brand .logo-text span { color: #f0a500; }

        /* Links */
        .sidebar nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.18s ease;
            position: relative;
        }
        .nav-item i {
            font-size: 1.05rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }
        .nav-item:hover {
            background: #1e293b;
            color: #e2e8f0;
        }
        .nav-item.active {
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(37,99,235,0.35);
        }
        .nav-item.active i { color: #fff; }

        /* Separador visual entre secciones */
        .nav-divider {
            height: 1px;
            background: #1e293b;
            margin: 10px 4px;
        }

        /* Tarjeta de info del usuario al fondo */
        .sidebar-footer {
            padding: 14px 12px;
            border-top: 1px solid #1e293b;
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: #1e293b;
            border-radius: 10px;
        }
        .sidebar-user .avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #f0a500, #e05c00);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; color: #fff; font-weight: 700;
            flex-shrink: 0;
        }
        .sidebar-user .user-info .user-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #e2e8f0;
            line-height: 1.2;
        }
        .sidebar-user .user-info .user-role {
            font-size: 0.7rem;
            color: #64748b;
        }
        .sidebar-user .logout-btn {
            margin-left: auto;
            color: #475569;
            text-decoration: none;
            font-size: 1rem;
            transition: color .2s;
        }
        .sidebar-user .logout-btn:hover { color: #ef4444; }

        /* Estadísticas rápidas en sidebar */
        .sidebar-stats {
            padding: 12px 12px 0;
        }
        .stat-mini {
            background: #1e293b;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 8px;
        }
        .stat-mini .stat-label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #64748b;
            font-weight: 600;
        }
        .stat-mini .stat-val {
            font-size: 1.3rem;
            font-weight: 700;
            color: #f1f5f9;
            line-height: 1.2;
        }
        .stat-mini .stat-sub {
            font-size: 0.7rem;
            color: #475569;
        }

        /* ══════════════════════════════
           CONTENIDO PRINCIPAL
        ══════════════════════════════ */
        .main-content {
            margin-left: 220px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            padding: 0 28px;
            height: 58px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topbar-left .page-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0f172a;
        }
        .topbar-left .breadcrumb-sep {
            color: #cbd5e1;
            font-size: 0.8rem;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .topbar-date {
            font-size: 0.78rem;
            color: #94a3b8;
        }

        .page-body {
            padding: 24px 28px;
            flex: 1;
        }

        /* Alertas */
        .alert { border-radius: 10px; border: none; font-size: 0.88rem; }
        .alert-success { background: #f0fdf4; color: #166534; }

        /* Cards */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            padding: 14px 18px;
            font-weight: 600;
            font-size: 0.88rem;
            border-radius: 12px 12px 0 0 !important;
        }

        /* Badges de estado */
        .badge-estado { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-consulta    { background: #f1f5f9; color: #475569; }
        .badge-pre-reserva { background: #fef3c7; color: #92400e; }
        .badge-confirmada  { background: #dcfce7; color: #166534; }
        .badge-cancelada   { background: #fee2e2; color: #991b1b; }
        .badge-finalizada  { background: #dbeafe; color: #1e40af; }
    </style>
    @stack('styles')
</head>
<body>

{{-- ══════════════ SIDEBAR ══════════════ --}}
<div class="sidebar">

    {{-- Logo --}}
    <div class="sidebar-brand">
        <div class="logo-icon">A</div>
        <div class="logo-text"><span>ADV</span>ENTUR</div>
    </div>

    {{-- Stats rápidas --}}
    <div class="sidebar-stats">
        <div class="stat-mini">
            <div class="stat-label">Reservas hoy</div>
            <div class="stat-val">—</div>
            <div class="stat-sub">Sin datos aún</div>
        </div>
    </div>

    {{-- Navegación --}}
    <nav class="mt-1">
        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>

        <div class="nav-divider"></div>

        <a href="{{ route('reservas.index') }}"
           class="nav-item {{ request()->routeIs('reservas.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i>
            Reservas
        </a>

        <a href="{{ route('reservas.create') }}"
           class="nav-item {{ request()->routeIs('reservas.create') ? 'active' : '' }}"
           style="padding-left: 46px; font-size: 0.8rem; color: #64748b;">
            <i class="bi bi-plus-circle" style="font-size:.85rem"></i>
            Nueva reserva
        </a>
    </nav>

    {{-- Usuario al fondo --}}
    <div class="sidebar-footer">
        @auth
        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">Administrador</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="logout-btn border-0 bg-transparent p-0"
                        title="Cerrar sesión">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
        @endauth
    </div>
</div>

{{-- ══════════════ MAIN ══════════════ --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <div class="topbar-left">
            <i class="bi bi-grid-1x2" style="color:#94a3b8;font-size:.9rem"></i>
            <span class="breadcrumb-sep">/</span>
            <span class="page-title">@yield('titulo', 'Dashboard')</span>
        </div>
        <div class="topbar-right">
            <span class="topbar-date">
                <i class="bi bi-calendar3 me-1"></i>
                {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM YYYY') }}
            </span>
        </div>
    </div>

    {{-- Cuerpo --}}
    <div class="page-body">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('contenido')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
@yield('scripts')
</body>
</html>