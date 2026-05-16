<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Adventur') }} — @yield('titulo', 'Panel')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
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
        html, body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

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
            transition: transform .25s ease;
        }
        .adv-sidebar::-webkit-scrollbar { display: none; }

        /* ── BRAND ── */
        .sb-brand {
            padding: 1.1rem 1.1rem;
            border-bottom: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; gap: .7rem;
            text-decoration: none;
            min-height: 64px;
        }
        .sb-logo-img {
            height: 38px; width: auto; max-width: 130px;
            object-fit: contain; flex-shrink: 0;
        }
        .sb-brand-icon {
            width: 34px; height: 34px; background: var(--amber);
            border-radius: 8px; display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
        }
        .sb-brand-icon span {
            color: var(--navy); font-size: .6rem; font-weight: 800;
            letter-spacing: .04em;
        }
        .sb-brand-text .name { color: white; font-weight: 700; font-size: .88rem; line-height: 1.15; }
        .sb-brand-text .sub  { color: var(--amber); font-size: .58rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; }

        /* ── NAV PRINCIPAL ── */
        .sb-nav { padding: 1rem .7rem; flex: 1; }

        .sb-section-label {
            font-size: .57rem; font-weight: 700; letter-spacing: .14em;
            text-transform: uppercase; color: rgba(255,255,255,.22);
            padding: 0 .5rem; margin-bottom: .4rem; margin-top: .9rem;
        }
        .sb-section-label:first-child { margin-top: 0; }

        /* ── LINKS ── */
        .sb-link {
            display: flex; align-items: center; gap: .6rem;
            padding: .52rem .7rem; border-radius: 7px;
            font-size: .82rem; font-weight: 500;
            color: rgba(255,255,255,.48);
            text-decoration: none;
            transition: all .15s;
            margin-bottom: 1px;
            position: relative;
        }
        .sb-link i { font-size: .88rem; width: 17px; text-align: center; flex-shrink: 0; }
        .sb-link:hover { background: rgba(255,255,255,.07); color: rgba(255,255,255,.88); }
        .sb-link.active {
            background: rgba(245,158,11,.13);
            color: var(--amber);
            font-weight: 600;
        }
        .sb-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px; border-radius: 0 3px 3px 0;
            background: var(--amber);
        }

        /* Badge */
        .sb-badge {
            margin-left: auto;
            background: var(--amber);
            color: var(--navy);
            border-radius: 999px;
            font-size: .6rem; font-weight: 800;
            padding: 1px 6px; min-width: 18px; text-align: center;
        }

        .sb-divider { height: 1px; background: rgba(255,255,255,.06); margin: .6rem .7rem; }

        /* ── FOOTER ── */
        .sb-footer {
            padding: .65rem .7rem;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .sb-user {
            display: flex; align-items: center; gap: .6rem;
            padding: .55rem .7rem; border-radius: 7px;
            background: rgba(255,255,255,.04);
        }
        .sb-avatar {
            width: 30px; height: 30px; background: var(--amber);
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
            color: var(--navy); font-size: .65rem; font-weight: 800;
        }
        .sb-user-name { color: rgba(255,255,255,.85); font-size: .78rem; font-weight: 600; line-height: 1.1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sb-user-role { color: rgba(255,255,255,.3); font-size: .64rem; }

        /* ── CONTENIDO ── */
        .adv-content { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-width: 0; }

        /* ── TOPBAR ── */
        .adv-topbar {
            height: 54px; background: white; border-bottom: 1px solid var(--line);
            display: flex; align-items: center; padding: 0 1.5rem; gap: 1rem;
            position: sticky; top: 0; z-index: 50;
            box-shadow: 0 1px 0 var(--line);
        }
        .topbar-title { font-size: .92rem; font-weight: 700; color: var(--ink); flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .topbar-title span { font-weight: 400; color: var(--ink-4); font-size: .82rem; margin-left: .4rem; }
        .topbar-actions { display: flex; align-items: center; gap: .65rem; }

        .tb-btn {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: 7px 13px; border-radius: 7px;
            font-size: .79rem; font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer; text-decoration: none;
            transition: all .15s; border: none; white-space: nowrap;
        }
        .tb-btn-primary { background: var(--amber); color: var(--navy); }
        .tb-btn-primary:hover { background: var(--amber-h); color: var(--navy); }

        .tb-user-btn {
            display: flex; align-items: center; gap: .5rem;
            padding: 5px 10px 5px 5px;
            border: 1.5px solid var(--line); border-radius: 7px;
            background: white; cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: border-color .15s;
        }
        .tb-user-btn:hover { border-color: var(--ink-4); }
        .tb-user-avatar {
            width: 27px; height: 27px; background: var(--navy);
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; color: var(--amber);
            font-size: .62rem; font-weight: 800;
        }
        .tb-user-name { font-size: .78rem; font-weight: 600; color: var(--ink-2); }
        .tb-user-btn i { font-size: .62rem; color: var(--ink-4); }

        /* ── DROPDOWN ── */
        .tb-dropdown { position: relative; }
        .tb-dropdown-menu {
            display: none; position: absolute; right: 0;
            top: calc(100% + 6px); background: white;
            border: 1px solid var(--line); border-radius: 10px;
            min-width: 195px; box-shadow: 0 8px 24px rgba(0,0,0,.1);
            z-index: 200; overflow: hidden;
        }
        .tb-dropdown-menu.open { display: block; }
        .tb-dd-header { padding: 10px 14px; background: var(--line-2); border-bottom: 1px solid var(--line); }
        .tb-dd-header .dd-name  { font-size: .8rem; font-weight: 700; color: var(--ink); }
        .tb-dd-header .dd-email { font-size: .68rem; color: var(--ink-4); }
        .tb-dd-item {
            display: flex; align-items: center; gap: .5rem;
            padding: 9px 14px; font-size: .8rem; color: var(--ink-2);
            text-decoration: none; transition: background .1s;
        }
        .tb-dd-item:hover { background: var(--line-2); }
        .tb-dd-item.danger { color: var(--red); }
        .tb-dd-item.danger:hover { background: #fef2f2; }
        .tb-dd-divider { height: 1px; background: var(--line); }

        /* ── MAIN ── */
        .adv-main { flex: 1; padding: 1.5rem; }

        /* ── HAMBURGUESA ── */
        .sb-toggle {
            display: none; background: none; border: none;
            cursor: pointer; color: var(--ink); font-size: 1.25rem;
            padding: 4px 6px; border-radius: 6px;
            transition: background .15s; flex-shrink: 0;
        }
        .sb-toggle:hover { background: var(--line); }

        /* ── OVERLAY MÓVIL ── */
        .sb-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 99; }
        .sb-overlay.open { display: block; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .adv-sidebar { transform: translateX(-100%); z-index: 100; }
            .adv-sidebar.open { transform: translateX(0); }
            .adv-content { margin-left: 0 !important; }
            .sb-toggle { display: flex; align-items: center; }
            .adv-topbar { padding: 0 1rem; gap: .75rem; }
            .adv-main { padding: 1rem; }
            .tb-btn-primary .tb-btn-text { display: none; }
            .tb-user-name { display: none; }
        }
        @media (max-width: 480px) {
            .adv-topbar { padding: 0 .75rem; gap: .5rem; }
        }

        /* ── TOASTS ── */
        @keyframes slideIn { from{opacity:0;transform:translateX(20px)} to{opacity:1;transform:translateX(0)} }
    </style>
</head>
<body>
<div class="adv-layout">

    <div class="sb-overlay" id="sb-overlay" onclick="cerrarSidebar()"></div>

    <aside class="adv-sidebar" id="adv-sidebar">

        {{-- BRAND --}}
        <a href="{{ route('dashboard') }}" class="sb-brand">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="sb-logo-img">
            @elseif(file_exists(public_path('images/logo.svg')))
                <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="sb-logo-img">
            @else
                <div class="sb-brand-icon"><span>ADV</span></div>
                <div class="sb-brand-text">
                    <div class="name">Adventur</div>
                    <div class="sub">Sistema de Reservas</div>
                </div>
            @endif
        </a>

        {{-- NAV --}}
        <nav class="sb-nav">

            {{-- PRINCIPAL --}}
            <div class="sb-section-label">Principal</div>

            <a href="{{ route('dashboard') }}"
               class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <a href="{{ route('reservas.index') }}"
               class="sb-link {{ request()->routeIs('reservas.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Reservas
                @php
                    $idMitad    = \App\Models\EstadoReserva::where('nombre','mitad_pago')->value('id');
                    $pendientes = $idMitad ? \App\Models\Reserva::where('estado_id',$idMitad)->count() : 0;
                @endphp
                @if($pendientes > 0)
                    <span class="sb-badge">{{ $pendientes }}</span>
                @endif
            </a>

            <a href="{{ route('clientes.index') }}"
               class="sb-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Clientes
            </a>

            <a href="{{ route('estadisticas.index') }}"
               class="sb-link {{ request()->routeIs('estadisticas.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> Estadísticas
            </a>

            <div class="sb-divider"></div>

            {{-- CUENTA --}}
            <div class="sb-section-label">Cuenta</div>

            <a href="{{ route('reservas.create') }}" class="sb-link">
                <i class="bi bi-plus-circle"></i> Nueva Reserva
            </a>

            <a href="{{ route('profile.edit') }}"
               class="sb-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Configuración
            </a>

            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="sb-link"
                        style="width:100%;background:none;border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;text-align:left;">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </button>
            </form>

        </nav>

        {{-- FOOTER USUARIO --}}
        <div class="sb-footer">
            <div class="sb-user">
                <div class="sb-avatar">
                    {{ strtoupper(substr(Auth::user()->nombre ?? Auth::user()->name ?? 'AD', 0, 2)) }}
                </div>
                <div style="min-width:0">
                    <div class="sb-user-name">
                        {{ Auth::user()->nombre ?? Auth::user()->name }}
                    </div>
                    <div class="sb-user-role">{{ Auth::user()->rol ?? 'Administrador' }}</div>
                </div>
            </div>
        </div>

    </aside>

    {{-- CONTENIDO --}}
    <div class="adv-content">

        <header class="adv-topbar">
            <button class="sb-toggle" id="sb-toggle" onclick="abrirSidebar()" aria-label="Abrir menú">
                <i class="bi bi-list"></i>
            </button>
            <div class="topbar-title">
                @yield('titulo', 'Panel')
                @hasSection('subtitulo')<span>/ @yield('subtitulo')</span>@endif
            </div>
            <div class="topbar-actions">
                <a href="{{ route('reservas.create') }}" class="tb-btn tb-btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    <span class="tb-btn-text">Nueva Reserva</span>
                </a>
                <div class="tb-dropdown" id="tb-dropdown">
                    <button class="tb-user-btn" onclick="toggleDropdown()">
                        <div class="tb-user-avatar">
                            {{ strtoupper(substr(Auth::user()->nombre ?? Auth::user()->name ?? 'AD', 0, 2)) }}
                        </div>
                        <span class="tb-user-name">{{ Auth::user()->nombre ?? Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="tb-dropdown-menu" id="tb-dd-menu">
                        <div class="tb-dd-header">
                            <div class="dd-name">{{ Auth::user()->nombre ?? Auth::user()->name }}</div>
                            <div class="dd-email">{{ Auth::user()->email }}</div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="tb-dd-item">
                            <i class="bi bi-gear"></i> Configuración
                        </a>
                        <div class="tb-dd-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="tb-dd-item danger"
                                    style="width:100%;background:none;border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;text-align:left;">
                                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

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
document.addEventListener('click', function(e) {
    const dd = document.getElementById('tb-dropdown');
    if (dd && !dd.contains(e.target))
        document.getElementById('tb-dd-menu')?.classList.remove('open');
});
function abrirSidebar() {
    document.getElementById('adv-sidebar').classList.add('open');
    document.getElementById('sb-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function cerrarSidebar() {
    document.getElementById('adv-sidebar').classList.remove('open');
    document.getElementById('sb-overlay').classList.remove('open');
    document.body.style.overflow = '';
}
document.querySelectorAll('#adv-sidebar .sb-link').forEach(link => {
    link.addEventListener('click', function() {
        if (window.innerWidth <= 768) cerrarSidebar();
    });
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarSidebar(); });
</script>

{{-- TOASTS --}}
@if(session('success') || session('error') || session('warning'))
<div id="toast-container" style="position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;max-width:calc(100vw - 40px);">
    @if(session('success'))
    <div class="toast-msg" style="display:flex;align-items:center;gap:10px;background:#f0fdf4;border:1.5px solid #86efac;border-left:4px solid #16a34a;padding:12px 16px;border-radius:10px;box-shadow:0 4px 16px rgba(0,0,0,.1);font-size:.83rem;font-weight:500;color:#15803d;min-width:280px;max-width:420px;animation:slideIn .25s ease;">
        <i class="bi bi-check-circle-fill" style="font-size:1.1rem;flex-shrink:0;"></i>
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:#16a34a;font-size:1rem;padding:0;">×</button>
    </div>
    @endif
    @if(session('error'))
    <div class="toast-msg" style="display:flex;align-items:center;gap:10px;background:#fef2f2;border:1.5px solid #fca5a5;border-left:4px solid #dc2626;padding:12px 16px;border-radius:10px;box-shadow:0 4px 16px rgba(0,0,0,.1);font-size:.83rem;font-weight:500;color:#dc2626;min-width:280px;max-width:420px;animation:slideIn .25s ease;">
        <i class="bi bi-exclamation-circle-fill" style="font-size:1.1rem;flex-shrink:0;"></i>
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:#dc2626;font-size:1rem;padding:0;">×</button>
    </div>
    @endif
    @if(session('warning'))
    <div class="toast-msg" style="display:flex;align-items:center;gap:10px;background:#fffbeb;border:1.5px solid #fcd34d;border-left:4px solid #d97706;padding:12px 16px;border-radius:10px;box-shadow:0 4px 16px rgba(0,0,0,.1);font-size:.83rem;font-weight:500;color:#b45309;min-width:280px;max-width:420px;animation:slideIn .25s ease;">
        <i class="bi bi-exclamation-triangle-fill" style="font-size:1.1rem;flex-shrink:0;"></i>
        <span>{{ session('warning') }}</span>
        <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:#d97706;font-size:1rem;padding:0;">×</button>
    </div>
    @endif
</div>
<script>
setTimeout(() => {
    document.querySelectorAll('.toast-msg').forEach(t => {
        t.style.animation = 'slideIn .25s ease reverse';
        setTimeout(() => t.remove(), 250);
    });
}, 5000);
</script>
@endif

@stack('scripts')
</body>
</html>