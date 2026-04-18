<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Adventur') }} — @yield('titulo', 'Panel')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --navy:    #0f1f3d;
            --navy-2:  #162444;
            --navy-3:  #1e3160;
            --amber:   #f59e0b;
            --amber-h: #fbbf24;
            --amber-l: #fffbeb;
            --ink:     #0f172a;
            --ink-2:   #1e293b;
            --ink-3:   #475569;
            --ink-4:   #94a3b8;
            --line:    #e2e8f0;
            --line-2:  #f8fafc;
            --surf:    #ffffff;
            --blue:    #1d4ed8;
            --blue-l:  #eff6ff;
            --green:   #059669;
            --red:     #dc2626;
            --sidebar-w: 240px;
        }
        html, body { height: 100%; font-family: 'Inter', sans-serif; background: #f1f5f9; color: var(--ink); -webkit-font-smoothing: antialiased; }

        /* ── LAYOUT ── */
        .adv-layout { display: flex; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .adv-sidebar {
            width: var(--sidebar-w);
            background: var(--navy);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
            overflow-y: auto;
            scrollbar-width: none;
        }
        .adv-sidebar::-webkit-scrollbar { display: none; }

        .sb-brand {
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex; align-items: center; gap: .75rem;
            text-decoration: none;
        }
        .sb-brand-icon {
            width: 36px; height: 36px; background: var(--amber);
            border-radius: 9px; display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
        }
        .sb-brand-icon span {
            color: var(--navy); font-size: .65rem; font-weight: 800;
            letter-spacing: .04em;
        }
        .sb-brand-text .name {
            color: white; font-weight: 700; font-size: .95rem; line-height: 1.1;
        }
        .sb-brand-text .sub {
            color: var(--amber); font-size: .6rem; font-weight: 600;
            letter-spacing: .1em; text-transform: uppercase;
        }

        .sb-section {
            padding: 1.25rem .75rem .5rem;
        }
        .sb-section-label {
            font-size: .6rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: rgba(255,255,255,.25);
            padding: 0 .5rem; margin-bottom: .4rem;
        }

        .sb-link {
            display: flex; align-items: center; gap: .65rem;
            padding: .6rem .75rem; border-radius: 8px;
            font-size: .84rem; font-weight: 500;
            color: rgba(255,255,255,.55);
            text-decoration: none;
            transition: all .15s;
            margin-bottom: 2px;
        }
        .sb-link i { font-size: .95rem; width: 18px; text-align: center; flex-shrink: 0; }
        .sb-link:hover { background: rgba(255,255,255,.06); color: rgba(255,255,255,.9); }
        .sb-link.active {
            background: rgba(245,158,11,.12);
            color: var(--amber);
            font-weight: 600;
        }
        .sb-link.active i { color: var(--amber); }

        .sb-footer {
            margin-top: auto;
            padding: .75rem;
            border-top: 1px solid rgba(255,255,255,.06);
        }
        .sb-user {
            display: flex; align-items: center; gap: .65rem;
            padding: .6rem .75rem; border-radius: 8px;
            cursor: default;
        }
        .sb-avatar {
            width: 30px; height: 30px; background: var(--amber);
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
            color: var(--navy); font-size: .68rem; font-weight: 800;
        }
        .sb-user-name { color: white; font-size: .82rem; font-weight: 600; line-height: 1.1; }
        .sb-user-role { color: rgba(255,255,255,.35); font-size: .68rem; }

        /* ── CONTENIDO PRINCIPAL ── */
        .adv-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* ── TOPBAR ── */
        .adv-topbar {
            height: 56px;
            background: white;
            border-bottom: 1px solid var(--line);
            display: flex; align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title {
            font-size: .95rem; font-weight: 700; color: var(--ink);
            flex: 1;
        }
        .topbar-title span {
            font-weight: 400; color: var(--ink-4); font-size: .84rem; margin-left: .4rem;
        }
        .topbar-actions { display: flex; align-items: center; gap: .75rem; }

        .tb-btn {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: 7px 14px; border-radius: 8px;
            font-size: .8rem; font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer; text-decoration: none;
            transition: all .15s; border: none;
        }
        .tb-btn-primary { background: var(--amber); color: var(--navy); }
        .tb-btn-primary:hover { background: var(--amber-h); color: var(--navy); }

        .tb-user-btn {
            display: flex; align-items: center; gap: .5rem;
            padding: 5px 10px 5px 5px;
            border: 1.5px solid var(--line); border-radius: 8px;
            background: white; cursor: pointer; font-family: 'Inter', sans-serif;
            transition: border-color .15s;
        }
        .tb-user-btn:hover { border-color: var(--ink-4); }
        .tb-user-avatar {
            width: 28px; height: 28px; background: var(--navy);
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; color: var(--amber);
            font-size: .65rem; font-weight: 800;
        }
        .tb-user-name { font-size: .8rem; font-weight: 600; color: var(--ink-2); }
        .tb-user-btn i { font-size: .65rem; color: var(--ink-4); }

        /* ── DROPDOWN ── */
        .tb-dropdown { position: relative; }
        .tb-dropdown-menu {
            display: none;
            position: absolute; right: 0; top: calc(100% + 6px);
            background: white; border: 1px solid var(--line);
            border-radius: 10px; min-width: 200px;
            box-shadow: 0 8px 24px rgba(0,0,0,.1);
            z-index: 200; overflow: hidden;
        }
        .tb-dropdown-menu.open { display: block; }
        .tb-dd-header {
            padding: 10px 14px; background: var(--line-2);
            border-bottom: 1px solid var(--line);
        }
        .tb-dd-header .dd-name { font-size: .82rem; font-weight: 700; color: var(--ink); }
        .tb-dd-header .dd-email { font-size: .7rem; color: var(--ink-4); }
        .tb-dd-item {
            display: flex; align-items: center; gap: .5rem;
            padding: 9px 14px; font-size: .82rem; color: var(--ink-2);
            text-decoration: none; transition: background .1s;
        }
        .tb-dd-item:hover { background: var(--line-2); }
        .tb-dd-item.danger { color: var(--red); }
        .tb-dd-item.danger:hover { background: #fef2f2; }
        .tb-dd-divider { height: 1px; background: var(--line); }

        /* ── MAIN AREA ── */
        .adv-main { flex: 1; padding: 1.5rem; }
    </style>
</head>
<body>
<div class="adv-layout">

    {{-- ══ SIDEBAR ══ --}}
    <aside class="adv-sidebar">
        <a href="{{ route('dashboard') }}" class="sb-brand">
            <div class="sb-brand-icon"><span>ADV</span></div>
            <div class="sb-brand-text">
                <div class="name">Adventur</div>
                <div class="sub">Sistema de Reservas</div>
            </div>
        </a>

        {{-- Principal: solo Dashboard y Reservas --}}
        <div class="sb-section">
            <div class="sb-section-label">Principal</div>
            <a href="{{ route('dashboard') }}"
               class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('reservas.index') }}"
               class="sb-link {{ request()->routeIs('reservas.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Reservas
            </a>
        </div>

        {{-- Cuenta: solo Configuración y Cerrar sesión --}}
        <div class="sb-section">
            <div class="sb-section-label">Cuenta</div>
            <a href="{{ route('profile.edit') }}"
               class="sb-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Configuración
            </a>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="sb-link"
                        style="width:100%;background:none;border:none;cursor:pointer;font-family:'Inter',sans-serif;color:rgba(255,255,255,.55);text-align:left;">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </button>
            </form>
        </div>

        <div class="sb-footer">
            <div class="sb-user">
                <div class="sb-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <div>
                    <div class="sb-user-name">{{ Auth::user()->name }}</div>
                    <div class="sb-user-role">Administrador</div>
                </div>
            </div>
        </div>
    </aside>

    {{-- ══ CONTENIDO ══ --}}
    <div class="adv-content">

        {{-- TOPBAR --}}
        <header class="adv-topbar">
            <div class="topbar-title">
                @yield('titulo', 'Panel')
                @hasSection('subtitulo')
                    <span>/ @yield('subtitulo')</span>
                @endif
            </div>
            <div class="topbar-actions">
                <a href="{{ route('reservas.create') }}" class="tb-btn tb-btn-primary">
                    <i class="bi bi-plus-lg"></i> Nueva Reserva
                </a>
                <div class="tb-dropdown" id="tb-dropdown">
                    <button class="tb-user-btn" onclick="toggleDropdown()">
                        <div class="tb-user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <span class="tb-user-name">{{ Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="tb-dropdown-menu" id="tb-dd-menu">
                        <div class="tb-dd-header">
                            <div class="dd-name">{{ Auth::user()->name }}</div>
                            <div class="dd-email">{{ Auth::user()->email }}</div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="tb-dd-item">
                            <i class="bi bi-gear"></i> Configuración
                        </a>
                        <div class="tb-dd-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="tb-dd-item danger"
                                    style="width:100%;background:none;border:none;cursor:pointer;font-family:'Inter',sans-serif;text-align:left;">
                                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="adv-main">
            @hasSection('contenido')
                @yield('contenido')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>

    </div>
</div>

<script>
function toggleDropdown() {
    document.getElementById('tb-dd-menu').classList.toggle('open');
}
document.addEventListener('click', function (e) {
    const dd = document.getElementById('tb-dropdown');
    if (dd && !dd.contains(e.target)) {
        document.getElementById('tb-dd-menu')?.classList.remove('open');
    }
});
</script>
@stack('scripts')
</body>
</html>