{{-- =====================================================================
     UBICACIÓN: resources/views/reservas/partials/_bloque5_politicas.blade.php
     DESCRIPCIÓN: Bloque 5 — Políticas y privacidad.
     Campos: selección de tipo de política, área de texto editable,
     opciones de envío PDF (WhatsApp / correo).
     ===================================================================== --}}
<div class="fb" id="bloque-5">
    <div class="fb-num-badge" id="fb-status-5">5</div>
    <div class="fb-head">
        <div class="fb-ico blue"><i class="bi bi-shield-check"></i></div>
        <div class="fb-titles">
            <h3>Políticas y Privacidad</h3>
            <p>Selecciona, personaliza y envía las políticas aplicables</p>
        </div>
    </div>
    <div class="fb-body">

        <div class="st">Tipo de política</div>

        <div class="alerta blue">
            <i class="bi bi-info-circle ai"></i>
            <div class="at">
                <strong>Selecciona el tipo de servicio para autocompletar las políticas.</strong>
                Puedes editar el contenido antes de guardar.
            </div>
        </div>

        <div class="politica-btns">
            <button type="button" class="btn-politica" id="btn-politica-tour" onclick="cargarPolitica('tours')">
                <i class="bi bi-map"></i> Políticas &ndash; Tours
            </button>
            <button type="button" class="btn-politica" id="btn-politica-viaje" onclick="cargarPolitica('viajes')">
                <i class="bi bi-airplane"></i> Políticas &ndash; Viajes
            </button>
        </div>

        <div class="field">
            <label class="lbl" for="politica_descripcion">
                Descripción de Políticas <span class="req">*</span>
            </label>
            <div style="position:relative">
                <span class="politica-badge" id="politica-loaded-badge">
                    <i class="bi bi-check-circle-fill"></i> Cargado
                </span>
                <textarea name="politica_descripcion"
                          id="politica_descripcion"
                          class="fi" rows="10"
                          placeholder="Selecciona un tipo de política arriba, o escribe el contenido manualmente..."
                          required
                          data-validate="required"
                          data-bloque="5"
                          oninput="updateProgressSteps()">{{ old('politica_descripcion') }}</textarea>
            </div>
            <div class="fhint">
                <i class="bi bi-info-circle me-1"></i>
                Puedes editar el contenido antes de guardar.
            </div>
            @error('politica_descripcion')
                <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        <input type="hidden" name="politica_tipo" id="politica_tipo" value="{{ old('politica_tipo') }}">

        {{-- ── ENVÍO PDF ── --}}
<div class="st">Enviar al cliente al guardar</div>

<div class="alerta blue" style="margin-bottom:.75rem">
    <i class="bi bi-info-circle ai"></i>
    <div class="at">
        Se enviará al número y correo del titular registrado en el bloque 2.
    </div>
</div>

{{-- Datos del titular (lectura en tiempo real) --}}
<div style="display:flex;gap:.65rem;flex-wrap:wrap;padding:.65rem .8rem;
            background:var(--adv-slate-l,#f8fafc);border:1.5px solid var(--border,#e2e8f0);
            border-radius:var(--r,8px);margin-bottom:.85rem">
    <div style="display:flex;align-items:center;gap:.4rem;font-size:.75rem;font-weight:600;color:var(--ink-2)">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="#25d366">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <span id="notif-tel-display">—</span>
    </div>
    <div style="width:1px;background:var(--border,#e2e8f0)"></div>
    <div style="display:flex;align-items:center;gap:.4rem;font-size:.75rem;font-weight:600;color:var(--ink-2)">
        <i class="bi bi-envelope-fill" style="color:var(--adv-blue);font-size:.85rem"></i>
        <span id="notif-email-display">—</span>
    </div>
</div>

<div class="notif-row">
    <label class="notif-item checked" id="p-wa">
        <input type="checkbox" name="notif_whatsapp" value="1"
               checked id="cb-wa"
               onchange="this.closest('label').classList.toggle('checked', this.checked)">
        <span class="notif-box"><i class="bi bi-check2"></i></span>
        <i class="bi bi-whatsapp" style="color:#25d366;font-size:1rem"></i>
        <span>Enviar por WhatsApp</span>
    </label>
    <label class="notif-item checked" id="p-em">
        <input type="checkbox" name="notif_email" value="1"
               checked id="cb-em"
               onchange="this.closest('label').classList.toggle('checked', this.checked)">
        <span class="notif-box"><i class="bi bi-check2"></i></span>
        <i class="bi bi-envelope-fill" style="color:var(--adv-blue);font-size:.9rem"></i>
        <span>Enviar por correo</span>
    </label>
</div>
<div class="fhint" style="margin-top:.5rem">
            <i class="bi bi-info-circle me-1"></i>
            Se enviará automáticamente al guardar la reserva si el titular tiene número y correo registrados.
        </div>

    </div>{{-- fin fb-body --}}
</div>{{-- fin bloque-5 --}}