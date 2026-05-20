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
            --navy:      #1b3a6b;
            --navy-2:    #22478a;
            --navy-3:    #2a56a8;
            --navy-4:    #3464c0;
            --amber:     #f5c842;
            --amber-h:   #f7d060;
            --amber-l:   #fffbea;
            --amber-d:   #d4a800;
            --ink:       #0f172a;
            --ink-2:     #1e293b;
            --ink-3:     #475569;
            --ink-4:     #94a3b8;
            --line:      #e2e8f0;
            --line-2:    #f8fafc;
            --surf:      #ffffff;
            --blue:      #1d4ed8;
            --blue-l:    #eff6ff;
            --blue-2:    #dbeafe;
            --green:     #059669;
            --green-l:   #ecfdf5;
            --red:       #dc2626;
            --red-l:     #fef2f2;
            --sidebar-w: 248px;
            --sidebar-bg: #ffffff;
        }
        html, body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f4f8;
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        .adv-layout { display: flex; min-height: 100vh; }

        .adv-sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--line);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
            overflow-y: auto;
            scrollbar-width: none;
            transition: transform .28s cubic-bezier(.4,0,.2,1);
            box-shadow: 2px 0 12px rgba(0,0,0,.06);
        }
        .adv-sidebar::-webkit-scrollbar { display: none; }

        .sb-brand {
            padding: .5rem .75rem;
            height: 150px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            flex-shrink: 0;
            background: #ffffff;
        }
        .sb-logo-img {
            height: 190px;
            width: auto;
            max-width: 210px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .sb-brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--amber) 0%, var(--amber-d) 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(245,200,66,.35);
        }
        .sb-brand-icon span { color: var(--navy); font-size: .65rem; font-weight: 800; }
        .sb-brand-text .name { color: var(--navy); font-weight: 700; font-size: .95rem; line-height: 1.2; }
        .sb-brand-text .sub  { color: var(--amber-d); font-size: .6rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; }

        .sb-nav { padding: .75rem .65rem; flex: 1; }

        .sb-section-label {
            font-size: .58rem; font-weight: 700; letter-spacing: .16em;
            text-transform: uppercase; color: var(--ink-4);
            padding: 0 .6rem; margin-bottom: .3rem; margin-top: 1.1rem;
            display: flex; align-items: center; gap: .5rem;
        }
        .sb-section-label::after {
            content: ''; flex: 1; height: 1px; background: var(--line);
        }
        .sb-section-label:first-child { margin-top: 0; }

        .sb-link {
            display: flex; align-items: center; gap: .65rem;
            padding: .55rem .7rem; border-radius: 8px;
            font-size: .82rem; font-weight: 500;
            color: var(--ink-3);
            text-decoration: none;
            transition: background .15s, color .15s;
            margin-bottom: 2px;
            position: relative;
            cursor: pointer;
            background: none; border: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
            width: 100%; text-align: left;
        }
        .sb-link i {
            font-size: .9rem; width: 18px; text-align: center;
            flex-shrink: 0; color: var(--ink-4); transition: color .15s;
        }
        .sb-link:hover { background: var(--line-2); color: var(--navy); }
        .sb-link:hover i { color: var(--navy); }
        .sb-link.active { background: #eef3fb; color: var(--navy); font-weight: 700; }
        .sb-link.active i { color: var(--navy); }
        .sb-link.active::before {
            content: '';
            position: absolute; left: 0; top: 18%; bottom: 18%;
            width: 3px; border-radius: 0 3px 3px 0;
            background: var(--amber);
        }

        .topbar-page-tag {
            display: inline-flex; align-items: center; gap: .5rem;
            background: #eef3fb; border: 1.5px solid #c8dff0;
            border-radius: 10px; padding: 6px 14px;
            max-width: fit-content; overflow: hidden;
        }
        .topbar-page-tag i { font-size: .85rem; color: var(--navy); flex-shrink: 0; }
        .topbar-title { font-size: .82rem; font-weight: 700; color: var(--navy); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .topbar-divider { color: var(--ink-4); font-size: .75rem; flex-shrink: 0; }
        .topbar-sub { font-size: .78rem; font-weight: 500; color: var(--ink-3); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .sb-link-cta {
            display: flex; align-items: center; gap: .65rem;
            padding: .6rem .85rem; border-radius: 8px;
            font-size: .83rem; font-weight: 700;
            color: var(--navy);
            background: linear-gradient(135deg, var(--amber) 0%, var(--amber-d) 100%);
            text-decoration: none;
            transition: opacity .15s, transform .15s;
            margin-bottom: 2px;
            box-shadow: 0 2px 8px rgba(212,168,0,.25);
        }
        .sb-link-cta i { font-size: .9rem; width: 18px; text-align: center; flex-shrink: 0; }
        .sb-link-cta:hover { opacity: .9; transform: translateY(-1px); color: var(--navy); }

        .sb-badge {
            margin-left: auto;
            background: var(--navy); color: #fff;
            border-radius: 999px; font-size: .6rem; font-weight: 800;
            padding: 2px 7px; min-width: 20px; text-align: center; line-height: 1.4;
        }
        .sb-link.active .sb-badge { background: var(--amber); color: var(--navy); }
        .sb-divider { height: 1px; background: var(--line); margin: .5rem .65rem; }

        .sb-footer { padding: .75rem .65rem; border-top: 1px solid var(--line); flex-shrink: 0; }
        .sb-user {
            display: flex; align-items: center; gap: .65rem;
            padding: .6rem .7rem; border-radius: 8px;
            background: var(--line-2); transition: background .15s; cursor: default;
        }
        .sb-user:hover { background: var(--line); }
        .sb-avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-2) 100%);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; color: var(--amber); font-size: .68rem; font-weight: 800;
        }
        .sb-user-info { min-width: 0; flex: 1; }
        .sb-user-name { color: var(--ink-2); font-size: .78rem; font-weight: 600; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sb-user-role { color: var(--ink-4); font-size: .63rem; margin-top: 1px; }
        .sb-logout {
            display: flex; align-items: center; justify-content: center;
            width: 30px; height: 30px; border-radius: 7px;
            color: var(--ink-4); background: none; border: none;
            cursor: pointer; transition: all .15s; flex-shrink: 0; font-size: .85rem;
        }
        .sb-logout:hover { background: var(--red-l); color: var(--red); }

        .adv-content { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-width: 0; }

        .adv-topbar {
            height: 64px; background: #ffffff; border-bottom: 1px solid var(--line);
            display: flex; align-items: center; padding: 0 1.75rem; gap: 1rem;
            position: sticky; top: 0; z-index: 50; box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }
        .topbar-actions { display: flex; align-items: center; gap: .6rem; flex-shrink: 0; margin-left: auto; }
        .topbar-left { flex: 1; min-width: 0; display: flex; align-items: center; }

        .tb-btn {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: 8px 16px; border-radius: 9px; font-size: .8rem; font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif; cursor: pointer; text-decoration: none;
            transition: all .15s; border: none; white-space: nowrap;
        }
        .tb-btn-primary { background: var(--navy); color: #ffffff; box-shadow: 0 2px 8px rgba(27,58,107,.2); }
        .tb-btn-primary:hover { background: var(--navy-2); color: #ffffff; box-shadow: 0 4px 14px rgba(27,58,107,.3); transform: translateY(-1px); }
        .tb-btn-secondary { background: var(--amber); color: var(--navy); box-shadow: 0 2px 8px rgba(212,168,0,.2); }
        .tb-btn-secondary:hover { background: var(--amber-h); color: var(--navy); transform: translateY(-1px); }

        .tb-user-btn {
            display: flex; align-items: center; gap: .55rem;
            padding: 6px 12px 6px 6px; border: 1.5px solid var(--line); border-radius: 10px;
            background: white; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        .tb-user-btn:hover { border-color: var(--navy); background: var(--line-2); box-shadow: 0 0 0 3px rgba(27,58,107,.08); }
        .tb-user-avatar {
            width: 30px; height: 30px;
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-2) 100%);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: var(--amber); font-size: .65rem; font-weight: 800;
        }
        .tb-user-name { font-size: .8rem; font-weight: 600; color: var(--ink-2); }
        .tb-user-btn i { font-size: .62rem; color: var(--ink-4); }

        .tb-dropdown { position: relative; }
        .tb-dropdown-menu {
            display: none; position: absolute; right: 0; top: calc(100% + 8px);
            background: white; border: 1px solid var(--line); border-radius: 12px;
            min-width: 210px; box-shadow: 0 8px 30px rgba(0,0,0,.1), 0 2px 8px rgba(0,0,0,.05);
            z-index: 200; overflow: hidden;
        }
        .tb-dropdown-menu.open { display: block; animation: ddFadeIn .15s ease; }
        @keyframes ddFadeIn { from{opacity:0;transform:translateY(-4px)} to{opacity:1;transform:translateY(0)} }

        .tb-dd-header { padding: 12px 16px; background: linear-gradient(135deg, #eef3fb 0%, var(--line-2) 100%); border-bottom: 1px solid var(--line); }
        .tb-dd-header .dd-name  { font-size: .82rem; font-weight: 700; color: var(--navy); }
        .tb-dd-header .dd-email { font-size: .69rem; color: var(--ink-4); margin-top: 2px; }

        .tb-dd-item {
            display: flex; align-items: center; gap: .55rem;
            padding: 10px 16px; font-size: .81rem; color: var(--ink-2);
            text-decoration: none; transition: background .1s;
            background: none; border: none; cursor: pointer;
            width: 100%; font-family: 'Plus Jakarta Sans', sans-serif; text-align: left;
        }
        .tb-dd-item i { font-size: .85rem; color: var(--ink-4); }
        .tb-dd-item:hover { background: var(--line-2); color: var(--navy); }
        .tb-dd-item:hover i { color: var(--navy); }
        .tb-dd-item.danger { color: var(--red); }
        .tb-dd-item.danger i { color: var(--red); }
        .tb-dd-item.danger:hover { background: var(--red-l); }
        .tb-dd-divider { height: 1px; background: var(--line); }

        .adv-main { flex: 1; padding: 1.5rem; }

        .sb-toggle {
            display: none; background: none; border: none; cursor: pointer;
            color: var(--ink); font-size: 1.2rem; padding: 5px 7px; border-radius: 7px;
            transition: background .15s; flex-shrink: 0; align-items: center;
        }
        .sb-toggle:hover { background: var(--line); }

        .sb-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.45); z-index: 99; backdrop-filter: blur(2px);
        }
        .sb-overlay.open { display: block; animation: overlayIn .2s ease; }
        @keyframes overlayIn { from{opacity:0} to{opacity:1} }

        @media (max-width: 768px) {
            .adv-sidebar { transform: translateX(-100%); z-index: 100; }
            .adv-sidebar.open { transform: translateX(0); }
            .adv-content { margin-left: 0 !important; }
            .sb-toggle { display: flex; }
            .adv-topbar { padding: 0 1rem; gap: .75rem; }
            .adv-main { padding: 1rem; }
            .tb-btn-primary .tb-btn-text { display: none; }
            .tb-user-name { display: none; }
        }
        @media (max-width: 480px) {
            .adv-topbar { padding: 0 .75rem; gap: .5rem; }
            .adv-main { padding: .875rem; }
        }

        @keyframes slideIn  { from{opacity:0;transform:translateX(16px)} to{opacity:1;transform:translateX(0)} }
        @keyframes slideOut { from{opacity:1;transform:translateX(0)} to{opacity:0;transform:translateX(16px)} }
    </style>
</head>
<body>
<div class="adv-layout">

    <div class="sb-overlay" id="sb-overlay" onclick="cerrarSidebar()"></div>

    {{-- ══════════════ SIDEBAR ══════════════ --}}
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

            {{-- ── PRINCIPAL ── --}}
            <div class="sb-section-label">Principal</div>

            <a href="{{ route('reservas.create') }}" class="sb-link-cta">
                <i class="bi bi-plus-circle-fill"></i> Nueva Reserva
            </a>

            <div style="margin-bottom:4px"></div>

            <a href="{{ route('dashboard') }}"
               class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <a href="{{ route('reservas.index') }}"
               class="sb-link {{ request()->routeIs('reservas.*') && !request()->routeIs('reservas.create') ? 'active' : '' }}">
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

            {{-- ── ADMINISTRACIÓN (solo admins) ── --}}
            @if(in_array(Auth::user()->rol, ['administrador', 'superadmin']))
            <div class="sb-section-label">Administración</div>

            <a href="{{ route('admin.usuarios.index') }}"
               class="sb-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Gestión de usuarios
            </a>

            <a href="{{ route('admin.tours.index') }}"
               class="sb-link {{ request()->routeIs('admin.tours.*') ? 'active' : '' }}">
                <i class="bi bi-briefcase"></i> Tours / Servicios
            </a>

            <a href="{{ route('admin.ciudades.index') }}"
               class="sb-link {{ request()->routeIs('admin.ciudades.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i> Ciudades / Ubigeo
            </a>
            @endif

            {{-- ── CUENTA ── --}}
            <div class="sb-section-label">Cuenta</div>

            <a href="{{ route('profile.edit') }}"
               class="sb-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Configuración
            </a>

            <form method="POST" action="{{ route('logout') }}" style="margin:0;" id="sb-logout-form">
                @csrf
                <button type="submit" class="sb-link"
                        style="width:100%;background:none;border:none;font-family:'Plus Jakarta Sans',sans-serif;text-align:left;">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </button>
            </form>

        </nav>

        {{-- ── FOOTER USUARIO ── --}}
        <div class="sb-footer">
            <div class="sb-user">
                @if(Auth::user()->foto_perfil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_perfil) }}"
                         alt="Foto"
                         style="width:32px;height:32px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                @else
                    <div class="sb-avatar">
                        {{ strtoupper(substr(Auth::user()->nombre ?? Auth::user()->name ?? 'AD', 0, 2)) }}
                    </div>
                @endif
                <div class="sb-user-info">
                    <div class="sb-user-name">
                        {{ Auth::user()->nombre ?? Auth::user()->name }}
                    </div>
                    <div class="sb-user-role">{{ Auth::user()->rol ?? 'Administrador' }}</div>
                </div>
                <button type="button" class="sb-logout" title="Cerrar sesión"
                        onclick="document.getElementById('sb-logout-form').submit()">
                    <i class="bi bi-power"></i>
                </button>
            </div>
        </div>

    </aside>

    {{-- ══════════════ CONTENIDO ══════════════ --}}
    <div class="adv-content">

        <header class="adv-topbar">
            <button class="sb-toggle" id="sb-toggle" onclick="abrirSidebar()" aria-label="Abrir menú">
                <i class="bi bi-list"></i>
            </button>

            <div class="topbar-page-tag">
                <i class="bi bi-grid-1x2" id="topbar-icon"></i>
                <span class="topbar-title">@yield('titulo', 'Panel')</span>
                @hasSection('subtitulo')
                    <span class="topbar-divider">/</span>
                    <span class="topbar-sub">@yield('subtitulo')</span>
                @endif
            </div>

            <div class="topbar-actions">
                <a href="{{ route('reservas.create') }}" class="tb-btn tb-btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    <span class="tb-btn-text">Nueva Reserva</span>
                </a>
                <div class="tb-dropdown" id="tb-dropdown">
                    <button class="tb-user-btn" onclick="toggleDropdown()" aria-label="Menú de usuario">
                        @if(Auth::user()->foto_perfil)
                            <img src="{{ asset('storage/' . Auth::user()->foto_perfil) }}"
                                 alt="Foto"
                                 style="width:30px;height:30px;border-radius:50%;object-fit:cover;">
                        @else
                            <div class="tb-user-avatar">
                                {{ strtoupper(substr(Auth::user()->nombre ?? Auth::user()->name ?? 'AD', 0, 2)) }}
                            </div>
                        @endif
                        <span class="tb-user-name">{{ Auth::user()->nombre ?? Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="tb-dropdown-menu" id="tb-dd-menu">
                        <div class="tb-dd-header">
                            <div class="dd-name">{{ Auth::user()->nombre ?? Auth::user()->name }}</div>
                            <div class="dd-email">{{ Auth::user()->email }}</div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="tb-dd-item">
                            <i class="bi bi-person-circle"></i> Mi perfil
                        </a>
                        <a href="{{ route('profile.edit') }}" class="tb-dd-item">
                            <i class="bi bi-gear"></i> Configuración
                        </a>
                        <div class="tb-dd-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="tb-dd-item danger">
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

{{-- ══ SCRIPTS ══ --}}
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
document.querySelectorAll('#adv-sidebar .sb-link, #adv-sidebar .sb-link-cta').forEach(link => {
    link.addEventListener('click', function() {
        if (window.innerWidth <= 768) cerrarSidebar();
    });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        cerrarSidebar();
        document.getElementById('tb-dd-menu')?.classList.remove('open');
    }
});
</script>

{{-- ══ TOASTS ══ --}}
@if(session('success') || session('error') || session('warning'))
<div id="toast-container" style="position:fixed;top:1.25rem;right:1.25rem;z-index:9999;display:flex;flex-direction:column;gap:.6rem;max-width:calc(100vw - 2.5rem);">
    @if(session('success'))
    <div class="toast-msg" style="display:flex;align-items:center;gap:.6rem;background:white;border:1.5px solid #86efac;border-left:4px solid #16a34a;padding:.75rem 1rem;border-radius:10px;box-shadow:0 4px 20px rgba(0,0,0,.1);font-size:.82rem;font-weight:500;color:#15803d;min-width:280px;max-width:400px;animation:slideIn .25s ease;">
        <i class="bi bi-check-circle-fill" style="font-size:1rem;flex-shrink:0;"></i>
        <span style="flex:1;">{{ session('success') }}</span>
        <button onclick="this.closest('.toast-msg').remove()" style="background:none;border:none;cursor:pointer;color:#16a34a;font-size:.9rem;padding:0;flex-shrink:0;line-height:1;"><i class="bi bi-x-lg"></i></button>
    </div>
    @endif
    @if(session('error'))
    <div class="toast-msg" style="display:flex;align-items:center;gap:.6rem;background:white;border:1.5px solid #fca5a5;border-left:4px solid #dc2626;padding:.75rem 1rem;border-radius:10px;box-shadow:0 4px 20px rgba(0,0,0,.1);font-size:.82rem;font-weight:500;color:#dc2626;min-width:280px;max-width:400px;animation:slideIn .25s ease;">
        <i class="bi bi-exclamation-circle-fill" style="font-size:1rem;flex-shrink:0;"></i>
        <span style="flex:1;">{{ session('error') }}</span>
        <button onclick="this.closest('.toast-msg').remove()" style="background:none;border:none;cursor:pointer;color:#dc2626;font-size:.9rem;padding:0;flex-shrink:0;line-height:1;"><i class="bi bi-x-lg"></i></button>
    </div>
    @endif
    @if(session('warning'))
    <div class="toast-msg" style="display:flex;align-items:center;gap:.6rem;background:white;border:1.5px solid #fcd34d;border-left:4px solid #d97706;padding:.75rem 1rem;border-radius:10px;box-shadow:0 4px 20px rgba(0,0,0,.1);font-size:.82rem;font-weight:500;color:#b45309;min-width:280px;max-width:400px;animation:slideIn .25s ease;">
        <i class="bi bi-exclamation-triangle-fill" style="font-size:1rem;flex-shrink:0;"></i>
        <span style="flex:1;">{{ session('warning') }}</span>
        <button onclick="this.closest('.toast-msg').remove()" style="background:none;border:none;cursor:pointer;color:#d97706;font-size:.9rem;padding:0;flex-shrink:0;line-height:1;"><i class="bi bi-x-lg"></i></button>
    </div>
    @endif
</div>
<script>
setTimeout(() => {
    document.querySelectorAll('.toast-msg').forEach(t => {
        t.style.animation = 'slideOut .3s ease forwards';
        setTimeout(() => t.remove(), 300);
    });
}, 5000);
</script>
@endif

@stack('scripts')
</body>
</html>