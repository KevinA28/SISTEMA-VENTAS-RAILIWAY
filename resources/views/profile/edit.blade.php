<x-app-layout>
@section('titulo', 'Mi perfil')

@section('contenido')
<style>
.pf-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 1.25rem;
    align-items: start;
}
@media(max-width:768px) { .pf-grid { grid-template-columns: 1fr; } }

.pf-card {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 14px;
    overflow: hidden;
}
.pf-card-head {
    background: linear-gradient(135deg, var(--navy) 0%, var(--navy-2) 100%);
    padding: 2rem 1.5rem;
    text-align: center;
}
.pf-avatar-wrap {
    position: relative;
    display: inline-block;
    margin-bottom: 1rem;
}
.pf-avatar {
    width: 90px; height: 90px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,.3);
    display: block;
}
.pf-avatar-initials {
    width: 90px; height: 90px;
    border-radius: 50%;
    background: rgba(255,255,255,.15);
    border: 3px solid rgba(255,255,255,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; font-weight: 800; color: var(--amber);
}
.pf-avatar-btn {
    position: absolute;
    bottom: 2px; right: 2px;
    width: 28px; height: 28px;
    background: var(--amber);
    border: 2px solid #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    font-size: .7rem; color: var(--navy);
    transition: transform .15s;
}
.pf-avatar-btn:hover { transform: scale(1.1); }
.pf-name {
    color: #fff; font-size: 1rem; font-weight: 700; margin-bottom: 3px;
}
.pf-email { color: #93c5fd; font-size: .75rem; }
.pf-rol {
    display: inline-block;
    margin-top: .6rem;
    background: rgba(245,200,66,.2);
    color: var(--amber);
    border: 1px solid rgba(245,200,66,.3);
    border-radius: 999px;
    padding: 2px 12px;
    font-size: .7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
}
.pf-card-body { padding: 1.25rem; }
.pf-meta-item {
    display: flex; align-items: center; gap: .6rem;
    padding: .6rem 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: .82rem; color: var(--ink-3);
}
.pf-meta-item:last-child { border-bottom: none; }
.pf-meta-item i { color: var(--ink-4); font-size: .9rem; width: 18px; text-align: center; }

/* Formularios */
.pf-form-card {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.pf-form-title {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--line);
    font-size: .88rem;
    font-weight: 700;
    color: var(--navy);
    display: flex; align-items: center; gap: .5rem;
}
.pf-form-title i { color: var(--amber-d); }
.pf-form-body { padding: 1.5rem; }
.pf-field { margin-bottom: 1rem; }
.pf-label {
    display: block;
    font-size: .72rem; font-weight: 700;
    color: var(--ink-3);
    text-transform: uppercase; letter-spacing: .05em;
    margin-bottom: .35rem;
}
.pf-input {
    width: 100%;
    border: 1.5px solid var(--line);
    border-radius: 8px;
    padding: .55rem .85rem;
    font-size: .85rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--ink);
    outline: none;
    transition: border-color .15s;
    background: #fff;
}
.pf-input:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(27,58,107,.08); }
.pf-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
@media(max-width:500px) { .pf-grid-2 { grid-template-columns: 1fr; } }
.pf-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .55rem 1.25rem;
    border-radius: 8px;
    font-size: .82rem; font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer; border: none;
    transition: all .15s;
}
.pf-btn-primary { background: var(--navy); color: #fff; }
.pf-btn-primary:hover { background: var(--navy-2); transform: translateY(-1px); }
.pf-btn-danger { background: #fff; color: #dc2626; border: 1.5px solid #fca5a5; }
.pf-btn-danger:hover { background: #fef2f2; }

/* Preview foto */
.foto-preview-wrap {
    display: flex; align-items: center; gap: 1rem;
    margin-bottom: 1rem;
}
.foto-preview {
    width: 64px; height: 64px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--line);
}
.foto-preview-initials {
    width: 64px; height: 64px;
    border-radius: 50%;
    background: #eef3fb;
    border: 2px solid var(--line);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; font-weight: 800; color: var(--navy);
}
.foto-file-input { display: none; }
.foto-file-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .45rem .9rem;
    border: 1.5px solid var(--line);
    border-radius: 8px;
    font-size: .78rem; font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer; background: #fff; color: var(--ink-2);
    transition: all .15s;
}
.foto-file-btn:hover { border-color: var(--navy); color: var(--navy); }
.foto-hint { font-size: .72rem; color: var(--ink-4); margin-top: .3rem; }

.success-msg {
    background: #f0fdf4; border: 1px solid #86efac;
    border-left: 4px solid #16a34a;
    border-radius: 8px; padding: .75rem 1rem;
    font-size: .82rem; color: #15803d;
    display: flex; align-items: center; gap: .5rem;
    margin-bottom: 1rem;
}
</style>

@if(session('status') === 'profile-updated')
<div class="success-msg">
    <i class="bi bi-check-circle-fill"></i> Perfil actualizado correctamente.
</div>
@endif
@if(session('status') === 'password-updated')
<div class="success-msg">
    <i class="bi bi-check-circle-fill"></i> Contraseña actualizada correctamente.
</div>
@endif

<div class="pf-grid">

    {{-- COLUMNA IZQUIERDA — tarjeta de perfil --}}
    <div>
        <div class="pf-card">
            <div class="pf-card-head">
                <div class="pf-avatar-wrap">
                    @if($user->foto_perfil)
                        <img src="{{ asset('storage/' . $user->foto_perfil) }}"
                             alt="Foto de perfil" class="pf-avatar" id="sidebar-foto">
                    @else
                        <div class="pf-avatar-initials" id="sidebar-foto-initials">
                            {{ strtoupper(substr($user->nombre, 0, 1) . substr($user->apellido, 0, 1)) }}
                        </div>
                    @endif
                    <label for="foto_perfil_input" class="pf-avatar-btn" title="Cambiar foto">
                        <i class="bi bi-camera-fill"></i>
                    </label>
                </div>
                <div class="pf-name">{{ $user->nombre_completo }}</div>
                <div class="pf-email">{{ $user->email }}</div>
                <div class="pf-rol">{{ ucfirst($user->rol) }}</div>
            </div>
            <div class="pf-card-body">
                <div class="pf-meta-item">
                    <i class="bi bi-person"></i>
                    {{ $user->nombre }} {{ $user->apellido }}
                </div>
                <div class="pf-meta-item">
                    <i class="bi bi-envelope"></i>
                    {{ $user->email }}
                </div>
                <div class="pf-meta-item">
                    <i class="bi bi-shield-check"></i>
                    {{ ucfirst($user->rol) }}
                </div>
                <div class="pf-meta-item">
                    <i class="bi bi-calendar3"></i>
                    Desde {{ $user->created_at->format('d/m/Y') }}
                </div>
            </div>
        </div>
    </div>

    {{-- COLUMNA DERECHA — formularios --}}
    <div>

        {{-- Información y foto --}}
        <div class="pf-form-card">
            <div class="pf-form-title">
                <i class="bi bi-person-circle"></i> Información personal
            </div>
            <div class="pf-form-body">
                <form method="POST" action="{{ route('profile.update') }}"
                      enctype="multipart/form-data" id="form-perfil">
                    @csrf @method('patch')

                    {{-- Foto --}}
                    <div class="pf-field">
                        <label class="pf-label">Foto de perfil</label>
                        <div class="foto-preview-wrap">
                            @if($user->foto_perfil)
                                <img src="{{ asset('storage/' . $user->foto_perfil) }}"
                                     alt="Foto" class="foto-preview" id="foto-preview-img">
                            @else
                                <div class="foto-preview-initials" id="foto-preview-initials">
                                    {{ strtoupper(substr($user->nombre, 0, 1) . substr($user->apellido, 0, 1)) }}
                                </div>
                                <img src="" alt="Foto" class="foto-preview"
                                     id="foto-preview-img" style="display:none">
                            @endif
                            <div>
                                <input type="file" name="foto_perfil" id="foto_perfil_input"
                                       class="foto-file-input" accept="image/jpg,image/jpeg,image/png,image/webp"
                                       onchange="previewFoto(this)">
                                <label for="foto_perfil_input" class="foto-file-btn">
                                    <i class="bi bi-upload"></i> Subir foto
                                </label>
                                @if($user->foto_perfil)
                                <div style="margin-top:.5rem">
                                    <label style="display:flex;align-items:center;gap:.4rem;font-size:.75rem;color:var(--ink-3);cursor:pointer">
                                        <input type="checkbox" name="eliminar_foto" value="1"> Eliminar foto actual
                                    </label>
                                </div>
                                @endif
                                <div class="foto-hint">JPG, PNG o WEBP. Máximo 2MB.</div>
                            </div>
                        </div>
                    </div>

                    <div class="pf-grid-2">
                        <div class="pf-field">
                            <label class="pf-label">Nombre</label>
                            <input type="text" name="nombre" class="pf-input"
                                   value="{{ old('nombre', $user->nombre) }}" required>
                            @error('nombre') <div style="font-size:.72rem;color:#dc2626;margin-top:.25rem">{{ $message }}</div> @enderror
                        </div>
                        <div class="pf-field">
                            <label class="pf-label">Apellido</label>
                            <input type="text" name="apellido" class="pf-input"
                                   value="{{ old('apellido', $user->apellido) }}" required>
                            @error('apellido') <div style="font-size:.72rem;color:#dc2626;margin-top:.25rem">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="pf-field">
                        <label class="pf-label">Correo electrónico</label>
                        <input type="email" name="email" class="pf-input"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email') <div style="font-size:.72rem;color:#dc2626;margin-top:.25rem">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="pf-btn pf-btn-primary">
                        <i class="bi bi-check-lg"></i> Guardar cambios
                    </button>
                </form>
            </div>
        </div>

        {{-- Cambiar contraseña --}}
        <div class="pf-form-card">
            <div class="pf-form-title">
                <i class="bi bi-lock-fill"></i> Cambiar contraseña
            </div>
            <div class="pf-form-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img    = document.getElementById('foto-preview-img');
            const init   = document.getElementById('foto-preview-initials');
            const sbFoto = document.getElementById('sidebar-foto');
            const sbInit = document.getElementById('sidebar-foto-initials');

            img.src = e.target.result;
            img.style.display = 'block';
            if (init) init.style.display = 'none';

            // Actualizar también el avatar del sidebar de la tarjeta
            if (sbFoto && sbFoto.tagName === 'IMG') {
                sbFoto.src = e.target.result;
            } else if (sbInit) {
                sbInit.style.display = 'none';
                const newImg = document.createElement('img');
                newImg.src = e.target.result;
                newImg.className = 'pf-avatar';
                newImg.id = 'sidebar-foto';
                sbInit.parentNode.insertBefore(newImg, sbInit);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@endsection
</x-app-layout>