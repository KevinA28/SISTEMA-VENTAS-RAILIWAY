<nav style="background:#1a2744;border-bottom:2px solid #f59e0b;font-family:'DM Sans',sans-serif;">
    <div style="max-width:1280px;margin:0 auto;padding:0 1.5rem;display:flex;justify-content:space-between;align-items:center;height:64px;">

        {{-- Logo --}}
        <div style="display:flex;align-items:center;gap:2.5rem;">
            <a href="{{ route('dashboard') }}" style="display:flex;align-items:center;gap:.6rem;text-decoration:none;">
                <div style="background:#f59e0b;width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <span style="color:#1a2744;font-size:.7rem;font-weight:900;letter-spacing:.02em;">ADV</span>
                </div>
                <div>
                    <div style="color:white;font-weight:800;font-size:1rem;line-height:1;">Adventur</div>
                    <div style="color:#f59e0b;font-size:.62rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;">Sistema de Reservas</div>
                </div>
            </a>

            {{-- Links principales --}}
            <div style="display:flex;align-items:center;gap:.2rem;">
                <a href="{{ route('dashboard') }}"
                   style="color:{{ request()->routeIs('dashboard') ? '#f59e0b' : 'rgba(255,255,255,.65)' }};
                          background:{{ request()->routeIs('dashboard') ? 'rgba(245,158,11,.12)' : 'transparent' }};
                          border-bottom:{{ request()->routeIs('dashboard') ? '2px solid #f59e0b' : '2px solid transparent' }};
                          padding:8px 14px;font-size:.85rem;font-weight:600;
                          text-decoration:none;display:flex;align-items:center;gap:.4rem;
                          height:64px;box-sizing:border-box;transition:all .15s;">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('reservas.index') }}"
                   style="color:{{ request()->routeIs('reservas.*') ? '#f59e0b' : 'rgba(255,255,255,.65)' }};
                          background:{{ request()->routeIs('reservas.*') ? 'rgba(245,158,11,.12)' : 'transparent' }};
                          border-bottom:{{ request()->routeIs('reservas.*') ? '2px solid #f59e0b' : '2px solid transparent' }};
                          padding:8px 14px;font-size:.85rem;font-weight:600;
                          text-decoration:none;display:flex;align-items:center;gap:.4rem;
                          height:64px;box-sizing:border-box;transition:all .15s;">
                    <i class="bi bi-calendar-check"></i> Reservas
                </a>
                <a href="{{ route('clientes.index') }}"
                   style="color:{{ request()->routeIs('clientes.*') ? '#f59e0b' : 'rgba(255,255,255,.65)' }};
                          background:{{ request()->routeIs('clientes.*') ? 'rgba(245,158,11,.12)' : 'transparent' }};
                          border-bottom:{{ request()->routeIs('clientes.*') ? '2px solid #f59e0b' : '2px solid transparent' }};
                          padding:8px 14px;font-size:.85rem;font-weight:600;
                          text-decoration:none;display:flex;align-items:center;gap:.4rem;
                          height:64px;box-sizing:border-box;transition:all .15s;">
                    <i class="bi bi-people"></i> Clientes
                </a>

                {{-- Estadísticas --}}
                <a href="{{ route('estadisticas.index') }}"
                   style="color:{{ request()->routeIs('estadisticas.*') ? '#f59e0b' : 'rgba(255,255,255,.65)' }};
                          background:{{ request()->routeIs('estadisticas.*') ? 'rgba(245,158,11,.12)' : 'transparent' }};
                          border-bottom:{{ request()->routeIs('estadisticas.*') ? '2px solid #f59e0b' : '2px solid transparent' }};
                          padding:8px 14px;font-size:.85rem;font-weight:600;
                          text-decoration:none;display:flex;align-items:center;gap:.4rem;
                          height:64px;box-sizing:border-box;transition:all .15s;">
                    <i class="bi bi-graph-up-arrow"></i> Estadísticas
                </a>

                {{-- Administración — solo admins, en el navbar --}}
                @if(in_array(Auth::user()->rol, ['administrador', 'superadmin']))
                <div x-data="{ openAdmin: false }" style="position:relative;height:64px;display:flex;align-items:center;">
                    <button @click="openAdmin = !openAdmin"
                            style="color:{{ request()->routeIs('admin.*') ? '#f59e0b' : 'rgba(255,255,255,.65)' }};
                                   background:{{ request()->routeIs('admin.*') ? 'rgba(245,158,11,.12)' : 'transparent' }};
                                   border-bottom:{{ request()->routeIs('admin.*') ? '2px solid #f59e0b' : '2px solid transparent' }};
                                   border-top:none;border-left:none;border-right:none;
                                   padding:8px 14px;font-size:.85rem;font-weight:600;cursor:pointer;
                                   font-family:'DM Sans',sans-serif;display:flex;align-items:center;gap:.4rem;
                                   height:64px;box-sizing:border-box;transition:all .15s;">
                        <i class="bi bi-shield-lock"></i> Admin
                        <i class="bi bi-chevron-down" style="font-size:.6rem;opacity:.7;"></i>
                    </button>
                    <div x-show="openAdmin" @click.away="openAdmin = false"
                         style="position:absolute;left:0;top:calc(100% + 0px);background:white;
                                border:1px solid #e5e7eb;border-radius:10px;min-width:210px;
                                box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:50;overflow:hidden;">
                        <div style="padding:8px 14px;background:#f9fafb;border-bottom:1px solid #f3f4f6;">
                            <div style="font-size:.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;">Administración</div>
                        </div>
                        <a href="{{ route('admin.usuarios.index') }}"
                           style="display:flex;align-items:center;gap:.6rem;padding:10px 14px;
                                  font-size:.84rem;color:{{ request()->routeIs('admin.usuarios.*') ? '#1a2744' : '#374151' }};
                                  font-weight:{{ request()->routeIs('admin.usuarios.*') ? '700' : '400' }};
                                  background:{{ request()->routeIs('admin.usuarios.*') ? '#fef3c7' : 'transparent' }};
                                  text-decoration:none;transition:background .12s;">
                            <i class="bi bi-people-fill" style="color:#6b7280;font-size:.85rem;"></i> Gestión de usuarios
                        </a>
                        <a href="{{ route('admin.tours.index') }}"
                           style="display:flex;align-items:center;gap:.6rem;padding:10px 14px;
                                  font-size:.84rem;color:{{ request()->routeIs('admin.tours.*') ? '#1a2744' : '#374151' }};
                                  font-weight:{{ request()->routeIs('admin.tours.*') ? '700' : '400' }};
                                  background:{{ request()->routeIs('admin.tours.*') ? '#fef3c7' : 'transparent' }};
                                  text-decoration:none;transition:background .12s;">
                            <i class="bi bi-briefcase" style="color:#6b7280;font-size:.85rem;"></i> Tours / Servicios
                        </a>
                        <a href="{{ route('admin.ciudades.index') }}"
                           style="display:flex;align-items:center;gap:.6rem;padding:10px 14px;
                                  font-size:.84rem;color:{{ request()->routeIs('admin.ciudades.*') ? '#1a2744' : '#374151' }};
                                  font-weight:{{ request()->routeIs('admin.ciudades.*') ? '700' : '400' }};
                                  background:{{ request()->routeIs('admin.ciudades.*') ? '#fef3c7' : 'transparent' }};
                                  text-decoration:none;transition:background .12s;">
                            <i class="bi bi-geo-alt" style="color:#6b7280;font-size:.85rem;"></i> Ciudades / Ubigeo
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Derecha: botón + usuario --}}
        <div style="display:flex;align-items:center;gap:.75rem;">
            <a href="{{ route('reservas.create') }}"
               style="background:#f59e0b;color:#1a2744;padding:8px 16px;border-radius:8px;
                      font-size:.82rem;font-weight:800;text-decoration:none;
                      display:flex;align-items:center;gap:.4rem;letter-spacing:.01em;">
                <i class="bi bi-plus-lg"></i> Nueva Reserva
            </a>

            <div x-data="{ open: false }" style="position:relative;">
                <button @click="open = !open"
                        style="display:flex;align-items:center;gap:.5rem;
                               background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);
                               color:white;padding:7px 13px;border-radius:8px;
                               font-size:.84rem;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;">
                    <div style="width:28px;height:28px;background:#f59e0b;border-radius:50%;
                                display:flex;align-items:center;justify-content:center;
                                color:#1a2744;font-size:.7rem;font-weight:900;">
                        {{ strtoupper(substr(Auth::user()->nombre ?? Auth::user()->name, 0, 2)) }}
                    </div>
                    {{ Auth::user()->nombre ?? Auth::user()->name }}
                    <i class="bi bi-chevron-down" style="font-size:.6rem;opacity:.7;"></i>
                </button>

                <div x-show="open" @click.away="open = false"
                     style="position:absolute;right:0;top:calc(100% + 8px);background:white;
                            border:1px solid #e5e7eb;border-radius:10px;min-width:210px;
                            box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:50;overflow:hidden;">
                    <div style="padding:10px 14px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
                        <div style="font-size:.82rem;font-weight:700;color:#0f1923;">{{ Auth::user()->nombre ?? Auth::user()->name }}</div>
                        <div style="font-size:.72rem;color:#9ca3af;">{{ Auth::user()->email }}</div>
                        @if(Auth::user()->rol === 'superadmin')
                            <div style="font-size:.68rem;font-weight:700;color:#86198f;margin-top:2px;text-transform:uppercase;letter-spacing:.06em;">
                                <i class="bi bi-star-fill"></i> Superadmin
                            </div>
                        @elseif(Auth::user()->rol === 'administrador')
                            <div style="font-size:.68rem;font-weight:700;color:#f59e0b;margin-top:2px;text-transform:uppercase;letter-spacing:.06em;">
                                <i class="bi bi-shield-check"></i> Administrador
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('profile.edit') }}"
                       style="display:flex;align-items:center;gap:.5rem;padding:10px 14px;
                              font-size:.84rem;color:#374151;text-decoration:none;">
                        <i class="bi bi-gear" style="color:#6b7280;"></i> Configuración
                    </a>
                    <div style="border-top:1px solid #f3f4f6;"></div>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit"
                                style="width:100%;display:flex;align-items:center;gap:.5rem;
                                       padding:10px 14px;font-size:.84rem;color:#dc2626;
                                       background:none;border:none;cursor:pointer;text-align:left;
                                       font-family:'DM Sans',sans-serif;">
                            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>