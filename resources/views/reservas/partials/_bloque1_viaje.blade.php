{{-- =====================================================================
     UBICACION: resources/views/reservas/partials/_bloque1_viaje.blade.php
     ===================================================================== --}}

<input type="hidden" name="fecha_tour"  id="fecha_tour"  value="{{ old('fecha_tour',  now()->toDateString()) }}">
<input type="hidden" name="hora_salida" id="hora_salida"  value="{{ old('hora_salida', '08:00') }}">

<div class="fb" id="bloque-1">
    <div class="fb-num-badge" id="fb-status-1">1</div>
    <div class="fb-head">
        <div class="fb-ico amber"><i class="bi bi-airplane"></i></div>
        <div class="fb-titles">
            <h3>Informacion de viaje</h3>
            <p>Paquete, destino, fechas, transporte y hospedaje</p>
        </div>
    </div>
    <div class="fb-body">

        {{-- ── PAQUETE Y PRECIO ── --}}
        <div class="st">Paquete y destino</div>
        <div class="g2">
            <div class="field">
                <label class="lbl" for="nombre_tour">Tipo de paquete <span class="req">*</span></label>
                <div class="ig {{ $errors->has('nombre_tour') ? 'err-group' : '' }}" style="position:relative;overflow:visible">
                    <span class="ia"><i class="bi bi-briefcase"></i></span>
                    <input type="text" name="nombre_tour" id="nombre_tour"
                           value="{{ old('nombre_tour') }}"
                           class="fi {{ $errors->has('nombre_tour') ? 'err' : '' }}"
                           placeholder="Ej: Arequipa 04D/03N, Cusco 04D/03N..."
                           required maxlength="200" autocomplete="off"
                           oninput="onNombreTourInput(this)"
                           data-validate="required" data-bloque="1">
                    <ul id="tour-sugerencias" style="
                        display:none;position:absolute;top:calc(100% + 2px);left:0;right:0;
                        background:#fff;border:1px solid #e2e8f0;border-radius:8px;
                        box-shadow:0 4px 16px rgba(0,0,0,.1);z-index:300;
                        max-height:220px;overflow-y:auto;margin:0;padding:0;list-style:none;
                    "></ul>
                </div>
                @error('nombre_tour')
                    <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            <div class="field">
                <label class="lbl" for="precio_tour">Precio del servicio <span class="req">*</span></label>
                <div class="ig {{ $errors->has('precio_tour') ? 'err-group' : '' }}">
                    <span class="ia">S/</span>
                    <input type="number" name="precio_tour" id="precio_tour"
                           value="{{ old('precio_tour') }}"
                           class="fi {{ $errors->has('precio_tour') ? 'err' : '' }}"
                           step="0.01" min="0" placeholder="0.00"
                           required inputmode="decimal"
                           oninput="this.value=this.value.replace(/[^0-9.]/g,'');calcTotal()"
                           data-validate="required|numeric" data-bloque="1">
                </div>
                @error('precio_tour')
                    <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- ── DESTINO ── --}}
        <div class="g2">
            <div class="field">
                <label class="lbl" for="ciudad_destino">Ciudad de destino <span class="req">*</span></label>
                <div class="ig" style="position:relative;overflow:visible">
                    <span class="ia"><i class="bi bi-geo-alt-fill"></i></span>
                    <input type="text" name="ciudad_destino" id="ciudad_destino"
                           value="{{ old('ciudad_destino') }}"
                           class="fi" placeholder="Ej: Cajamarca, Cusco, Mancora..."
                           required maxlength="150" autocomplete="off"
                           oninput="onCiudadDestinoInput(this)"
                           data-validate="required" data-bloque="1">
                    <ul id="ciudad-destino-suggestions" class="city-suggestions"></ul>
                </div>
            </div>
            <div class="field">
                <label class="lbl" for="departamento_destino">Departamento de destino</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-map"></i></span>
                    <select name="departamento_destino" id="departamento_destino"
                            class="fi" onchange="updateProgressSteps()">
                        <option value="">— Se detecta automaticamente —</option>
                        @php
                            $departamentos = [
                                'Amazonas','Ancash','Apurimac','Arequipa','Ayacucho',
                                'Cajamarca','Callao','Cusco','Huancavelica','Huanuco','Ica','Junin',
                                'La Libertad','Lambayeque','Lima','Loreto','Madre de Dios','Moquegua',
                                'Pasco','Piura','Puno','San Martin','Tacna','Tumbes','Ucayali',
                                'Internacional'
                            ];
                        @endphp
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep }}" {{ old('departamento_destino') == $dep ? 'selected' : '' }}>
                                {{ $dep }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="fhint">Se completa automaticamente segun la ciudad</div>
            </div>
        </div>

        {{-- Procedencia --}}
        <div class="field" style="max-width:380px">
            <label class="lbl" for="ciudad_procedencia">Ciudad de origen del cliente <span class="req">*</span></label>
            <div class="ig {{ $errors->has('ciudad_procedencia') ? 'err-group' : '' }}">
                <span class="ia"><i class="bi bi-pin-map"></i></span>
                <select name="ciudad_procedencia" id="ciudad_procedencia"
                        class="fi {{ $errors->has('ciudad_procedencia') ? 'err' : '' }}"
                        required data-validate="required" data-bloque="1">
                    <option value="">— Seleccionar —</option>
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep }}" {{ old('ciudad_procedencia') == $dep ? 'selected' : '' }}>
                            {{ $dep }}
                        </option>
                    @endforeach
                    <option value="Extranjero" {{ old('ciudad_procedencia') == 'Extranjero' ? 'selected' : '' }}>
                        Extranjero
                    </option>
                </select>
            </div>
            @error('ciudad_procedencia')
                <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        {{-- ── FECHAS ── --}}
        <div class="st">Fechas</div>

        <div class="fecha-hora-row">
            <div class="field">
                <label class="lbl">Fecha de arribo</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-calendar3"></i></span>
                    <input type="text" id="fecha_arribo_display" class="fi" placeholder="Seleccionar..." readonly>
                </div>
                <input type="hidden" name="fecha_arribo" id="fecha_arribo" value="{{ old('fecha_arribo') }}">
                <label class="nd-check">
                    <input type="checkbox" id="nd_fecha_arribo" name="nd_fecha_arribo" value="1"
                           {{ old('nd_fecha_arribo') ? 'checked' : '' }}
                           onchange="toggleND(this,'fecha_arribo_display','fecha_arribo')">
                    N/D
                </label>
            </div>
            <div class="field">
                <label class="lbl">Hora de arribo</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-clock"></i></span>
                    <input type="text" id="hora_arribo_display" class="fi" placeholder="00:00 AM" readonly>
                </div>
                <input type="hidden" name="hora_arribo" id="hora_arribo" value="{{ old('hora_arribo') }}">
                <label class="nd-check">
                    <input type="checkbox" id="nd_hora_arribo" name="nd_hora_arribo" value="1"
                           {{ old('nd_hora_arribo') ? 'checked' : '' }}
                           onchange="toggleNDHora(this,'hora_arribo_display','hora_arribo')">
                    N/D
                </label>
            </div>
            <div class="field">
                <label class="lbl">Dias de viaje</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-clock-history"></i></span>
                    <input type="text" id="dias_calculados" name="dias_viaje"
                           value="{{ old('dias_viaje') }}" class="fi" readonly placeholder="Dias">
                </div>
                <div class="fhint">Automatico</div>
            </div>
        </div>

        <div class="fecha-hora-row" style="margin-top:.6rem">
            <div class="field">
                <label class="lbl">Fecha de retorno</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-calendar3"></i></span>
                    <input type="text" id="fecha_retorno_display" class="fi" placeholder="Seleccionar..." readonly>
                </div>
                <input type="hidden" name="fecha_retorno" id="fecha_retorno" value="{{ old('fecha_retorno') }}">
                <label class="nd-check">
                    <input type="checkbox" id="nd_fecha_retorno" name="nd_fecha_retorno" value="1"
                           {{ old('nd_fecha_retorno') ? 'checked' : '' }}
                           onchange="toggleND(this,'fecha_retorno_display','fecha_retorno')">
                    N/D
                </label>
            </div>
            <div class="field">
                <label class="lbl">Hora de retorno</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-clock"></i></span>
                    <input type="text" id="hora_retorno_display" class="fi" placeholder="00:00 AM" readonly>
                </div>
                <input type="hidden" name="hora_retorno" id="hora_retorno" value="{{ old('hora_retorno') }}">
                <label class="nd-check">
                    <input type="checkbox" id="nd_hora_retorno" name="nd_hora_retorno" value="1"
                           {{ old('nd_hora_retorno') ? 'checked' : '' }}
                           onchange="toggleNDHora(this,'hora_retorno_display','hora_retorno')">
                    N/D
                </label>
            </div>
            <div class="field"></div>
        </div>

        {{-- ── TRANSPORTE ── --}}
        <div class="st">Transporte <span class="opt">(opcional)</span></div>
        <div class="field" style="max-width:280px">
            <label class="lbl">Tipo de transporte</label>
            <select name="tipo_transporte" id="tipo_transporte" class="fi" onchange="toggleTransporte()">
                <option value="">— Sin transporte —</option>
                <option value="terrestre" {{ old('tipo_transporte') == 'terrestre' ? 'selected' : '' }}>Terrestre</option>
                <option value="aereo"     {{ old('tipo_transporte') == 'aereo'     ? 'selected' : '' }}>Aereo</option>
            </select>
        </div>

        <div class="transport-fields {{ old('tipo_transporte') == 'terrestre' ? 'visible' : '' }}" id="transport-terrestre">
            <div class="field" style="max-width:380px">
                <label class="lbl">Empresa de transporte</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-bus-front"></i></span>
                    <input type="text" name="empresa_transporte" id="empresa_transporte"
                           value="{{ old('empresa_transporte') }}" class="fi"
                           placeholder="Cruz del Sur, Oltursa, Movil Tours..." maxlength="150">
                </div>
            </div>
        </div>

        <div class="transport-fields {{ old('tipo_transporte') == 'aereo' ? 'visible' : '' }}" id="transport-aereo">
            <div class="g2">
                <div class="field">
                    <label class="lbl">Aerolinea</label>
                    <div class="ig">
                        <span class="ia"><i class="bi bi-airplane-engines"></i></span>
                        <select name="aerolinea" id="aerolinea" class="fi" onchange="toggleAerolineaOtra(this)">
                            <option value="">— Seleccionar —</option>
                            <optgroup label="Nacionales">
                                <option value="LATAM Airlines Peru"  {{ old('aerolinea') == 'LATAM Airlines Peru'  ? 'selected':'' }}>LATAM Airlines Peru</option>
                                <option value="Sky Airline Peru"     {{ old('aerolinea') == 'Sky Airline Peru'     ? 'selected':'' }}>Sky Airline Peru</option>
                                <option value="JetSMART Peru"        {{ old('aerolinea') == 'JetSMART Peru'        ? 'selected':'' }}>JetSMART Peru</option>
                                <option value="Star Peru"            {{ old('aerolinea') == 'Star Peru'            ? 'selected':'' }}>Star Peru</option>
                                <option value="ATSA Airlines"        {{ old('aerolinea') == 'ATSA Airlines'        ? 'selected':'' }}>ATSA Airlines</option>
                            </optgroup>
                            <optgroup label="Internacionales">
                                <option value="Avianca"          {{ old('aerolinea') == 'Avianca'          ? 'selected':'' }}>Avianca</option>
                                <option value="American Airlines" {{ old('aerolinea') == 'American Airlines' ? 'selected':'' }}>American Airlines</option>
                                <option value="Copa Airlines"    {{ old('aerolinea') == 'Copa Airlines'    ? 'selected':'' }}>Copa Airlines</option>
                                <option value="Delta Air Lines"  {{ old('aerolinea') == 'Delta Air Lines'  ? 'selected':'' }}>Delta Air Lines</option>
                                <option value="Iberia"           {{ old('aerolinea') == 'Iberia'           ? 'selected':'' }}>Iberia</option>
                                <option value="Air Europa"       {{ old('aerolinea') == 'Air Europa'       ? 'selected':'' }}>Air Europa</option>
                                <option value="KLM"              {{ old('aerolinea') == 'KLM'              ? 'selected':'' }}>KLM</option>
                                <option value="Aeromexico"       {{ old('aerolinea') == 'Aeromexico'       ? 'selected':'' }}>Aeromexico</option>
                                <option value="Spirit Airlines"  {{ old('aerolinea') == 'Spirit Airlines'  ? 'selected':'' }}>Spirit Airlines</option>
                                <option value="United Airlines"  {{ old('aerolinea') == 'United Airlines'  ? 'selected':'' }}>United Airlines</option>
                            </optgroup>
                            <option value="otra">Otra aerolinea...</option>
                        </select>
                    </div>
                    <input type="text" id="aerolinea_otra" name="aerolinea_otra"
                           value="{{ old('aerolinea_otra') }}" class="fi"
                           placeholder="Nombre de la aerolinea"
                           style="margin-top:.4rem;display:{{ old('aerolinea')=='otra' ? 'block':'none' }}"
                           maxlength="100">
                </div>
                <div class="field">
                    <label class="lbl">Numero de vuelo</label>
                    <div class="ig">
                        <span class="ia"><i class="bi bi-hash"></i></span>
                        <input type="text" name="numero_vuelo" id="numero_vuelo"
                               value="{{ old('numero_vuelo') }}" class="fi"
                               placeholder="LA2045" maxlength="20"
                               oninput="this.value=this.value.toUpperCase()">
                    </div>
                </div>
            </div>
            <div class="g2">
                <div class="field">
                    <label class="lbl">Hora de salida del vuelo</label>
                    <div class="ig">
                        <span class="ia"><i class="bi bi-box-arrow-up-right"></i></span>
                        <input type="text" id="hora_salida_vuelo_display" class="fi" placeholder="00:00 AM" readonly>
                    </div>
                    <input type="hidden" name="hora_salida_vuelo" id="hora_salida_vuelo" value="{{ old('hora_salida_vuelo') }}">
                </div>
                <div class="field">
                    <label class="lbl">Hora de llegada del vuelo</label>
                    <div class="ig">
                        <span class="ia"><i class="bi bi-box-arrow-in-down-left"></i></span>
                        <input type="text" id="hora_llegada_vuelo_display" class="fi" placeholder="00:00 AM" readonly>
                    </div>
                    <input type="hidden" name="hora_llegada_vuelo" id="hora_llegada_vuelo" value="{{ old('hora_llegada_vuelo') }}">
                </div>
            </div>
        </div>

        {{-- ── HOSPEDAJE ── --}}
        <div class="st">Hospedaje <span class="opt">(opcional)</span></div>
        <div class="g2">
            <div class="field">
                <label class="lbl">Nombre del hotel</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-building"></i></span>
                    <input type="text" name="nombre_hotel" id="nombre_hotel"
                           value="{{ old('nombre_hotel') }}" class="fi"
                           placeholder="Nombre del hotel o alojamiento" maxlength="200">
                </div>
            </div>
            <div class="field">
                <label class="lbl">Tipo de establecimiento</label>
                <select name="tipo_establecimiento" id="tipo_establecimiento" class="fi">
                    <option value="">— Seleccionar —</option>
                    <option value="hotel_2"  {{ old('tipo_establecimiento') == 'hotel_2'  ? 'selected':'' }}>Hotel 2 estrellas</option>
                    <option value="hotel_3"  {{ old('tipo_establecimiento') == 'hotel_3'  ? 'selected':'' }}>Hotel 3 estrellas</option>
                    <option value="hotel_4"  {{ old('tipo_establecimiento') == 'hotel_4'  ? 'selected':'' }}>Hotel 4 estrellas</option>
                    <option value="hotel_5"  {{ old('tipo_establecimiento') == 'hotel_5'  ? 'selected':'' }}>Hotel 5 estrellas</option>
                    <option value="hostal"   {{ old('tipo_establecimiento') == 'hostal'   ? 'selected':'' }}>Hostal</option>
                    <option value="apart"    {{ old('tipo_establecimiento') == 'apart'    ? 'selected':'' }}>Apart-hotel</option>
                    <option value="resort"   {{ old('tipo_establecimiento') == 'resort'   ? 'selected':'' }}>Resort</option>
                    <option value="ecolodge" {{ old('tipo_establecimiento') == 'ecolodge' ? 'selected':'' }}>Ecolodge</option>
                    <option value="albergue" {{ old('tipo_establecimiento') == 'albergue' ? 'selected':'' }}>Albergue / Hostel</option>
                </select>
            </div>
        </div>

        {{-- ── HABITACIONES — cada una con su cama y alimentación ── --}}
        <div class="st" style="margin-top:.75rem">Habitaciones <span class="opt">(opcional)</span></div>

        {{-- Selector para agregar habitación --}}
        <div style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;margin-bottom:.75rem">
            <div class="ig" style="flex:1;min-width:180px">
                <span class="ia"><i class="bi bi-door-closed"></i></span>
                <select id="hab-tipo-selector" class="fi">
                    <option value="">— Tipo de habitación —</option>
                    <option value="sgl">SGL — Simple (1 persona)</option>
                    <option value="dbl">DBL — Doble matrimonial</option>
                    <option value="twn">TWN — Twin (2 camas)</option>
                    <option value="tpl">TPL — Triple</option>
                    <option value="qdl">QDL — Cuádruple</option>
                    <option value="fam">FAM — Familiar</option>
                    <option value="sui">SUI — Suite</option>
                </select>
            </div>
            <button type="button" onclick="agregarHabitacion()"
                    style="padding:.45rem .9rem;border-radius:6px;background:var(--adv-blue,#2563eb);
                           color:#fff;border:none;font-size:.75rem;font-weight:700;cursor:pointer;
                           display:flex;align-items:center;gap:.3rem;white-space:nowrap;flex-shrink:0">
                <i class="bi bi-plus-lg"></i> Agregar habitación
            </button>
        </div>

        {{-- Lista de habitaciones agregadas --}}
        <div id="hab-lista"></div>

        {{-- Hidden con el resumen serializado --}}
        <input type="hidden" name="tipo_habitacion" id="tipo_habitacion_hidden" value="{{ old('tipo_habitacion') }}">

    </div>
</div>

{{-- ═══════════════════════════════════════════════ ESTILOS ══ --}}
<style>
.fb { overflow: visible !important; }
.ig { overflow: visible !important; }
.fecha-hora-row {
    display:grid;grid-template-columns:1fr 1fr 1fr;gap:.75rem;align-items:start;
}
@media(max-width:600px){
    .fecha-hora-row{grid-template-columns:1fr 1fr;}
    .fecha-hora-row .field:last-child{display:none;}
}
.city-suggestions {
    position:absolute;top:calc(100% + 2px);left:0;right:0;
    background:#fff;border:1px solid var(--border,#e2e8f0);
    border-radius:6px;box-shadow:0 4px 16px rgba(0,0,0,.1);
    z-index:200;max-height:220px;overflow-y:auto;
    margin:0;padding:0;list-style:none;display:none;
}
.city-suggestions.open{display:block}
.city-suggestions li{
    padding:.45rem .75rem;font-size:.8rem;cursor:pointer;
    display:flex;justify-content:space-between;align-items:center;
    color:#334155;border-bottom:1px solid #f1f5f9;
}
.city-suggestions li:last-child{border-bottom:none}
.city-suggestions li:hover{background:#f8fafc}
.cs-dep{font-size:.68rem;color:#94a3b8;font-weight:600}

/* Tarjeta de habitación */
.hab-card {
    background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;
    padding:.75rem;margin-bottom:.6rem;
}
.hab-card-head {
    display:flex;justify-content:space-between;align-items:center;
    margin-bottom:.6rem;
}
.hab-card-title {
    font-size:.82rem;font-weight:700;color:#1e40af;
    display:flex;align-items:center;gap:.35rem;
}
.hab-card-body {
    display:grid;grid-template-columns:1fr 1fr;gap:.5rem;
}
@media(max-width:500px){.hab-card-body{grid-template-columns:1fr;}}
.hab-del-btn {
    background:none;border:none;cursor:pointer;color:#94a3b8;
    font-size:.8rem;padding:2px 6px;border-radius:4px;
    display:flex;align-items:center;gap:.2rem;
}
.hab-del-btn:hover{color:#ef4444;background:#fef2f2;}
.hab-select {
    width:100%;padding:.35rem .5rem;border:1px solid #e2e8f0;
    border-radius:6px;font-size:.78rem;background:#fff;
    color:#334155;font-family:inherit;outline:none;
}
.hab-select:focus{border-color:#3b82f6;}
.hab-field-lbl{font-size:.68rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.2rem;}
</style>

{{-- ═══════════════════════════════════════════════ SCRIPTS ══ --}}
<script>
/* ══════════════════════════════════════════════════════
   AUTOCOMPLETE TOURS — fuera del DOMContentLoaded
══════════════════════════════════════════════════════ */
let _tourTimer = null;

function onNombreTourInput(inp) {
    updateProgressSteps();
    clearTimeout(_tourTimer);
    const q  = inp.value.trim();
    const ul = document.getElementById('tour-sugerencias');
    if (!ul) return;
    if (q.length < 2) { ul.style.display = 'none'; return; }

    _tourTimer = setTimeout(async () => {
        try {
            const res  = await fetch('/api/tours/sugerencias?q=' + encodeURIComponent(q));
            const data = await res.json();
            if (!data.length) { ul.style.display = 'none'; return; }
            ul.innerHTML = data.map(t =>
                `<li onclick="seleccionarTour('${t.nombre.replace(/'/g,"\\'")}')"
                     style="padding:.45rem .75rem;font-size:.82rem;cursor:pointer;
                            display:flex;justify-content:space-between;align-items:center;
                            border-bottom:1px solid #f1f5f9;color:#334155;">
                    <span>${t.nombre}</span>
                    <span style="font-size:.68rem;color:#94a3b8">
                        ${t.veces_usado > 0 ? '×'+t.veces_usado : t.categoria}
                    </span>
                </li>`
            ).join('');
            ul.style.display = 'block';
        } catch(e) { ul.style.display = 'none'; }
    }, 300);
}

function seleccionarTour(nombre) {
    document.getElementById('nombre_tour').value = nombre;
    document.getElementById('tour-sugerencias').style.display = 'none';
    updateProgressSteps();
}

/* ══════════════════════════════════════════════════════
   AUTOCOMPLETE CIUDAD — busca en BD via API
══════════════════════════════════════════════════════ */
let _ciudadTimer = null;

function onCiudadDestinoInput(inp) {
    updateProgressSteps();
    clearTimeout(_ciudadTimer);
    const val = inp.value.trim();
    if (val.length < 2) { _cerrarSug(); return; }
    _ciudadTimer = setTimeout(() => _buscarCiudad(val), 250);
}

async function _buscarCiudad(q) {
    try {
        const res  = await fetch('/api/ciudades/buscar?q=' + encodeURIComponent(q));
        const data = await res.json();
        const cont = document.getElementById('ciudad-destino-suggestions');
        if (!cont) return;
        if (!data.length) { _cerrarSug(); return; }
        cont.innerHTML = data.map(c =>
            `<li onclick="seleccionarCiudad('${c.nombre.replace(/'/g,"\\'")}','${c.departamento}')">
                <span>${c.nombre}</span>
                <span class="cs-dep">${c.departamento}</span>
             </li>`
        ).join('');
        cont.classList.add('open');
    } catch(e) { _cerrarSug(); }
}

function seleccionarCiudad(ciudad, dep) {
    const inp = document.getElementById('ciudad_destino');
    if (inp) inp.value = ciudad;
    _setDepto(dep);
    _cerrarSug();
    updateProgressSteps();
}

function _setDepto(dep) {
    const sel = document.getElementById('departamento_destino');
    if (sel && dep) sel.value = dep;
}

function _cerrarSug() {
    const c = document.getElementById('ciudad-destino-suggestions');
    if (c) { c.classList.remove('open'); c.innerHTML = ''; }
}

/* ══════════════════════════════════════════════════════
   AEROLINEA OTRA
══════════════════════════════════════════════════════ */
function toggleAerolineaOtra(sel) {
    const otro = document.getElementById('aerolinea_otra');
    if (otro) otro.style.display = sel.value === 'otra' ? 'block' : 'none';
}

/* ══════════════════════════════════════════════════════
   N/D HORAS
══════════════════════════════════════════════════════ */
function toggleNDHora(chk, displayId, hiddenId) {
    const display = document.getElementById(displayId);
    const hidden  = document.getElementById(hiddenId);
    const label   = chk.closest('.nd-check');
    if (chk.checked) {
        if (display) { display.value = 'N/D'; display.disabled = true; display.style.background = '#f1f5f9'; }
        if (hidden)  hidden.value = 'N/D';
        if (label)   label.classList.add('active');
        if (display && display._flatpickr) display._flatpickr.destroy();
    } else {
        if (display) { display.value = ''; display.disabled = false; display.style.background = ''; }
        if (hidden)  hidden.value = '';
        if (label)   label.classList.remove('active');
        if (display) flatpickr('#' + displayId, {
            enableTime:true, noCalendar:true, dateFormat:'h:i K', time_24hr:false, allowInput:false,
            onChange(sel, str, inst) {
                const h = document.getElementById(hiddenId);
                if (h) h.value = inst.formatDate(sel[0], 'H:i');
                updateProgressSteps();
            }
        });
    }
    updateProgressSteps();
}

/* ══════════════════════════════════════════════════════
   HABITACIONES — con cama y alimentación por unidad
══════════════════════════════════════════════════════ */
let _habIndex = 0;
const _habitaciones = {}; // { idx: { tipo, cama, alimentacion } }

const HAB_LABELS = {
    sgl:'SGL — Simple', dbl:'DBL — Doble matrimonial',
    twn:'TWN — Twin',   tpl:'TPL — Triple',
    qdl:'QDL — Cuádruple', fam:'FAM — Familiar', sui:'SUI — Suite',
};
const CAMA_LABELS = { KB:'King Bed', QB:'Queen Bed', TB:'Twin Beds' };
const ALIM_LABELS = { RO:'Solo habitación', BB:'Con desayuno', HB:'Desayuno y cena', FB:'Pensión completa', AI:'Todo incluido' };

function agregarHabitacion() {
    const sel  = document.getElementById('hab-tipo-selector');
    const tipo = sel?.value;
    if (!tipo) {
        if (sel) { sel.classList.add('err'); setTimeout(() => sel.classList.remove('err'), 1200); }
        return;
    }
    const idx = _habIndex++;
    _habitaciones[idx] = { tipo, cama:'', alimentacion:'' };
    _renderHabCard(idx);
    sel.value = '';
    _serializarHabs();
    updateProgressSteps();
}

function _renderHabCard(idx) {
    const lista = document.getElementById('hab-lista');
    if (!lista) return;
    const hab   = _habitaciones[idx];
    const label = HAB_LABELS[hab.tipo] || hab.tipo;
    const num   = Object.keys(_habitaciones).indexOf(String(idx)) + 1;

    const card = document.createElement('div');
    card.className = 'hab-card';
    card.id = 'hab-card-' + idx;
    card.innerHTML = `
        <div class="hab-card-head">
            <span class="hab-card-title">
                <i class="bi bi-door-closed"></i>
                Habitación ${num} — ${label}
            </span>
            <button type="button" class="hab-del-btn" onclick="eliminarHabitacion(${idx})">
                <i class="bi bi-x-lg"></i> Quitar
            </button>
        </div>
        <div class="hab-card-body">
            <div>
                <div class="hab-field-lbl">Tipo de cama</div>
                <select class="hab-select" onchange="_habChange(${idx},'cama',this.value)">
                    <option value="">— Sin preferencia —</option>
                    <option value="KB">KB — King Bed</option>
                    <option value="QB">QB — Queen Bed</option>
                    <option value="TB">TB — Twin Beds (separadas)</option>
                </select>
            </div>
            <div>
                <div class="hab-field-lbl">Plan de alimentación</div>
                <select class="hab-select" onchange="_habChange(${idx},'alimentacion',this.value)">
                    <option value="">— Sin plan —</option>
                    <option value="RO">RO — Solo habitación</option>
                    <option value="BB">BB — Con desayuno</option>
                    <option value="HB">HB — Desayuno y cena</option>
                    <option value="FB">FB — Pensión completa</option>
                    <option value="AI">AI — Todo incluido</option>
                </select>
            </div>
        </div>
    `;
    lista.appendChild(card);
}

function _habChange(idx, campo, valor) {
    if (_habitaciones[idx]) _habitaciones[idx][campo] = valor;
    _serializarHabs();
}

function eliminarHabitacion(idx) {
    delete _habitaciones[idx];
    document.getElementById('hab-card-' + idx)?.remove();
    _serializarHabs();
    updateProgressSteps();
}

function _serializarHabs() {
    const partes = Object.entries(_habitaciones).map(([idx, h]) => {
        const label = HAB_LABELS[h.tipo] || h.tipo;
        const cama  = h.cama        ? ' | ' + (CAMA_LABELS[h.cama]  || h.cama)        : '';
        const alim  = h.alimentacion ? ' | ' + (ALIM_LABELS[h.alimentacion] || h.alimentacion) : '';
        return label + cama + alim;
    });
    const hidden = document.getElementById('tipo_habitacion_hidden');
    if (hidden) hidden.value = partes.join(' / ');
}

/* ══════════════════════════════════════════════════════
   CLICK FUERA — cerrar dropdowns
══════════════════════════════════════════════════════ */
document.addEventListener('click', e => {
    if (!e.target.closest('#ciudad_destino') && !e.target.closest('#ciudad-destino-suggestions'))
        _cerrarSug();
    if (!e.target.closest('#nombre_tour') && !e.target.closest('#tour-sugerencias')) {
        const ul = document.getElementById('tour-sugerencias');
        if (ul) ul.style.display = 'none';
    }
});

/* ══════════════════════════════════════════════════════
   DOM CONTENT LOADED
══════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function () {

    // N/D horas
    ['hora_arribo','hora_retorno'].forEach(id => {
        const chk = document.getElementById('nd_' + id);
        if (chk && chk.checked) toggleNDHora(chk, id + '_display', id);
    });

    // Aerolinea old()
    const aerSel = document.getElementById('aerolinea');
    if (aerSel && aerSel.value === 'otra') {
        const otro = document.getElementById('aerolinea_otra');
        if (otro) otro.style.display = 'block';
    }

    // Ciudad old() — detectar departamento
    const ciudadInp = document.getElementById('ciudad_destino');
    if (ciudadInp && ciudadInp.value) onCiudadDestinoInput(ciudadInp);

    // Habitaciones old() — restaurar desde hidden
    const habOld = document.getElementById('tipo_habitacion_hidden')?.value || '';
    if (habOld) {
        habOld.split(' / ').forEach(parte => {
            const partes = parte.split(' | ');
            const tipo   = Object.entries(HAB_LABELS).find(([k,v]) => v === partes[0])?.[0] || 'sgl';
            const cama   = Object.entries(CAMA_LABELS).find(([k,v]) => v === partes[1])?.[0] || '';
            const alim   = Object.entries(ALIM_LABELS).find(([k,v]) => v === partes[2])?.[0] || '';
            const idx    = _habIndex++;
            _habitaciones[idx] = { tipo, cama, alimentacion: alim };
            _renderHabCard(idx);
            // Restaurar selects
            const card = document.getElementById('hab-card-' + idx);
            if (card) {
                const selects = card.querySelectorAll('.hab-select');
                if (selects[0] && cama) selects[0].value = cama;
                if (selects[1] && alim) selects[1].value = alim;
            }
        });
    }
});
</script>
