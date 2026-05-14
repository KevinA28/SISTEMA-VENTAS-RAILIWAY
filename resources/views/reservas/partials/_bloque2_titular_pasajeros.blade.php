{{-- =====================================================================
     UBICACIÓN: resources/views/reservas/partials/_bloque2_titular_pasajeros.blade.php
     CAMBIOS:
       - Eliminado switch "Solo viaja el titular" (no tenía sentido)
       - Seguro de salud visible SIN necesidad de abrir el panel de salud
       - discapacidades y seguro_salud ahora se envían correctamente
       - pasajeros adicionales también tienen seguro visible
     ===================================================================== --}}
<div class="fb" id="bloque-2">
    <div class="fb-num-badge" id="fb-status-2">2</div>
    <div class="fb-head">
        <div class="fb-ico blue"><i class="bi bi-people"></i></div>
        <div class="fb-titles">
            <h3>Titular y pasajeros</h3>
            <p>Datos personales, contacto, salud y grupo de viaje</p>
        </div>
    </div>
    {{-- Campos hidden para garantizar que salud del titular siempre se envía --}}
    <input type="hidden" name="titular_tiene_alergias"   id="hid_titular_tiene_alergias"   value="{{ old('titular_tiene_alergias', 'no') }}">
    <input type="hidden" name="titular_alergias_detalle" id="hid_titular_alergias_detalle" value="{{ old('titular_alergias_detalle') }}">
    <input type="hidden" name="titular_restricciones"    id="hid_titular_restricciones"    value="{{ old('titular_restricciones') }}">
    <input type="hidden" name="titular_obs_medicas"      id="hid_titular_obs_medicas"      value="{{ old('titular_obs_medicas') }}">
    <input type="hidden" name="titular_discapacidades"   id="hid_titular_discapacidades"   value="{{ old('titular_discapacidades') }}">
    <input type="hidden" name="titular_discapacidad_otro" id="hid_titular_discapacidad_otro" value="{{ old('titular_discapacidad_otro') }}">
    <input type="hidden" name="titular_seguro_salud"     id="hid_titular_seguro_salud"     value="{{ old('titular_seguro_salud') }}">
    <div class="fb-body">
        <input type="hidden" name="cliente_id" id="cliente_id" value="{{ old('cliente_id') }}">

        {{-- ── IDENTIFICACIÓN ── --}}
        <div class="st">Identificación del titular</div>
        <div class="g2">
            <div class="field">
                <label class="lbl" for="titular_tipo_documento">Tipo de doc.</label>
                <select name="titular_tipo_documento" id="titular_tipo_documento"
                        class="fi" onchange="onTipoDocChange()">
                    <option value="DNI"       {{ old('titular_tipo_documento','DNI') == 'DNI'       ? 'selected' : '' }}>DNI</option>
                    <option value="CE"        {{ old('titular_tipo_documento')       == 'CE'        ? 'selected' : '' }}>C. Extranjería</option>
                    <option value="PASAPORTE" {{ old('titular_tipo_documento')       == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                    <option value="RUC"       {{ old('titular_tipo_documento')       == 'RUC'       ? 'selected' : '' }}>RUC</option>
                </select>
            </div>
            <div class="field">
                <label class="lbl" for="titular_numero_documento">
                    Número de documento <span class="req">*</span>
                </label>
                <div class="dni-wrap">
                    <div class="dni-row">
                        <span class="ia"><i class="bi bi-card-text"></i></span>
                        <input type="text"
                               name="titular_numero_documento"
                               id="titular_numero_documento"
                               value="{{ old('titular_numero_documento') }}"
                               class="fi" placeholder="Ingresa el número"
                               maxlength="12" inputmode="numeric"
                               oninput="onDocInput(this);onTitularChange()"
                               data-bloque="2">
                        <button type="button" class="btn-dni-lookup" id="btn-lookup" onclick="buscarPorDoc()">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                    <div class="dni-result" id="dni-result"></div>
                </div>
                <div class="fhint">Busca para autocompletar desde SUNAT/RENIEC</div>
            </div>
        </div>

        {{-- ── DATOS PERSONALES ── --}}
        <div class="st">Datos personales</div>
        <div class="g2">
            <div class="field">
                <label class="lbl" for="titular_nombre">
                    Nombre completo <span class="req">*</span>
                </label>
                <div class="ig {{ $errors->has('titular_nombre') ? 'err-group' : '' }}">
                    <span class="ia"><i class="bi bi-person"></i></span>
                    <input type="text"
                           name="titular_nombre"
                           id="titular_nombre"
                           value="{{ old('titular_nombre') }}"
                           class="fi {{ $errors->has('titular_nombre') ? 'err' : '' }}"
                           placeholder="NOMBRES Y APELLIDOS COMPLETOS"
                           required maxlength="200"
                           oninput="this.value=this.value.toUpperCase();onTitularChange();updateProgressSteps()"
                           data-validate="required" data-bloque="2">
                </div>
                @error('titular_nombre')
                    <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            <div class="field">
                <label class="lbl" for="titular_fecha_nacimiento">Fecha de nacimiento</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-calendar2-heart"></i></span>
                    <input type="text" id="titular_fecha_nacimiento"
                           class="fi" placeholder="DD/MM/AAAA" readonly>
                </div>
                <input type="hidden" name="titular_fecha_nacimiento"
                       id="titular_fecha_nacimiento_iso"
                       value="{{ old('titular_fecha_nacimiento') }}">
                <div id="edad-badge"></div>
            </div>
        </div>

        {{-- ── CONTACTO ── --}}
        <div class="st">Contacto</div>
        <div class="g2">
            <div class="field">
                <label class="lbl lbl-wa" for="titular_telefono">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="#25d366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Celular / WhatsApp <span class="req">*</span>
                </label>
                <div style="position:relative">
                    <div class="phone-wrap {{ $errors->has('titular_telefono') ? 'err-group' : '' }}">
                        <button type="button" class="phone-code-btn" id="phone-code-btn-1"
                                onclick="togglePhoneDropdown('phone-dd-1')">
                            <span class="flag-emoji" id="phone-flag-1">🇵🇪</span>
                            <span id="phone-code-1">+51</span>
                            <i class="bi bi-chevron-down" style="font-size:.55rem"></i>
                        </button>
                        <input type="text" name="titular_telefono" id="titular_telefono"
                               value="{{ old('titular_telefono') }}"
                               class="fi {{ $errors->has('titular_telefono') ? 'err' : '' }}"
                               placeholder="999 999 999" maxlength="15" inputmode="numeric"
                               oninput="validatePhone(this,'phone-code-1');onTitularChange();updateProgressSteps()"
                               required data-validate="required|phone" data-bloque="2">
                    </div>
                    <input type="hidden" name="titular_telefono_codigo" id="titular_telefono_codigo"
                           value="{{ old('titular_telefono_codigo','+51') }}">
                    <div class="phone-dropdown" id="phone-dd-1">
                        <input type="text" class="phone-dropdown-search" placeholder="Buscar país..."
                               oninput="filterPhoneList(this,'phone-dd-1')">
                        <ul id="phone-list-1"></ul>
                    </div>
                </div>
                @error('titular_telefono')
                    <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
                <div class="fhint">Confirmación por WhatsApp</div>
            </div>

            <div class="field">
                <label class="lbl">Correo electrónico</label>
                <div class="email-wrap">
                    <div class="email-row">
                        <span class="ea"><i class="bi bi-envelope" style="color:var(--adv-blue)"></i></span>
                        <input type="text" id="email-user" class="fi email-user"
                               placeholder="usuario" maxlength="80" autocomplete="off"
                               oninput="emailInput();onTitularChange()"
                               onblur="setTimeout(closeDomains,200)"
                               onpaste="handleEmailPaste(event)">
                        <span class="at-sign">@</span>
                        <input type="text" id="email-domain" class="fi email-domain"
                               placeholder="dominio.com" maxlength="80" autocomplete="off"
                               oninput="joinEmail();onTitularChange()" onblur="setTimeout(closeDomains,200)">
                    </div>
                    <ul class="domain-list" id="domain-list"></ul>
                    <input type="hidden" name="titular_email" id="titular_email"
                           value="{{ old('titular_email') }}">
                </div>
            </div>
        </div>

        {{-- TELÉFONO SECUNDARIO + CANAL --}}
        <div class="g2" style="margin-top:.5rem">
            <div class="field">
                <label class="lbl">Teléfono secundario <span class="opt">(opcional)</span></label>
                <div style="position:relative">
                    <div class="phone-wrap">
                        <button type="button" class="phone-code-btn" id="phone-code-btn-2"
                                onclick="togglePhoneDropdown('phone-dd-2')">
                            <span class="flag-emoji" id="phone-flag-2">🇵🇪</span>
                            <span id="phone-code-2">+51</span>
                            <i class="bi bi-chevron-down" style="font-size:.55rem"></i>
                        </button>
                        <input type="text" name="titular_telefono2" id="titular_telefono2"
                               value="{{ old('titular_telefono2') }}"
                               class="fi" placeholder="999 999 999" maxlength="15" inputmode="numeric"
                               oninput="validatePhone(this,'phone-code-2')">
                    </div>
                    <input type="hidden" name="titular_telefono2_codigo" id="titular_telefono2_codigo"
                           value="{{ old('titular_telefono2_codigo','+51') }}">
                    <div class="phone-dropdown" id="phone-dd-2">
                        <input type="text" class="phone-dropdown-search" placeholder="Buscar país..."
                               oninput="filterPhoneList(this,'phone-dd-2')">
                        <ul id="phone-list-2"></ul>
                    </div>
                </div>
            </div>
            <div class="field">
                <label class="lbl" for="canal_contacto">Canal de contacto <span class="req">*</span></label>
                <select name="canal_contacto" id="canal_contacto"
                        class="fi" required data-validate="required" data-bloque="2">
                    <option value="whatsapp"       {{ old('canal_contacto','whatsapp') == 'whatsapp'       ? 'selected' : '' }}>WhatsApp</option>
                    <option value="presencial"     {{ old('canal_contacto')            == 'presencial'     ? 'selected' : '' }}>Presencial</option>
                    <option value="llamada"        {{ old('canal_contacto')            == 'llamada'        ? 'selected' : '' }}>Llamada telefónica</option>
                    <option value="redes_sociales" {{ old('canal_contacto')            == 'redes_sociales' ? 'selected' : '' }}>Redes Sociales</option>
                    <option value="web"            {{ old('canal_contacto')            == 'web'            ? 'selected' : '' }}>Página web</option>
                    <option value="referido"       {{ old('canal_contacto')            == 'referido'       ? 'selected' : '' }}>Referido</option>
                </select>
            </div>
        </div>

        {{-- CONTACTO DE EMERGENCIA --}}
        <div class="st" style="margin-top:1.5rem">Contacto de emergencia <span class="opt">(opcional)</span></div>
        <div class="g3">
            <div class="field">
                <label class="lbl">Nombre completo</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-person-exclamation"></i></span>
                    <input type="text" name="emergencia_nombre"
                           value="{{ old('emergencia_nombre') }}"
                           class="fi" placeholder="Nombre del contacto" maxlength="200"
                           oninput="this.value=this.value.toUpperCase()">
                </div>
            </div>
            <div class="field">
                <label class="lbl">Parentesco</label>
                <select name="emergencia_parentesco" id="emergencia_parentesco"
                        class="fi" onchange="toggleParentescoOtro(this)">
                    <option value="">— Seleccionar —</option>
                    @foreach([
                        'Padre','Madre','Esposo/a','Conviviente','Hijo/a',
                        'Hermano/a','Cuñado/a','Abuelo/a','Nieto/a',
                        'Tío/a','Sobrino/a','Primo/a','Padrino/Madrina',
                        'Amigo/a','Colega','Vecino/a','Otro'
                    ] as $p)
                        <option value="{{ $p }}" {{ old('emergencia_parentesco') == $p ? 'selected' : '' }}>
                            {{ $p }}
                        </option>
                    @endforeach
                </select>
                <input type="text" id="emergencia_parentesco_manual"
                       name="emergencia_parentesco_manual"
                       value="{{ old('emergencia_parentesco_manual') }}"
                       class="fi" placeholder="Especifica el parentesco..."
                       maxlength="60"
                       style="margin-top:.4rem;display:{{ old('emergencia_parentesco')=='Otro' ? 'block':'none' }}">
            </div>
            <div class="field">
                <label class="lbl">Teléfono</label>
                <div style="position:relative">
                    <div class="phone-wrap">
                        <button type="button" class="phone-code-btn" id="phone-code-btn-3"
                                onclick="togglePhoneDropdown('phone-dd-3')">
                            <span class="flag-emoji" id="phone-flag-3">🇵🇪</span>
                            <span id="phone-code-3">+51</span>
                            <i class="bi bi-chevron-down" style="font-size:.55rem"></i>
                        </button>
                        <input type="text" name="emergencia_telefono"
                               value="{{ old('emergencia_telefono') }}"
                               class="fi" placeholder="999 999 999" maxlength="15" inputmode="numeric"
                               oninput="validatePhone(this,'phone-code-3')">
                    </div>
                    <input type="hidden" name="emergencia_telefono_codigo" id="emergencia_telefono_codigo"
                           value="{{ old('emergencia_telefono_codigo','+51') }}">
                    <div class="phone-dropdown" id="phone-dd-3">
                        <input type="text" class="phone-dropdown-search" placeholder="Buscar país..."
                               oninput="filterPhoneList(this,'phone-dd-3')">
                        <ul id="phone-list-3"></ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             PASAJEROS DEL GRUPO
             NOTA: Se eliminó el switch "Solo viaja el titular"
             porque no tenía sentido — si el titular tiene salud
             y viaja solo, igual se necesita capturar esa info.
             Ahora el titular siempre aparece como Pasajero 1,
             y si no hay adicionales simplemente no se agregan.
        ══════════════════════════════════════════════════════════ --}}
        <div class="st" style="margin-top:1.5rem">Pasajeros del grupo</div>

        {{-- ── TITULAR — Pasajero 1 ── --}}
        <div class="pax-card" id="pax-titular-card"
             style="border:1.5px solid var(--adv-blue,#2563eb);background:#f0f7ff;margin-bottom:.75rem;">
            <div class="pax-head">
                <span>
                    <i class="bi bi-person-badge me-1"></i>
                    Pasajero 1 — <span id="pax1-nombre-lbl" style="font-weight:800">Titular</span>
                </span>
                <span style="background:var(--adv-blue,#2563eb);color:#fff;font-size:.62rem;
                             font-weight:800;padding:.15rem .55rem;border-radius:99px;
                             letter-spacing:.04em">TITULAR</span>
            </div>

            {{-- Resumen de datos en tiempo real --}}
            <div style="display:flex;flex-wrap:wrap;gap:.4rem 1.4rem;padding:.45rem .65rem .5rem;font-size:.76rem;">
                <div>
                    <div class="mini-lbl">Documento</div>
                    <div class="mini-val" id="pax1-doc">—</div>
                </div>
                <div>
                    <div class="mini-lbl">Teléfono</div>
                    <div class="mini-val" id="pax1-tel">—</div>
                </div>
                <div>
                    <div class="mini-lbl">Correo</div>
                    <div class="mini-val" id="pax1-email">—</div>
                </div>
            </div>

            {{-- ── SEGURO (siempre visible, sin abrir salud) ── --}}
            <div class="seguro-row">
                <span class="seguro-lbl">
                    <i class="bi bi-shield-plus" style="color:#0369a1"></i>
                    Seguro de salud
                </span>
                <select name="titular_seguro_salud" id="titular_seguro_salud"
                        class="fi seguro-sel" onchange="updateProgressSteps()">
                    <option value="">Sin seguro</option>
                    <option value="essalud" {{ old('titular_seguro_salud') == 'essalud' ? 'selected' : '' }}>EsSalud</option>
                    <option value="sis"     {{ old('titular_seguro_salud') == 'sis'     ? 'selected' : '' }}>SIS</option>
                    <option value="eps"     {{ old('titular_seguro_salud') == 'eps'     ? 'selected' : '' }}>EPS privada</option>
                    <option value="ffaa"    {{ old('titular_seguro_salud') == 'ffaa'    ? 'selected' : '' }}>FFAA / PNP</option>
                    <option value="otro"    {{ old('titular_seguro_salud') == 'otro'    ? 'selected' : '' }}>Otro</option>
                </select>
            </div>

            {{-- ── SWITCH CONDICIONES DE SALUD ── --}}
            <div class="salud-sw-bar">
                <span class="salud-sw-title">
                    <i class="bi bi-heart-pulse" style="color:#b45309"></i>
                    Condiciones de salud
                    <span class="salud-hint">alergias, restricciones, discapacidad</span>
                </span>
                <label class="sw-label">
                    <div class="pax-sw-track" id="titular-sw-track">
                        <div class="pax-sw-thumb"></div>
                    </div>
                    <input type="checkbox" id="titular-salud-sw" style="display:none"
                           onchange="toggleSaludSw(this,'titular-salud-body','titular-sw-track','titular-sw-lbl')">
                    <span class="pax-sw-lbl" id="titular-sw-lbl">Sin condiciones</span>
                </label>
            </div>

            {{-- ── PANEL SALUD TITULAR (se despliega con el switch) ── --}}
            <div id="titular-salud-body" style="display:none;padding:.8rem .65rem;
                 border-top:1px solid var(--border,#e2e8f0);background:#fffdf7">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem">

                    <div class="field">
                        <label class="lbl">Alergias</label>
                        <div class="yn-grp">
                            <button type="button" class="yn-btn yn-no active"
                                    data-target="titular-alerg-det" onclick="togYN(this)">No</button>
                            <button type="button" class="yn-btn yn-si"
                                    data-target="titular-alerg-det" onclick="togYN(this)">Sí</button>
                        </div>
                        <input type="hidden" id="titular_tiene_alergias" value="no">
                        <div id="titular-alerg-det" style="display:none;margin-top:.4rem">
                            <textarea id="titular_alergias_detalle_input" class="fi" rows="2"
                                      placeholder="Medicamentos, alimentos, materiales...">{{ old('titular_alergias_detalle') }}</textarea>
                        </div>
                    </div>

                    <div class="field">
                        <label class="lbl">Restricciones alimentarias</label>
                        <div class="yn-grp">
                            <button type="button" class="yn-btn yn-no active"
                                    data-target="titular-rest-det" onclick="togYN(this)">No</button>
                            <button type="button" class="yn-btn yn-si"
                                    data-target="titular-rest-det" onclick="togYN(this)">Sí</button>
                        </div>
                        <div id="titular-rest-det" style="display:none;margin-top:.4rem">
                            <textarea id="titular_restricciones_input" class="fi" rows="2"
                                      placeholder="Sin gluten, vegano, sin lactosa...">{{ old('titular_restricciones') }}</textarea>
                        </div>
                    </div>

                    <div class="field" style="grid-column:1/-1">
                        <label class="lbl">Discapacidad</label>
                        <div class="yn-grp">
                            <button type="button" class="yn-btn yn-no active"
                                    data-target="titular-discap-det" onclick="togYN(this)">No</button>
                            <button type="button" class="yn-btn yn-si"
                                    data-target="titular-discap-det" onclick="togYN(this)">Sí</button>
                        </div>
                        <div id="titular-discap-det" style="display:none;margin-top:.4rem" data-discap-wrap>
                            <div class="discap-chips">
                                <span class="discap-chip" data-val="motora"         onclick="togDiscap(this)">Motora</span>
                                <span class="discap-chip" data-val="visual"         onclick="togDiscap(this)">Visual</span>
                                <span class="discap-chip" data-val="auditiva"       onclick="togDiscap(this)">Auditiva</span>
                                <span class="discap-chip" data-val="cognitiva"      onclick="togDiscap(this)">Cognitiva</span>
                                <span class="discap-chip" data-val="habla_lenguaje" onclick="togDiscap(this)">Habla/Lenguaje</span>
                                <span class="discap-chip" data-val="psicosocial"    onclick="togDiscap(this)">Psicosocial</span>
                                <span class="discap-chip" data-val="otro"           onclick="togDiscap(this)">Otro</span>
                            </div>
                            <input type="hidden" id="titular_discapacidades_input"
                                   value="{{ old('titular_discapacidades') }}">
                            <input type="text" class="fi discap-otro"
                                   name="titular_discapacidad_otro"
                                   value="{{ old('titular_discapacidad_otro') }}"
                                   placeholder="Especifica..." style="display:none;margin-top:.35rem">
                        </div>
                    </div>

                    <div class="field" style="grid-column:1/-1">
                        <label class="lbl">Observaciones médicas</label>
                        <div class="yn-grp">
                            <button type="button" class="yn-btn yn-no active"
                                    data-target="titular-obs-det" onclick="togYN(this)">No</button>
                            <button type="button" class="yn-btn yn-si"
                                    data-target="titular-obs-det" onclick="togYN(this)">Sí</button>
                        </div>
                        <div id="titular-obs-det" style="display:none;margin-top:.4rem">
                            <textarea id="titular_obs_medicas_input" class="fi" rows="2"
                                      placeholder="Condiciones crónicas, medicamentos habituales...">{{ old('titular_obs_medicas') }}</textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── PASAJEROS ADICIONALES ── --}}
        <div id="pax-lista"></div>
        <p id="pax-cnt" style="font-size:.74rem;color:var(--ink-4);margin-bottom:.35rem;margin-top:.3rem"></p>
        <button type="button" class="btn-add" onclick="addPax()">
            <i class="bi bi-person-plus"></i> Agregar pasajero
        </button>

    </div>{{-- fin fb-body --}}
</div>

{{-- ══ ESTILOS ══ --}}
<style>
.mini-lbl {
    font-size:.62rem;font-weight:700;color:var(--ink-4);
    text-transform:uppercase;letter-spacing:.05em;
}
.mini-val { font-weight:600;color:var(--ink-2);font-size:.76rem; }

/* Seguro siempre visible */
.seguro-row {
    display:flex;align-items:center;gap:.65rem;
    padding:.5rem .65rem;
    border-top:1px solid var(--border,#e2e8f0);
    background:#f0f9ff;
}
.seguro-lbl {
    font-size:.72rem;font-weight:700;color:#0369a1;
    white-space:nowrap;display:flex;align-items:center;gap:.3rem;
    flex-shrink:0;
}
.seguro-sel {
    flex:1;font-size:.78rem;padding:.3rem .5rem;
    border-radius:6px;min-width:0;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat:no-repeat;background-position:right 8px center;
    padding-right:24px;
}

/* Barra switch salud */
.salud-sw-bar {
    display:flex;align-items:center;justify-content:space-between;gap:.5rem;
    padding:.45rem .65rem;
    background:rgba(0,0,0,.025);
    border-top:1px solid var(--border,#e2e8f0);
}
.salud-sw-title {
    font-size:.72rem;font-weight:700;color:var(--ink-3);
    display:flex;align-items:center;gap:.3rem;flex-wrap:wrap;
}
.salud-hint {
    font-size:.65rem;font-weight:400;color:var(--ink-4);
    font-style:italic;
}
.sw-label { display:flex;align-items:center;gap:.45rem;cursor:pointer; }

/* Switch track/thumb */
.pax-sw-track {
    width:36px;height:20px;background:#cbd5e1;border-radius:99px;
    position:relative;cursor:pointer;transition:background .25s;flex-shrink:0;
}
.pax-sw-track.on { background:#b45309; }
.pax-sw-thumb {
    position:absolute;top:3px;left:3px;width:14px;height:14px;
    background:#fff;border-radius:50%;transition:transform .25s;
    box-shadow:0 1px 3px rgba(0,0,0,.2);
}
.pax-sw-track.on .pax-sw-thumb { transform:translateX(16px); }
.pax-sw-lbl { font-size:.7rem;font-weight:700;color:var(--ink-4);min-width:90px; }
.pax-sw-track.on ~ .pax-sw-lbl { color:#b45309; }

/* Si/No buttons */
.yn-grp { display:flex;gap:.3rem;margin-bottom:.1rem; }
.yn-btn {
    flex:1;padding:.28rem .4rem;border:1.5px solid var(--border,#e2e8f0);
    border-radius:var(--r,6px);background:#fff;font-size:.72rem;font-weight:700;
    cursor:pointer;text-align:center;transition:all .15s;color:var(--ink-3);
}
.yn-btn.yn-no.active { background:#f0fdf4;border-color:#16a34a;color:#16a34a; }
.yn-btn.yn-si.active { background:#fff7ed;border-color:#b45309;color:#b45309; }

/* Discapacidad chips */
.discap-chips { display:flex;flex-wrap:wrap;gap:.3rem;margin-bottom:.35rem; }
.discap-chip {
    padding:.22rem .55rem;border:1.5px solid var(--border,#e2e8f0);border-radius:99px;
    font-size:.68rem;font-weight:600;cursor:pointer;background:#fff;
    color:var(--ink-3);transition:all .15s;user-select:none;
}
.discap-chip.sel { background:#eff6ff;border-color:#2563eb;color:#2563eb; }
</style>

{{-- ══ SCRIPT LOCAL ══ --}}
<script>
/* Parentesco manual */
function toggleParentescoOtro(sel) {
    const m = document.getElementById('emergencia_parentesco_manual');
    if (m) m.style.display = sel.value === 'Otro' ? 'block' : 'none';
}

/* Titular → Pasajero 1 (tiempo real) */
function onTitularChange() {
    const nombre  = (document.getElementById('titular_nombre')?.value || '').trim();
    const doc     = (document.getElementById('titular_numero_documento')?.value || '').trim();
    const tipoDoc = document.getElementById('titular_tipo_documento')?.value || 'DNI';
    const tel     = (document.getElementById('titular_telefono')?.value || '').trim();
    const u       = (document.getElementById('email-user')?.value || '').trim();
    const d       = (document.getElementById('email-domain')?.value || '').trim();
    const email   = (u && d) ? u + '@' + d : u;

    const lbl = document.getElementById('pax1-nombre-lbl');
    if (lbl) lbl.textContent = nombre || 'Titular';

    const setV = (id, v) => { const e = document.getElementById(id); if (e) e.textContent = v || '—'; };
    setV('pax1-doc',   doc ? tipoDoc + ': ' + doc : null);
    setV('pax1-tel',   tel);
    setV('pax1-email', email);

    const codigo = document.getElementById('titular_telefono_codigo')?.value || '+51';
    const telDisplay   = document.getElementById('notif-tel-display');
    const emailDisplay = document.getElementById('notif-email-display');
    if (telDisplay)   telDisplay.textContent   = tel   ? codigo + ' ' + tel : '— Sin teléfono —';
    if (emailDisplay) emailDisplay.textContent = email || '— Sin correo —';
}

/* Switch salud — titular y pasajeros adicionales */
function toggleSaludSw(chk, bodyId, trackId, lblId) {
    const body  = document.getElementById(bodyId);
    const track = document.getElementById(trackId);
    const lbl   = document.getElementById(lblId);
    if (body)  body.style.display  = chk.checked ? 'block' : 'none';
    if (track) track.classList.toggle('on', chk.checked);
    if (lbl)   lbl.textContent = chk.checked ? 'Con condiciones' : 'Sin condiciones';
}

/* Clic en el track activa el checkbox oculto */
document.addEventListener('click', e => {
    const track = e.target.closest('.pax-sw-track');
    if (!track) return;
    e.preventDefault();
    e.stopPropagation();
    const chk = track.nextElementSibling;
    if (chk && chk.type === 'checkbox') {
        chk.checked = !chk.checked;
        chk.dispatchEvent(new Event('change'));
    }
});

/* Si / No toggle */
function togYN(btn) {
    const grp = btn.closest('.yn-grp');
    grp?.querySelectorAll('.yn-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const isSi = btn.classList.contains('yn-si');
    const det  = document.getElementById(btn.dataset.target);
    if (det) det.style.display = isSi ? 'block' : 'none';
    if (!isSi && det) det.querySelectorAll('textarea,input[type="text"]').forEach(t => t.value = '');

    if (btn.dataset.target === 'titular-alerg-det') {
    const hid = document.getElementById('titular_tiene_alergias');
    if (hid) hid.value = isSi ? 'si' : 'no';
     }
}

/* Discapacidad chips */
function togDiscap(chip) {
    chip.classList.toggle('sel');
    const wrap = chip.closest('[data-discap-wrap]');
    if (!wrap) return;
    const vals = [...wrap.querySelectorAll('.discap-chip.sel')].map(c => c.dataset.val);
    const hid  = wrap.querySelector('input[type="hidden"]');
    if (hid) hid.value = vals.join(',');
    const otro = wrap.querySelector('.discap-otro');
    if (otro) otro.style.display = vals.includes('otro') ? 'block' : 'none';
}

/* ══════════════════════════════════════════════
   SINCRONIZAR SALUD A HIDDENS ANTES DEL SUBMIT
   Se registra UNA sola vez en DOMContentLoaded
══════════════════════════════════════════════ */
function sincronizarSaludHiddens() {
    const seguro = document.getElementById('titular_seguro_salud');
    if (seguro) document.getElementById('hid_titular_seguro_salud').value = seguro.value;

    const tieneAlerg = document.getElementById('titular_tiene_alergias');
    if (tieneAlerg) document.getElementById('hid_titular_tiene_alergias').value = tieneAlerg.value;

    const detAlerg = document.getElementById('titular_alergias_detalle_input');
    if (detAlerg) document.getElementById('hid_titular_alergias_detalle').value = detAlerg.value;

    const rest = document.getElementById('titular_restricciones_input');
    if (rest) document.getElementById('hid_titular_restricciones').value = rest.value;

    const obs = document.getElementById('titular_obs_medicas_input');
    if (obs) document.getElementById('hid_titular_obs_medicas').value = obs.value;

    const discap = document.getElementById('titular_discapacidades_input');
    if (discap) document.getElementById('hid_titular_discapacidades').value = discap.value;

    const discapOtro = document.getElementById('titular_discapacidad_otro_input');
    if (discapOtro) document.getElementById('hid_titular_discapacidad_otro').value = discapOtro.value;
}

document.addEventListener('DOMContentLoaded', () => {
    onTitularChange();

    // Restaurar old() para discapacidades del titular
    const discapOld = document.querySelector('[name="titular_discapacidades"]')?.value || '';
    if (discapOld) {
        discapOld.split(',').filter(Boolean).forEach(val => {
            const chip = document.querySelector(`#titular-discap-det .discap-chip[data-val="${val}"]`);
            if (chip) chip.classList.add('sel');
        });
        const otro = document.querySelector('[name="titular_discapacidad_otro"]');
        if (otro?.value) {
            const otroCont = document.querySelector('.discap-otro');
            if (otroCont) otroCont.style.display = 'block';
        }
    }

    // Flatpickr fecha nacimiento titular
    const fpEl = document.getElementById('titular_fecha_nacimiento');
    if (fpEl?._flatpickr) fpEl._flatpickr.destroy();
    if (typeof flatpickr !== 'undefined') {
        flatpickr('#titular_fecha_nacimiento', {
            locale: 'es', dateFormat: 'd/m/Y', maxDate: 'today', allowInput: false,
            onChange(sel, str, inst) {
                const iso = inst.formatDate(sel[0], 'Y-m-d');
                const h = document.getElementById('titular_fecha_nacimiento_iso');
                if (h) h.value = iso;
                calcEdad(iso);
            },
            onReady(sel, str, inst) {
                const v = document.getElementById('titular_fecha_nacimiento_iso')?.value;
                if (v) { inst.setDate(v, false, 'Y-m-d'); calcEdad(v); }
            }
        });
    }

    // Registrar listener del submit UNA sola vez aquí
    document.getElementById('form-reserva')?.addEventListener('submit', sincronizarSaludHiddens, true);
});
</script>