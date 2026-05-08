{{-- =====================================================================
     UBICACION: resources/views/reservas/partials/_bloque1_viaje.blade.php
     ===================================================================== --}}

{{-- Hidden fields que el backend espera --}}
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
                <div class="ig {{ $errors->has('nombre_tour') ? 'err-group' : '' }}">
                    <span class="ia"><i class="bi bi-briefcase"></i></span>
                    <input type="text" name="nombre_tour" id="nombre_tour"
                           value="{{ old('nombre_tour') }}"
                           class="fi {{ $errors->has('nombre_tour') ? 'err' : '' }}"
                           placeholder="Ej: Tours por Banos del Inca, Paquete Lima-Cusco 5D/4N"
                           required maxlength="200"
                           oninput="updateProgressSteps()"
                           data-validate="required" data-bloque="1">
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
                <div class="ig" style="position:relative">
                    <span class="ia"><i class="bi bi-geo-alt-fill"></i></span>
                    <input type="text" name="ciudad_destino" id="ciudad_destino"
                           value="{{ old('ciudad_destino') }}"
                           class="fi" placeholder="Ej: Banos del Inca, Cusco, Mancora..."
                           required maxlength="150" autocomplete="off"
                           oninput="onCiudadDestinoInput(this)"
                           data-validate="required" data-bloque="1">
                    <ul id="ciudad-destino-suggestions" class="city-suggestions"></ul>
                </div>
                <div id="depto-detectado" class="fhint" style="display:none;align-items:center;gap:.3rem">
                    <i class="bi bi-check-circle-fill" style="color:#16a34a"></i>
                    Departamento: <strong id="depto-nombre"></strong>
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
                                'Pasco','Piura','Puno','San Martin','Tacna','Tumbes','Ucayali'
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

        {{-- Fila 1: Arribo --}}
        <div class="fecha-hora-row">
            <div class="field">
                <label class="lbl">Fecha de arribo</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-calendar3"></i></span>
                    <input type="text" id="fecha_arribo_display"
                           class="fi" placeholder="Seleccionar..." readonly data-bloque="1">
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

        {{-- Fila 2: Retorno --}}
        <div class="fecha-hora-row" style="margin-top:.6rem">
            <div class="field">
                <label class="lbl">Fecha de retorno</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-calendar3"></i></span>
                    <input type="text" id="fecha_retorno_display"
                           class="fi" placeholder="Seleccionar..." readonly data-bloque="1">
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

        {{-- Terrestre --}}
        <div class="transport-fields {{ old('tipo_transporte') == 'terrestre' ? 'visible' : '' }}"
             id="transport-terrestre">
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

        {{-- Aereo --}}
        <div class="transport-fields {{ old('tipo_transporte') == 'aereo' ? 'visible' : '' }}"
             id="transport-aereo">
            <div class="g2">
                {{-- Aerolinea con lista predefinida --}}
                <div class="field">
                    <label class="lbl">Aerolinea</label>
                    <div class="ig">
                        <span class="ia"><i class="bi bi-airplane-engines"></i></span>
                        <select name="aerolinea" id="aerolinea" class="fi"
                                onchange="toggleAerolineaOtra(this)">
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
                           value="{{ old('aerolinea_otra') }}"
                           class="fi" placeholder="Nombre de la aerolinea"
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
                    <option value="hotel_2"   {{ old('tipo_establecimiento') == 'hotel_2'   ? 'selected':'' }}>Hotel 2 estrellas</option>
                    <option value="hotel_3"   {{ old('tipo_establecimiento') == 'hotel_3'   ? 'selected':'' }}>Hotel 3 estrellas</option>
                    <option value="hotel_4"   {{ old('tipo_establecimiento') == 'hotel_4'   ? 'selected':'' }}>Hotel 4 estrellas</option>
                    <option value="hotel_5"   {{ old('tipo_establecimiento') == 'hotel_5'   ? 'selected':'' }}>Hotel 5 estrellas</option>
                    <option value="hostal"    {{ old('tipo_establecimiento') == 'hostal'    ? 'selected':'' }}>Hostal</option>
                    <option value="apart"     {{ old('tipo_establecimiento') == 'apart'     ? 'selected':'' }}>Apart-hotel</option>
                    <option value="resort"    {{ old('tipo_establecimiento') == 'resort'    ? 'selected':'' }}>Resort</option>
                    <option value="ecolodge"  {{ old('tipo_establecimiento') == 'ecolodge'  ? 'selected':'' }}>Ecolodge</option>
                    <option value="albergue"  {{ old('tipo_establecimiento') == 'albergue'  ? 'selected':'' }}>Albergue / Hostel</option>
                </select>
            </div>
        </div>

        {{-- Habitaciones + Alimentacion + Camas --}}
        <div class="g3" style="margin-top:.65rem">
            {{-- Habitaciones acumulables --}}
            <div class="field">
                <label class="lbl">Tipo de habitacion</label>
                <div style="display:flex;gap:.5rem;align-items:center">
                    <div class="ig" style="flex:1">
                        <span class="ia"><i class="bi bi-door-closed"></i></span>
                        <select id="hab-tipo-selector" class="fi">
                            <option value="">— Tipo —</option>
                            <option value="sgl">SGL — Simple (1 persona)</option>
                            <option value="dbl">DBL — Doble matrimonial</option>
                            <option value="twn">TWN — Twin (2 camas)</option>
                            <option value="tpl">TPL — Triple</option>
                            <option value="qdl">QDL — Cuadruple</option>
                            <option value="fam">FAM — Familiar</option>
                            <option value="sui">SUI — Suite</option>
                        </select>
                    </div>
                    <button type="button" onclick="agregarHabitacion()"
                            style="padding:.45rem .9rem;border-radius:var(--r,6px);
                                   background:var(--adv-blue,#2563eb);color:#fff;border:none;
                                   font-size:.75rem;font-weight:700;cursor:pointer;
                                   display:flex;align-items:center;gap:.3rem;white-space:nowrap;flex-shrink:0">
                        <i class="bi bi-plus-lg"></i> Agregar
                    </button>
                </div>
                <div id="hab-lista" style="display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.45rem"></div>
                <input type="hidden" name="tipo_habitacion" id="tipo_habitacion_hidden"
                       value="{{ old('tipo_habitacion') }}">
                <div class="fhint" id="hab-resumen" style="margin-top:.2rem"></div>
            </div>

            {{-- Tipo de cama --}}
            <div class="field">
                <label class="lbl">Tipo de cama</label>
                <select name="tipo_cama" id="tipo_cama" class="fi">
                    <option value="">— Seleccionar —</option>
                    <option value="KB" {{ old('tipo_cama') == 'KB' ? 'selected':'' }}>KB — King Bed</option>
                    <option value="QB" {{ old('tipo_cama') == 'QB' ? 'selected':'' }}>QB — Queen Bed</option>
                    <option value="TB" {{ old('tipo_cama') == 'TB' ? 'selected':'' }}>TB — Twin Beds (separadas)</option>
                </select>
            </div>

            {{-- Plan de alimentacion --}}
            <div class="field">
                <label class="lbl">Plan de alimentacion</label>
                <select name="plan_alimentacion" id="plan_alimentacion" class="fi">
                    <option value="">— Seleccionar —</option>
                    <option value="RO" {{ old('plan_alimentacion') == 'RO' ? 'selected':'' }}>RO — Solo habitacion</option>
                    <option value="BB" {{ old('plan_alimentacion') == 'BB' ? 'selected':'' }}>BB — Con desayuno</option>
                    <option value="HB" {{ old('plan_alimentacion') == 'HB' ? 'selected':'' }}>HB — Desayuno y cena</option>
                    <option value="FB" {{ old('plan_alimentacion') == 'FB' ? 'selected':'' }}>FB — Pensión completa</option>
                    <option value="AI" {{ old('plan_alimentacion') == 'AI' ? 'selected':'' }}>AI — Todo incluido</option>
                </select>
            </div>
        </div>

    </div>
</div>

{{-- ESTILOS --}}
<style>
.fecha-hora-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: .75rem;
    align-items: start;
}
@media (max-width: 600px) {
    .fecha-hora-row { grid-template-columns: 1fr 1fr; }
    .fecha-hora-row .field:last-child { display: none; }
}
.city-suggestions {
    position:absolute;top:calc(100% + 2px);left:0;right:0;
    background:#fff;border:1px solid var(--border,#e2e8f0);
    border-radius:var(--r,6px);box-shadow:0 4px 16px rgba(0,0,0,.1);
    z-index:200;max-height:220px;overflow-y:auto;
    margin:0;padding:0;list-style:none;display:none;
}
.city-suggestions.open{display:block}
.city-suggestions li{
    padding:.45rem .75rem;font-size:.8rem;cursor:pointer;
    display:flex;justify-content:space-between;align-items:center;
    color:var(--ink-2,#334155);border-bottom:1px solid var(--border,#f1f5f9);
}
.city-suggestions li:last-child{border-bottom:none}
.city-suggestions li:hover{background:var(--adv-slate-l,#f8fafc)}
.cs-dep{font-size:.68rem;color:var(--ink-4,#94a3b8);font-weight:600}
.hab-chip{
    display:inline-flex;align-items:center;gap:.3rem;
    background:var(--adv-slate-l,#f1f5f9);border:1px solid var(--border,#e2e8f0);
    border-radius:99px;padding:.2rem .6rem .2rem .4rem;
    font-size:.72rem;font-weight:600;color:var(--ink-2,#334155);
}
.hab-qty{
    background:var(--adv-blue,#2563eb);color:#fff;border-radius:99px;
    min-width:18px;height:18px;display:inline-flex;align-items:center;
    justify-content:center;font-size:.65rem;font-weight:800;
}
.hab-remove{cursor:pointer;color:var(--ink-4,#94a3b8);margin-left:.1rem;line-height:1}
.hab-remove:hover{color:var(--adv-red,#ef4444)}
</style>

{{-- SCRIPT --}}
<script>
/* ── MAPA CIUDADES PERU → DEPARTAMENTO ── */
const CIUDADES_PERU = {
    'cajamarca':'Cajamarca','banos del inca':'Cajamarca','baños del inca':'Cajamarca',
    'los banos del inca':'Cajamarca','los baños del inca':'Cajamarca',
    'jaen':'Cajamarca','jaén':'Cajamarca','chota':'Cajamarca','cutervo':'Cajamarca',
    'celendin':'Cajamarca','celendín':'Cajamarca','bambamarca':'Cajamarca',
    'hualgayoc':'Cajamarca','contumaza':'Cajamarca','contumazá':'Cajamarca',
    'san ignacio':'Cajamarca','san marcos':'Cajamarca','pedro galvez':'Cajamarca',
    'santa cruz cajamarca':'Cajamarca','san pablo':'Cajamarca','san miguel':'Cajamarca',
    'lajas':'Cajamarca','llama':'Cajamarca','chalamarca':'Cajamarca',
    'chirinos':'Cajamarca','namballe':'Cajamarca','tabaconas':'Cajamarca',

    'lima':'Lima','miraflores':'Lima','san isidro':'Lima','barranco':'Lima',
    'surco':'Lima','chorrillos':'Lima','san borja':'Lima','la molina':'Lima',
    'ate':'Lima','san juan de lurigancho':'Lima','sjl':'Lima',
    'comas':'Lima','villa el salvador':'Lima','villa maria del triunfo':'Lima',
    'san martin de porres':'Lima','los olivos':'Lima','puente piedra':'Lima',
    'lurin':'Lima','lurín':'Lima','pachacamac':'Lima','chaclacayo':'Lima',
    'chosica':'Lima','huacho':'Lima','huaral':'Lima','barranca':'Lima',
    'chancay':'Lima','canete':'Lima','cañete':'Lima','chilca':'Lima',
    'mala':'Lima','asia':'Lima','pucusana':'Lima',
    'callao':'Callao','bellavista':'Callao','ventanilla':'Callao',

    'cusco':'Cusco','cuzco':'Cusco','qosqo':'Cusco',
    'machu picchu':'Cusco','aguas calientes':'Cusco',
    'pisac':'Cusco','pisaq':'Cusco','urubamba':'Cusco','ollantaytambo':'Cusco',
    'chinchero':'Cusco','quillabamba':'Cusco','sicuani':'Cusco','espinar':'Cusco',
    'calca':'Cusco','yucay':'Cusco','moray':'Cusco','maras':'Cusco',
    'andahuaylillas':'Cusco','raqchi':'Cusco','paucartambo':'Cusco',
    'ocongate':'Cusco','tinqui':'Cusco','kiteni':'Cusco',

    'arequipa':'Arequipa','mollendo':'Arequipa','camana':'Arequipa','camaná':'Arequipa',
    'majes':'Arequipa','chivay':'Arequipa','colca':'Arequipa','cotahuasi':'Arequipa',
    'aplao':'Arequipa','chuquibamba':'Arequipa','caraveli':'Arequipa','caravelí':'Arequipa',
    'ocona':'Arequipa','ocoña':'Arequipa','islay':'Arequipa','mejia':'Arequipa',
    'punta de bombon':'Arequipa','yanahuara':'Arequipa','cayma':'Arequipa',
    'socabaya':'Arequipa','hunter':'Arequipa','sachaca':'Arequipa',

    'piura':'Piura','sullana':'Piura','mancora':'Piura','máncora':'Piura',
    'paita':'Piura','talara':'Piura','catacaos':'Piura','chulucanas':'Piura',
    'huancabamba':'Piura','ayabaca':'Piura','morropon':'Piura','morropón':'Piura',
    'sechura':'Piura','bernal':'Piura','vice':'Piura','lobitos':'Piura',
    'negritos':'Piura','el alto piura':'Piura','cabo blanco':'Piura',
    'los organos':'Piura','los órganos':'Piura','vichayito':'Piura',

    'trujillo':'La Libertad','huanchaco':'La Libertad','chan chan':'La Libertad',
    'pacasmayo':'La Libertad','chepen':'La Libertad','chepén':'La Libertad',
    'otuzco':'La Libertad','santiago de chuco':'La Libertad',
    'viru':'La Libertad','virú':'La Libertad','ascope':'La Libertad',
    'casa grande':'La Libertad','guadalupe':'La Libertad',
    'laredo':'La Libertad','el porvenir':'La Libertad',
    'la esperanza':'La Libertad','huamachuco':'La Libertad',
    'florencia de mora':'La Libertad',

    'huaraz':'Ancash','chimbote':'Ancash','nuevo chimbote':'Ancash',
    'caraz':'Ancash','yungay':'Ancash','carhuaz':'Ancash','recuay':'Ancash',
    'huari':'Ancash','casma':'Ancash','huarmey':'Ancash',
    'llanganuco':'Ancash','pastoruri':'Ancash',
    'chavin de huantar':'Ancash','chavín de huántar':'Ancash',
    'pomabamba':'Ancash','sihuas':'Ancash','pallasca':'Ancash',
    'chiquian':'Ancash','ocros':'Ancash','aija':'Ancash',

    'huancayo':'Junin','tarma':'Junin','la merced':'Junin',
    'satipo':'Junin','jauja':'Junin','concepcion':'Junin','concepción':'Junin',
    'chupaca':'Junin','chanchamayo':'Junin','pichanaqui':'Junin',
    'san ramon':'Junin','san ramón':'Junin','mazamari':'Junin',

    'puno':'Puno','juliaca':'Puno','ilave':'Puno',
    'desaguadero':'Puno','yunguyo':'Puno','lago titicaca':'Puno','titicaca':'Puno',
    'azangaro':'Puno','azángaro':'Puno','ayaviri':'Puno','lampa':'Puno',
    'sandia':'Puno','macusani':'Puno','moho':'Puno','juli':'Puno',
    'pomata':'Puno','huancane':'Puno','huancané':'Puno',
    'capachica':'Puno','amantani':'Puno','taquile':'Puno','uros':'Puno',
    'chucuito':'Puno',

    'puerto maldonado':'Madre de Dios','tambopata':'Madre de Dios',
    'manu':'Madre de Dios','boca manu':'Madre de Dios',
    'iñapari':'Madre de Dios','iberia':'Madre de Dios',

    'iquitos':'Loreto','yurimaguas':'Loreto','requena':'Loreto',
    'nauta':'Loreto','contamana':'Loreto','caballococha':'Loreto',

    'tarapoto':'San Martin','moyobamba':'San Martin','rioja':'San Martin',
    'lamas':'San Martin','juanjui':'San Martin','tocache':'San Martin',
    'saposoa':'San Martin','uchiza':'San Martin','sauce':'San Martin',
    'chazuta':'San Martin','picota':'San Martin','nueva cajamarca':'San Martin',

    'chachapoyas':'Amazonas','bagua':'Amazonas','kuelap':'Amazonas',
    'luya':'Amazonas','bagua grande':'Amazonas','lamud':'Amazonas',
    'cocachimba':'Amazonas','gocta':'Amazonas',

    'huanuco':'Huanuco','huánuco':'Huanuco','tingo maria':'Huanuco',
    'leoncio prado':'Huanuco','ambo':'Huanuco','llata':'Huanuco',

    'pucallpa':'Ucayali','aguaytia':'Ucayali','atalaya':'Ucayali',

    'ica':'Ica','nazca':'Ica','nasca':'Ica','paracas':'Ica',
    'pisco':'Ica','chincha':'Ica','palpa':'Ica','marcona':'Ica',

    'chiclayo':'Lambayeque','lambayeque':'Lambayeque','ferrenafe':'Lambayeque',
    'monsefu':'Lambayeque','monsefú':'Lambayeque','motupe':'Lambayeque',
    'olmos':'Lambayeque','jayanca':'Lambayeque','pimentel':'Lambayeque',
    'eten':'Lambayeque','etén':'Lambayeque',

    'tumbes':'Tumbes','zorritos':'Tumbes','punta sal':'Tumbes',
    'zarumilla':'Tumbes','aguas verdes':'Tumbes','corrales':'Tumbes',

    'tacna':'Tacna','tarata':'Tacna','candarave':'Tacna','locumba':'Tacna',

    'moquegua':'Moquegua','ilo':'Moquegua','omate':'Moquegua',
    'torata':'Moquegua','ubinas':'Moquegua',

    'ayacucho':'Ayacucho','huanta':'Ayacucho','huancapi':'Ayacucho',
    'puquio':'Ayacucho','coracora':'Ayacucho',

    'abancay':'Apurimac','andahuaylas':'Apurimac','chalhuanca':'Apurimac',
    'chincheros':'Apurimac',

    'huancavelica':'Huancavelica','lircay':'Huancavelica',
    'acobamba':'Huancavelica','pampas':'Huancavelica',

    'cerro de pasco':'Pasco','oxapampa':'Pasco','villa rica':'Pasco',
    'pozuzo':'Pasco','yanahuanca':'Pasco',
};

function _norm(s){ return s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,''); }

let _ciudadTimer = null;
function onCiudadDestinoInput(inp) {
    updateProgressSteps();
    const val = inp.value.trim();
    clearTimeout(_ciudadTimer);
    const key = _norm(val);
    let dep = CIUDADES_PERU[val.toLowerCase()] || CIUDADES_PERU[key] || null;
    if (!dep && val.length >= 3) {
        for (const [c,d] of Object.entries(CIUDADES_PERU)) {
            if (_norm(c).startsWith(key) || c.toLowerCase().startsWith(val.toLowerCase())) { dep = d; break; }
        }
    }
    _setDepto(dep);
    if (val.length >= 2) _ciudadTimer = setTimeout(() => _mostrarSug(val), 200);
    else _cerrarSug();
}

function _setDepto(dep) {
    const sel  = document.getElementById('departamento_destino');
    const hint = document.getElementById('depto-detectado');
    const nom  = document.getElementById('depto-nombre');
    if (dep) {
        if (sel)  sel.value = dep;
        if (hint) hint.style.display = 'flex';
        if (nom)  nom.textContent = dep;
    } else {
        if (hint) hint.style.display = 'none';
    }
}

function _mostrarSug(query) {
    const q    = _norm(query);
    const cont = document.getElementById('ciudad-destino-suggestions');
    if (!cont) return;
    const matches = []; const vistos = new Set();
    for (const [c,d] of Object.entries(CIUDADES_PERU)) {
        if ((_norm(c).includes(q) || c.toLowerCase().includes(query.toLowerCase())) && !vistos.has(c)) {
            vistos.add(c);
            const lbl = c.split(' ').map(w => w.charAt(0).toUpperCase()+w.slice(1)).join(' ');
            matches.push({lbl, dep:d});
            if (matches.length >= 6) break;
        }
    }
    if (!matches.length) { _cerrarSug(); return; }
    cont.innerHTML = matches.map(m =>
        `<li onclick="seleccionarCiudad('${m.lbl}','${m.dep}')">
            <span>${m.lbl}</span><span class="cs-dep">${m.dep}</span>
         </li>`
    ).join('');
    cont.classList.add('open');
}

function seleccionarCiudad(ciudad, dep) {
    const inp = document.getElementById('ciudad_destino');
    if (inp) inp.value = ciudad;
    _setDepto(dep);
    _cerrarSug();
    updateProgressSteps();
}

function _cerrarSug() {
    const c = document.getElementById('ciudad-destino-suggestions');
    if (c) { c.classList.remove('open'); c.innerHTML = ''; }
}

document.addEventListener('click', e => {
    if (!e.target.closest('#ciudad_destino') && !e.target.closest('#ciudad-destino-suggestions')) _cerrarSug();
});

/* ── AEROLINEA OTRA ── */
function toggleAerolineaOtra(sel) {
    const otro = document.getElementById('aerolinea_otra');
    if (otro) otro.style.display = sel.value === 'otra' ? 'block' : 'none';
}

/* ── N/D HORAS ── */
function toggleNDHora(chk, displayId, hiddenId) {
    const display = document.getElementById(displayId);
    const hidden  = document.getElementById(hiddenId);
    const label   = chk.closest('.nd-check');
    if (chk.checked) {
        if (display) { display.value = 'N/D'; display.disabled = true; display.style.background = 'var(--adv-slate-l,#f1f5f9)'; }
        if (hidden)  hidden.value = 'N/D';
        if (label)   label.classList.add('active');
        if (display && display._flatpickr) display._flatpickr.destroy();
    } else {
        if (display) { display.value = ''; display.disabled = false; display.style.background = ''; }
        if (hidden)  hidden.value = '';
        if (label)   label.classList.remove('active');
        if (display) flatpickr('#' + displayId, {
            enableTime: true, noCalendar: true, dateFormat: 'h:i K', time_24hr: false, allowInput: false,
            onChange(sel, str, inst) {
                const h = document.getElementById(hiddenId);
                if (h) h.value = inst.formatDate(sel[0], 'H:i');
                updateProgressSteps();
            }
        });
    }
    updateProgressSteps();
}

/* ── HABITACIONES ACUMULABLES ── */
const _habitaciones = {};
const HAB_LABELS = {
    'sgl':'SGL — Simple','dbl':'DBL — Doble matrimonial',
    'twn':'TWN — Twin','tpl':'TPL — Triple',
    'qdl':'QDL — Cuadruple','fam':'FAM — Familiar','sui':'SUI — Suite',
};

function agregarHabitacion() {
    const sel  = document.getElementById('hab-tipo-selector');
    const tipo = sel?.value;
    if (!tipo) {
        if (sel) { sel.classList.add('err'); setTimeout(() => sel.classList.remove('err'), 1200); }
        return;
    }
    _habitaciones[tipo] = (_habitaciones[tipo] || 0) + 1;
    _renderHabs();
    sel.value = '';
}

function quitarHabitacion(tipo) {
    if (!_habitaciones[tipo]) return;
    _habitaciones[tipo]--;
    if (_habitaciones[tipo] <= 0) delete _habitaciones[tipo];
    _renderHabs();
}

function _renderHabs() {
    const lista   = document.getElementById('hab-lista');
    const hidden  = document.getElementById('tipo_habitacion_hidden');
    const resumen = document.getElementById('hab-resumen');
    if (!lista) return;
    lista.innerHTML = '';
    const partes = [];
    for (const [tipo, qty] of Object.entries(_habitaciones)) {
        const lbl  = HAB_LABELS[tipo] || tipo;
        const chip = document.createElement('div');
        chip.className = 'hab-chip';
        chip.innerHTML =
            `<span class="hab-qty">${qty}</span>` +
            `<span>${lbl}</span>` +
            `<span class="hab-remove" onclick="quitarHabitacion('${tipo}')" title="Quitar uno"><i class="bi bi-x"></i></span>`;
        lista.appendChild(chip);
        partes.push(`${lbl} x${qty}`);
    }
    const str = partes.join(', ');
    if (hidden)  hidden.value = str;
    if (resumen) resumen.textContent = str || '';
    updateProgressSteps();
}

document.addEventListener('DOMContentLoaded', function() {
    // N/D horas
    ['hora_arribo','hora_retorno'].forEach(id => {
        const chk = document.getElementById('nd_' + id);
        if (chk && chk.checked) toggleNDHora(chk, id + '_display', id);
    });
    // Habitaciones old()
    const habOld = document.getElementById('tipo_habitacion_hidden')?.value || '';
    if (habOld) {
        const inv = {};
        for (const [k,v] of Object.entries(HAB_LABELS)) inv[v.toLowerCase()] = k;
        habOld.split(',').forEach(parte => {
            const m = parte.trim().match(/^(.+?)\s*x(\d+)$/i);
            if (!m) return;
            const tipo = inv[m[1].trim().toLowerCase()] || m[1].trim().toLowerCase();
            const qty  = parseInt(m[2], 10);
            if (tipo && qty > 0) _habitaciones[tipo] = qty;
        });
        _renderHabs();
    }
    // Ciudad old()
    const ciudadInp = document.getElementById('ciudad_destino');
    if (ciudadInp && ciudadInp.value) onCiudadDestinoInput(ciudadInp);
    // Aerolinea old()
    const aerSel = document.getElementById('aerolinea');
    if (aerSel && aerSel.value === 'otra') {
        const otro = document.getElementById('aerolinea_otra');
        if (otro) otro.style.display = 'block';
    }
});
</script>