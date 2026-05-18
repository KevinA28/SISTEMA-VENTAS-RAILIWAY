@extends('layouts.app')

@section('titulo', 'Gestión de usuarios')

@section('contenido')
<style>
.gu-card {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 14px;
    padding: 1.5rem;
    margin-bottom: 1.25rem;
}
.gu-card-title {
    font-size: .95rem;
    font-weight: 700;
    color: var(--navy);
    display: flex;
    align-items: center;
    gap: .5rem;
    margin-bottom: 1.25rem;
}
.gu-card-title i { font-size: 1.1rem; color: var(--amber-d); }
.gu-badge-count {
    background: var(--line);
    color: var(--ink-3);
    font-size: .68rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 999px;
}
.inv-form {
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
    align-items: flex-end;
    background: #f8fafc;
    border: 1px solid var(--line);
    border-radius: 10px;
    padding: 1.25rem;
}
.inv-field { display: flex; flex-direction: column; gap: .3rem; }
.inv-field label {
    font-size: .72rem;
    font-weight: 700;
    color: var(--ink-3);
    text-transform: uppercase;
    letter-spacing: .05em;
}
.inv-input {
    border: 1.5px solid var(--line);
    border-radius: 8px;
    padding: .5rem .75rem;
    font-size: .83rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--ink);
    outline: none;
    transition: border-color .15s;
    background: #fff;
}
.inv-input:focus { border-color: var(--navy); }
.inv-input-email { width: 260px; }
.inv-btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .55rem 1.1rem;
    background: var(--navy);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: .82rem;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer;
    transition: background .15s, transform .15s;
}
.inv-btn:hover { background: var(--navy-2); transform: translateY(-1px); }
.gu-table { width: 100%; border-collapse: collapse; }
.gu-table th {
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--ink-4);
    padding: .5rem .75rem;
    border-bottom: 1.5px solid var(--line);
    text-align: left;
    white-space: nowrap;
}
.gu-table td {
    padding: .75rem;
    border-bottom: 1px solid #f1f5f9;
    font-size: .83rem;
    color: var(--ink-2);
    vertical-align: middle;
}
.gu-table tr:last-child td { border-bottom: none; }
.gu-table tr:hover td { background: #f8fafc; }
.gu-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 800;
    flex-shrink: 0;
}
.gu-user-cell { display: flex; align-items: center; gap: .65rem; }
.gu-user-name { font-weight: 600; color: var(--ink); font-size: .83rem; }
.gu-user-email { font-size: .72rem; color: var(--ink-4); margin-top: 1px; }
.rol-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: 3px 10px; border-radius: 999px;
    font-size: .7rem; font-weight: 700;
}
.rol-admin    { background: #ede9fe; color: #6d28d9; }
.rol-ventas   { background: #dbeafe; color: #1d4ed8; }
.rol-operador { background: #dcfce7; color: #15803d; }
.estado-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: 3px 10px; border-radius: 999px;
    font-size: .7rem; font-weight: 700;
}
.estado-activo   { background: #dcfce7; color: #15803d; }
.estado-inactivo { background: #fee2e2; color: #dc2626; }
.gu-actions { display: flex; gap: .4rem; align-items: center; }
.gu-btn {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: 4px 10px; border-radius: 6px;
    font-size: .72rem; font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer; border: 1.5px solid;
    transition: all .15s; background: #fff;
}
.gu-btn-warn   { border-color: #fed7aa; color: #c2410c; }
.gu-btn-warn:hover   { background: #fff7ed; }
.gu-btn-green  { border-color: #86efac; color: #15803d; }
.gu-btn-green:hover  { background: #f0fdf4; }
.gu-btn-danger { border-color: #fca5a5; color: #dc2626; }
.gu-btn-danger:hover { background: #fef2f2; }
.inv-row td { background: #fffbea; }
.inv-row:hover td { background: #fef9c3 !important; }
.tu-cuenta { font-size: .72rem; color: var(--ink-4); display: flex; align-items: center; gap: .3rem; }

/* ── MODAL ── */
.gu-modal-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(15,23,42,.5);
    backdrop-filter: blur(3px);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}
.gu-modal-overlay.open { display: flex; animation: modalFadeIn .18s ease; }
@keyframes modalFadeIn { from{opacity:0} to{opacity:1} }
.gu-modal {
    background: #fff;
    border-radius: 16px;
    padding: 0;
    width: 100%;
    max-width: 420px;
    margin: 1rem;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
    animation: modalSlideIn .2s ease;
    overflow: hidden;
}
@keyframes modalSlideIn { from{opacity:0;transform:translateY(-12px)} to{opacity:1;transform:translateY(0)} }
.gu-modal-header {
    padding: 1.25rem 1.5rem 1rem;
    border-bottom: 1px solid var(--line);
    display: flex;
    align-items: center;
    gap: .75rem;
}
.gu-modal-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.gu-modal-icon.danger { background: #fef2f2; color: #dc2626; }
.gu-modal-icon.warning { background: #fff7ed; color: #c2410c; }
.gu-modal-title { font-size: .95rem; font-weight: 700; color: var(--ink); }
.gu-modal-body { padding: 1rem 1.5rem 1.25rem; }
.gu-modal-desc { font-size: .85rem; color: var(--ink-3); line-height: 1.7; }
.gu-modal-name {
    display: inline-block;
    background: var(--line-2);
    border: 1px solid var(--line);
    border-radius: 6px;
    padding: 2px 10px;
    font-weight: 700;
    color: var(--ink);
    margin: 4px 0;
}
.gu-modal-footer {
    padding: .75rem 1.5rem 1.25rem;
    display: flex;
    justify-content: flex-end;
    gap: .6rem;
}
.gu-modal-cancel {
    padding: .5rem 1.1rem;
    border: 1.5px solid var(--line);
    border-radius: 8px;
    background: #fff;
    font-size: .82rem;
    font-weight: 600;
    color: var(--ink-3);
    cursor: pointer;
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: all .15s;
}
.gu-modal-cancel:hover { background: var(--line-2); color: var(--ink); }
.gu-modal-confirm {
    padding: .5rem 1.1rem;
    border: none;
    border-radius: 8px;
    font-size: .82rem;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: all .15s;
}
.gu-modal-confirm.danger { background: #dc2626; color: #fff; }
.gu-modal-confirm.danger:hover { background: #b91c1c; }
.gu-modal-confirm.warning { background: #ea580c; color: #fff; }
.gu-modal-confirm.warning:hover { background: #c2410c; }
</style>

{{-- MODAL PERSONALIZADO --}}
<div class="gu-modal-overlay" id="gu-modal-overlay">
    <div class="gu-modal">
        <div class="gu-modal-header">
            <div class="gu-modal-icon" id="modal-icon">
                <i id="modal-icon-i" class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="gu-modal-title" id="modal-title">Confirmar acción</div>
        </div>
        <div class="gu-modal-body">
            <p class="gu-modal-desc" id="modal-desc"></p>
        </div>
        <div class="gu-modal-footer">
            <button class="gu-modal-cancel" onclick="cerrarModal()">
                <i class="bi bi-x-lg"></i> Cancelar
            </button>
            <button class="gu-modal-confirm" id="modal-confirm-btn" onclick="confirmarModal()">
                Confirmar
            </button>
        </div>
    </div>
</div>

{{-- INVITAR --}}
<div class="gu-card">
    <div class="gu-card-title">
        <i class="bi bi-person-plus-fill"></i>
        Invitar nuevo usuario
    </div>
    <form method="POST" action="{{ route('admin.usuarios.invitar') }}" class="inv-form">
        @csrf
        <div class="inv-field">
            <label>Correo electrónico</label>
            <input type="email" name="email" required
                   class="inv-input inv-input-email"
                   placeholder="correo@ejemplo.com"
                   value="{{ old('email') }}">
        </div>
        <div class="inv-field">
            <label>Rol</label>
            <select name="rol" required class="inv-input">
                <option value="ventas"        {{ old('rol') == 'ventas'        ? 'selected' : '' }}>Ventas</option>
                <option value="operador"      {{ old('rol') == 'operador'      ? 'selected' : '' }}>Operador</option>
                <option value="administrador" {{ old('rol') == 'administrador' ? 'selected' : '' }}>Administrador</option>
            </select>
        </div>
        <button type="submit" class="inv-btn">
            <i class="bi bi-send"></i> Enviar invitación
        </button>
    </form>
</div>

{{-- INVITACIONES PENDIENTES --}}
@if ($invitaciones->count())
<div class="gu-card">
    <div class="gu-card-title">
        <i class="bi bi-hourglass-split"></i>
        Invitaciones pendientes
        <span class="gu-badge-count">{{ $invitaciones->count() }}</span>
    </div>
    <div style="overflow-x:auto">
        <table class="gu-table">
            <thead>
                <tr>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Invitado por</th>
                    <th>Expira</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invitaciones as $inv)
                <tr class="inv-row">
                    <td>
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <i class="bi bi-envelope" style="color:var(--amber-d)"></i>
                            {{ $inv->email }}
                        </div>
                    </td>
                    <td>
                        <span class="rol-badge {{ $inv->rol === 'administrador' ? 'rol-admin' : ($inv->rol === 'ventas' ? 'rol-ventas' : 'rol-operador') }}">
                            {{ ucfirst($inv->rol) }}
                        </span>
                    </td>
                    <td style="color:var(--ink-3)">{{ $inv->invitadoPor->nombre_completo ?? '—' }}</td>
                    <td style="color:var(--ink-3)">
                        <i class="bi bi-clock" style="font-size:.75rem"></i>
                        {{ $inv->expires_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.invitaciones.cancelar', $inv) }}" id="form-inv-{{ $inv->id }}">
                            @csrf @method('DELETE')
                            <button type="button" class="gu-btn gu-btn-danger"
                                    onclick="abrirModal('cancelar-inv', 'form-inv-{{ $inv->id }}', '{{ $inv->email }}')">
                                <i class="bi bi-x-lg"></i> Cancelar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- USUARIOS --}}
<div class="gu-card">
    <div class="gu-card-title">
        <i class="bi bi-people-fill"></i>
        Usuarios del sistema
        <span class="gu-badge-count">{{ $usuarios->count() }}</span>
    </div>
    <div style="overflow-x:auto">
        <table class="gu-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                @php
                    $iniciales = strtoupper(substr($usuario->nombre, 0, 1) . substr($usuario->apellido, 0, 1));
                    $colores   = ['administrador' => ['#ede9fe','#6d28d9'], 'ventas' => ['#dbeafe','#1d4ed8'], 'operador' => ['#dcfce7','#15803d']];
                    $color     = $colores[$usuario->rol] ?? ['#f1f5f9','#475569'];
                @endphp
                <tr>
                    <td>
                        <div class="gu-user-cell">
                            <div class="gu-avatar" style="background:{{ $color[0] }};color:{{ $color[1] }}">
                                {{ $iniciales }}
                            </div>
                            <div>
                                <div class="gu-user-name">
                                    {{ $usuario->nombre_completo }}
                                    @if($usuario->id === auth()->id())
                                        <span style="font-size:.65rem;color:var(--ink-4);font-weight:400">(tú)</span>
                                    @endif
                                </div>
                                <div class="gu-user-email">{{ $usuario->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="rol-badge {{ $usuario->rol === 'administrador' ? 'rol-admin' : ($usuario->rol === 'ventas' ? 'rol-ventas' : 'rol-operador') }}">
                            @if($usuario->rol === 'administrador') <i class="bi bi-shield-fill"></i>
                            @elseif($usuario->rol === 'ventas') <i class="bi bi-bag"></i>
                            @else <i class="bi bi-tools"></i>
                            @endif
                            {{ ucfirst($usuario->rol) }}
                        </span>
                    </td>
                    <td>
                        <span class="estado-badge {{ $usuario->activo ? 'estado-activo' : 'estado-inactivo' }}">
                            <i class="bi bi-{{ $usuario->activo ? 'check-circle-fill' : 'x-circle-fill' }}"></i>
                            {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        @if ($usuario->id !== auth()->id())
                            <div class="gu-actions">
                                <form method="POST"
                                      action="{{ route('admin.usuarios.toggleActivo', $usuario) }}"
                                      id="form-toggle-{{ $usuario->id }}">
                                    @csrf @method('PATCH')
                                    <button type="button"
                                            class="gu-btn {{ $usuario->activo ? 'gu-btn-warn' : 'gu-btn-green' }}"
                                            onclick="abrirModal('{{ $usuario->activo ? 'desactivar' : 'activar' }}', 'form-toggle-{{ $usuario->id }}', '{{ $usuario->nombre_completo }}')">
                                        <i class="bi bi-{{ $usuario->activo ? 'pause-circle' : 'play-circle' }}"></i>
                                        {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                                <form method="POST"
                                      action="{{ route('admin.usuarios.destroy', $usuario) }}"
                                      id="form-del-{{ $usuario->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            class="gu-btn gu-btn-danger"
                                            onclick="abrirModal('eliminar', 'form-del-{{ $usuario->id }}', '{{ $usuario->nombre_completo }}')">
                                        <i class="bi bi-trash3"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="tu-cuenta">
                                <i class="bi bi-shield-check"></i> Tu cuenta
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
let _modalFormId = null;

function abrirModal(tipo, formId, nombre) {
    _modalFormId = formId;

    const overlay   = document.getElementById('gu-modal-overlay');
    const icon      = document.getElementById('modal-icon');
    const iconI     = document.getElementById('modal-icon-i');
    const title     = document.getElementById('modal-title');
    const desc      = document.getElementById('modal-desc');
    const confirmBtn = document.getElementById('modal-confirm-btn');

    if (tipo === 'eliminar') {
        icon.className      = 'gu-modal-icon danger';
        iconI.className     = 'bi bi-trash3-fill';
        title.textContent   = 'Eliminar usuario';
        desc.innerHTML      = `¿Estás seguro de que quieres eliminar a <span class="gu-modal-name">${nombre}</span>?<br>Esta acción no se puede deshacer.`;
        confirmBtn.className = 'gu-modal-confirm danger';
        confirmBtn.innerHTML = '<i class="bi bi-trash3"></i> Sí, eliminar';

    } else if (tipo === 'desactivar') {
        icon.className      = 'gu-modal-icon warning';
        iconI.className     = 'bi bi-pause-circle-fill';
        title.textContent   = 'Desactivar usuario';
        desc.innerHTML      = `¿Desactivar a <span class="gu-modal-name">${nombre}</span>?<br>No podrá iniciar sesión hasta que lo reactives.`;
        confirmBtn.className = 'gu-modal-confirm warning';
        confirmBtn.innerHTML = '<i class="bi bi-pause-circle"></i> Sí, desactivar';

    } else if (tipo === 'activar') {
        icon.className      = 'gu-modal-icon';
        icon.style.background = '#f0fdf4';
        icon.style.color    = '#15803d';
        iconI.className     = 'bi bi-play-circle-fill';
        title.textContent   = 'Activar usuario';
        desc.innerHTML      = `¿Activar a <span class="gu-modal-name">${nombre}</span>?<br>Podrá iniciar sesión nuevamente.`;
        confirmBtn.className = 'gu-modal-confirm';
        confirmBtn.style.background = '#15803d';
        confirmBtn.style.color = '#fff';
        confirmBtn.innerHTML = '<i class="bi bi-play-circle"></i> Sí, activar';

    } else if (tipo === 'cancelar-inv') {
        icon.className      = 'gu-modal-icon danger';
        iconI.className     = 'bi bi-x-circle-fill';
        title.textContent   = 'Cancelar invitación';
        desc.innerHTML      = `¿Cancelar la invitación enviada a <span class="gu-modal-name">${nombre}</span>?<br>El enlace dejará de funcionar.`;
        confirmBtn.className = 'gu-modal-confirm danger';
        confirmBtn.innerHTML = '<i class="bi bi-x-lg"></i> Sí, cancelar';
    }

    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('gu-modal-overlay').classList.remove('open');
    document.body.style.overflow = '';
    _modalFormId = null;
}

function confirmarModal() {
    if (_modalFormId) {
        document.getElementById(_modalFormId).submit();
    }
}

document.getElementById('gu-modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarModal();
});
</script>
@endpush