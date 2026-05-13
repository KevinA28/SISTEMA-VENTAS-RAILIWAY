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
                Se enviará al correo del titular registrado en el bloque 2.
            </div>
        </div>

        {{-- Correo del titular (lectura en tiempo real) --}}
        <div style="display:flex;align-items:center;gap:.5rem;padding:.65rem .8rem;
                    background:var(--adv-slate-l,#f8fafc);border:1.5px solid var(--border,#e2e8f0);
                    border-radius:var(--r,8px);margin-bottom:.85rem">
            <i class="bi bi-envelope-fill" style="color:var(--adv-blue);font-size:.9rem"></i>
            <span id="notif-email-display" style="font-size:.75rem;font-weight:600;color:var(--ink-2)">—</span>
        </div>

        {{-- Solo email, WhatsApp eliminado --}}
        <input type="hidden" name="notif_whatsapp" value="0">

        <div class="notif-row">
            <label class="notif-item checked" id="p-em">
                <input type="checkbox" name="notif_email" value="1"
                       checked id="cb-em"
                       onchange="this.closest('label').classList.toggle('checked', this.checked)">
                <span class="notif-box"><i class="bi bi-check2"></i></span>
                <i class="bi bi-envelope-fill" style="color:var(--adv-blue);font-size:.9rem"></i>
                <span>Enviar confirmación por correo</span>
            </label>
        </div>
        <div class="fhint" style="margin-top:.5rem">
            <i class="bi bi-info-circle me-1"></i>
            Se enviará el PDF de confirmación automáticamente al guardar si el titular tiene correo registrado.
        </div>

    </div>{{-- fin fb-body --}}
</div>{{-- fin bloque-5 --}}