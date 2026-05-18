/**
 * UBICACIÓN: public/js/reservas-create.js
 * DESCRIPCIÓN: JavaScript completo del formulario de nueva reserva.
 * VERSIÓN: 9.0 — CORREGIDO: estado_inicial, barra de progreso mejorada,
 * validación de submit, campos requeridos correctos.
 */

/* ══════════════════════════════════════════════════════════════
   PAÍSES — códigos telefónicos
══════════════════════════════════════════════════════════════ */
const TOTAL_BLOQUES = 5;
const _prevStates = {};

function getBloqueStatus(n) {
    switch(n) {
        case 1: {
            const srv  = (document.getElementById('nombre_tour')?.value || '').trim();
            const prc  = parseFloat(document.getElementById('precio_tour')?.value) || 0;
            const dest = (document.getElementById('ciudad_destino')?.value || '').trim();
            const orig = (document.getElementById('ciudad_procedencia')?.value || '').trim();
            const canal = (document.getElementById('canal_contacto')?.value || '').trim();
            if (!srv || !dest || !orig || !canal) return 'incomplete';
            if (prc <= 0) return 'error';
            return 'done';
        }
        case 2: {
            const nombre = (document.getElementById('titular_nombre')?.value || '').trim();
            const tel    = (document.getElementById('titular_telefono')?.value || '').trim();
            if (!nombre || !tel) return 'incomplete';
            const solo = document.getElementById('solo-pasajero');
            if (!solo?.checked) {
                const cards = document.querySelectorAll('#pax-lista .pax-card');
                if (!cards.length) return 'incomplete';
                let ok = true;
                cards.forEach(c => {
                    const idx = c.id.replace('pax-','');
                    if (!(document.getElementById('pax-nombre-'+idx)?.value || '').trim()) ok = false;
                });
                if (!ok) return 'incomplete';
            }
            return 'done';
        }
        case 3: {
            const chk = document.getElementById('salud-master-toggle');
            if (chk?.checked) return 'done';
            if (chk?.dataset.touched === '1') return 'done';
            const seguro = document.getElementById('titular_seguro_salud')?.value;
            if (seguro) return 'done';
            return 'incomplete';
        }
        case 4: {
    const comp = (document.getElementById('tipo_comprobante')?.value || '').trim();
    if (!comp) return 'incomplete';
    const met = (document.getElementById('metodo_pago')?.value || '').trim();
    const mon = parseFloat(document.getElementById('monto_pagado_inicial')?.value) || 0;
    // En edición el pago es opcional — si método está vacío igual se considera completo
    if (met && mon <= 0) return 'incomplete';
    return 'done';
}
        case 5:
            return (document.getElementById('politica_descripcion')?.value || '').trim().length >= 20
                ? 'done' : 'incomplete';
        default: return 'incomplete';
    }
}

function updateProgressSteps() {
    let doneCount = 0;
    const activeIdx = getActiveBloqueIdx();
    for (let i = 1; i <= TOTAL_BLOQUES; i++) {
        const ps    = document.getElementById('ps-'+i);
        const fb    = document.getElementById('bloque-'+i);
        const badge = document.getElementById('fb-status-'+i);
        const tpsStep = document.getElementById('tps-step-'+i);
        if (!ps || !fb) continue;
        const status = getBloqueStatus(i);
        const wasDone = _prevStates[i] === 'done';
        ps.classList.remove('done','has-error','active');
        fb.classList.remove('has-errors','is-complete');
        if (badge) { badge.style.background = 'var(--adv-blue)'; badge.innerHTML = String(i); }
        if (tpsStep) tpsStep.classList.remove('done','has-error','active','just-done');
        if (status === 'done') {
            ps.classList.add('done'); fb.classList.add('is-complete');
            if (badge) badge.innerHTML = '<i class="bi bi-check2" style="font-size:.8rem"></i>';
            if (tpsStep) {
                tpsStep.classList.add('done');
                if (!wasDone) { tpsStep.classList.add('just-done'); setTimeout(()=>tpsStep.classList.remove('just-done'),500); }
            }
            doneCount++;
        } else if (status === 'error') {
            ps.classList.add('has-error'); fb.classList.add('has-errors');
            if (badge) { badge.style.background='var(--adv-red)'; badge.innerHTML='<i class="bi bi-exclamation-lg" style="font-size:.7rem"></i>'; }
            if (tpsStep) tpsStep.classList.add('has-error');
        }
        if (i === activeIdx) { ps.classList.add('active'); if(tpsStep) tpsStep.classList.add('active'); }
        _prevStates[i] = status;
    }
    const pct = Math.round((doneCount / TOTAL_BLOQUES) * 100);
    const pFill = document.getElementById('ps-fill');
    const pPct  = document.getElementById('ps-pct');
    const pDone = document.getElementById('ps-done-count');
    if (pFill) pFill.style.width = pct + '%';
    if (pPct)  pPct.textContent  = pct + '%';
    if (pDone) pDone.textContent = doneCount + '/' + TOTAL_BLOQUES;
    const tFill  = document.getElementById('top-progress-fill');
    const tPct   = document.getElementById('top-progress-pct');
    const tLabel = document.getElementById('top-progress-label');
    if (tFill) tFill.style.width = pct + '%';
    if (tPct)  tPct.textContent  = pct + '%';
    if (tLabel) {
        if (pct === 100)     tLabel.textContent = '✅ Formulario completo — listo para guardar';
        else if (pct >= 80)  tLabel.textContent = '¡Casi listo! Solo faltan algunos detalles';
        else if (pct >= 40)  tLabel.textContent = 'Completando formulario...';
        else                 tLabel.textContent = 'Completa los campos requeridos';
    }
}
const PHONE_FORMATS = {
    '+51':  { len:9,  fmt:[3,3,3]     }, // Perú        999 999 999
    '+54':  { len:10, fmt:[2,4,4]     }, // Argentina   99 9999 9999
    '+55':  { len:11, fmt:[2,5,4]     }, // Brasil      99 99999 9999
    '+56':  { len:9,  fmt:[1,4,4]     }, // Chile       9 9999 9999
    '+57':  { len:10, fmt:[3,3,4]     }, // Colombia    999 999 9999
    '+593': { len:9,  fmt:[2,3,4]     }, // Ecuador     99 999 9999
    '+591': { len:8,  fmt:[4,4]       }, // Bolivia     9999 9999
    '+595': { len:9,  fmt:[3,3,3]     }, // Paraguay    999 999 999
    '+598': { len:8,  fmt:[4,4]       }, // Uruguay     9999 9999
    '+58':  { len:10, fmt:[3,3,4]     }, // Venezuela   999 999 9999
    '+52':  { len:10, fmt:[3,3,4]     }, // México      999 999 9999
    '+1':   { len:10, fmt:[3,3,4]     }, // EE.UU.      999 999 9999
    '+34':  { len:9,  fmt:[3,3,3]     }, // España      999 999 999
    '+39':  { len:10, fmt:[3,4,3]     }, // Italia      999 9999 999
    '+33':  { len:9,  fmt:[1,2,2,2,2] }, // Francia     9 99 99 99 99
    '+49':  { len:11, fmt:[4,3,4]     }, // Alemania    9999 999 9999
    '+44':  { len:10, fmt:[4,3,3]     }, // R.Unido     9999 999 999
    '+81':  { len:10, fmt:[2,4,4]     }, // Japón       99 9999 9999
    '+86':  { len:11, fmt:[3,4,4]     }, // China       999 9999 9999
}

function initPhoneDropdowns() {
    ['phone-dd-1','phone-dd-2','phone-dd-3'].forEach(ddId => {
        const ul = document.getElementById(ddId)?.querySelector('ul');
        if (ul) renderPhoneList(ul, ddId, '');
    });
}

function renderPhoneList(ul, ddId, filter) {
    const f = filter.toLowerCase();
    const items = PHONE_COUNTRIES.filter(c =>
        !f || c.name.toLowerCase().includes(f) || c.code.includes(f)
    );
    ul.innerHTML = items.map(c =>
        `<li onclick="selectPhoneCountry('${ddId}','${c.code}','${c.flag}',${c.len},'${c.start||''}')">` +
        `<span class="flag-emoji">${c.flag}</span> ${c.name}` +
        `<span style="color:var(--ink-4);margin-left:auto">${c.code}</span></li>`
    ).join('');
}

function togglePhoneDropdown(ddId) {
    const dd = document.getElementById(ddId);
    if (!dd) return;
    const isOpen = dd.classList.contains('open');
    document.querySelectorAll('.phone-dropdown').forEach(d => d.classList.remove('open'));
    if (!isOpen) {
        dd.classList.add('open');
        dd.querySelector('.phone-dropdown-search')?.focus();
    }
}

function filterPhoneList(searchInput, ddId) {
    const ul = document.getElementById(ddId)?.querySelector('ul');
    if (ul) renderPhoneList(ul, ddId, searchInput.value);
}

function selectPhoneCountry(ddId, code, flag, len, start) {
    const suffix = ddId.split('-').pop();
    const flagEl = document.getElementById('phone-flag-' + suffix);
    const codeEl = document.getElementById('phone-code-' + suffix);
    if (flagEl) flagEl.textContent = flag;
    if (codeEl) codeEl.textContent = code;

    const hiddenMap = {
        '1': 'titular_telefono_codigo',
        '2': 'titular_telefono2_codigo',
        '3': 'emergencia_telefono_codigo'
    };
    const hid = document.getElementById(hiddenMap[suffix]);
    if (hid) hid.value = code;

    const btnEl = document.getElementById('phone-code-btn-' + suffix);
    if (btnEl) { btnEl.dataset.len = len; btnEl.dataset.start = start || ''; }

    document.getElementById(ddId)?.classList.remove('open');
    updateProgressSteps();
}

function renderPhoneList(ul, ddId, filter) {
    const FLAGS = {
        '+51':'🇵🇪','+54':'🇦🇷','+55':'🇧🇷','+56':'🇨🇱','+57':'🇨🇴',
        '+593':'🇪🇨','+591':'🇧🇴','+595':'🇵🇾','+598':'🇺🇾','+58':'🇻🇪',
        '+52':'🇲🇽','+1':'🇺🇸','+34':'🇪🇸','+39':'🇮🇹','+33':'🇫🇷',
        '+49':'🇩🇪','+44':'🇬🇧','+81':'🇯🇵','+86':'🇨🇳',
    };
    const NAMES = {
        '+51':'Perú','+54':'Argentina','+55':'Brasil','+56':'Chile',
        '+57':'Colombia','+593':'Ecuador','+591':'Bolivia','+595':'Paraguay',
        '+598':'Uruguay','+58':'Venezuela','+52':'México','+1':'EE.UU./Canadá',
        '+34':'España','+39':'Italia','+33':'Francia','+49':'Alemania',
        '+44':'Reino Unido','+81':'Japón','+86':'China',
    };
    const f = filter.toLowerCase();
    const items = Object.entries(PHONE_FORMATS).filter(([code]) => {
        if (!f) return true;
        return (NAMES[code]||'').toLowerCase().includes(f) || code.includes(f);
    });
    ul.innerHTML = items.map(([code]) =>
        `<li onclick="selectPhoneCountry('${ddId}','${code}','${FLAGS[code]||''}',${PHONE_FORMATS[code].len},'')">` +
        `<span class="flag-emoji">${FLAGS[code]||''}</span> ${NAMES[code]||code}` +
        `<span style="color:var(--ink-4);margin-left:auto">${code}</span></li>`
    ).join('');
}

function validatePhone(inp, codeElId) {
    const code = document.getElementById(codeElId)?.textContent?.trim() || '+51';
    const cfg  = PHONE_FORMATS[code] || { len:10, fmt:[3,3,4] };
    const raw  = inp.value.replace(/\D/g, '').substring(0, cfg.len);
    let out = '', pos = 0;
    for (let i = 0; i < cfg.fmt.length; i++) {
        const chunk = raw.substr(pos, cfg.fmt[i]);
        if (!chunk) break;
        if (pos > 0) out += ' ';
        out += chunk;
        pos += cfg.fmt[i];
    }
    inp.value = out;
}

/* ══════════════════════════════════════════════════════════════
   INIT — DOMContentLoaded
══════════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {

    initPhoneDropdowns();
    // Flatpickr — fecha arribo
    flatpickr('#fecha_arribo_display', {
        locale: 'es', dateFormat: 'd/m/Y', allowInput: false,
        onChange(sel, str, inst) {
            const h = document.getElementById('fecha_arribo');
            if (h) h.value = inst.formatDate(sel[0], 'Y-m-d');
            // Sincronizar fecha_tour con fecha_arribo
            const ft = document.getElementById('fecha_tour');
            if (ft) ft.value = inst.formatDate(sel[0], 'Y-m-d');
            calcDias(); updateProgressSteps();
        },
        onReady(sel, str, inst) {
            const v = document.getElementById('fecha_arribo')?.value;
            if (v && v !== 'N/D') inst.setDate(v, false, 'Y-m-d');
        }
    });

    // Flatpickr — fecha retorno
    flatpickr('#fecha_retorno_display', {
        locale: 'es', dateFormat: 'd/m/Y', allowInput: false,
        onChange(sel, str, inst) {
            const h = document.getElementById('fecha_retorno');
            if (h) h.value = inst.formatDate(sel[0], 'Y-m-d');
            calcDias(); updateProgressSteps();
        },
        onReady(sel, str, inst) {
            const v = document.getElementById('fecha_retorno')?.value;
            if (v && v !== 'N/D') inst.setDate(v, false, 'Y-m-d');
        }
    });

    // Flatpickr — horas
['hora_arribo', 'hora_retorno', 'hora_salida_vuelo', 'hora_llegada_vuelo'].forEach(id => {
    const displayEl = document.getElementById(id + '_display');
    if (!displayEl) return;
    const hidden = document.getElementById(id);
    if (hidden && hidden.value === 'N/D') return; // ignorar si es N/D
    flatpickr('#' + id + '_display', {
        enableTime: true, noCalendar: true,
        dateFormat: 'h:i K', time_24hr: false, allowInput: false,
        onChange(sel, str, inst) {
            const h = document.getElementById(id);
            if (h) h.value = inst.formatDate(sel[0], 'H:i');
            if (id === 'hora_arribo') {
                const hs = document.getElementById('hora_salida');
                if (hs) hs.value = inst.formatDate(sel[0], 'H:i');
            }
            updateProgressSteps();
        },
        onReady(sel, str, inst) {
            const v = document.getElementById(id)?.value;
            if (v && v !== 'N/D') inst.setDate(v);
        }
    });
});

// N/D checkboxes
['nd_fecha_arribo', 'nd_fecha_retorno'].forEach(id => {
    const chk = document.getElementById(id);
    if (chk && chk.checked) {
        const field = id.replace('nd_', '');
        toggleND(chk, field + '_display', field);
    }
});

    togFactura();
    calcTotal();
    loadEmailOld();
    updOpHint();
    calcDias();
    toggleTransporte();

    // ── CORRECCIÓN: inicializar estado_inicial desde el select (no desde radios) ──
    const estadoSelect = document.getElementById('estado_inicial_select');
    if (estadoSelect) {
        const hidden = document.getElementById('estado_inicial_hidden');
        if (hidden && hidden.value) estadoSelect.value = hidden.value;
    }

    // Mantener compatibilidad con radio buttons si existen en el bloque de pago
    const estRadios = document.querySelectorAll('[name="estado_inicial"][type="radio"]');
    estRadios.forEach(r => {
        if (r.checked) r.closest('.eo-compact')?.classList.add('sel');
    });

    // Nombre titular → salud
    const tiNombre = document.getElementById('titular_nombre');
    if (tiNombre) actualizarNombreTitularSalud(tiNombre.value);

    // Política old()
    const tipoOld = document.getElementById('politica_tipo')?.value;
    if (tipoOld) {
        document.getElementById('btn-politica-tour')?.classList.toggle('active', tipoOld === 'tours');
        document.getElementById('btn-politica-viaje')?.classList.toggle('active', tipoOld === 'viajes');
    }

    // Auto-resize textareas
    document.querySelectorAll('textarea.fi').forEach(ta => {
        ta.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });

    // Validación blur
    document.querySelectorAll('[data-validate]').forEach(inp => {
        inp.addEventListener('blur', () => validateField(inp));
        inp.addEventListener('input', () => {
            if (inp.classList.contains('err')) validateField(inp);
            else updateProgressSteps();
        });
    });

    // Progreso reactivo
    document.querySelectorAll('input, select, textarea').forEach(el => {
        el.addEventListener('change', updateProgressSteps);
        el.addEventListener('input', updateProgressSteps);
    });
    window.addEventListener('scroll', updateProgressSteps, { passive: true });

    // Notif visual
    document.querySelectorAll('.notif-item').forEach(item => {
        const cb = item.querySelector('input[type="checkbox"]');
        if (cb && cb.checked) item.classList.add('checked');
    });

    // Solo pasajero
    const soloChk = document.getElementById('solo-pasajero');
    if (soloChk) toggleSoloPasajero(soloChk);

    // Salud master
    const saludChk = document.getElementById('salud-master-toggle');
    if (saludChk) {
        _updateSaludMasterVisual(saludChk.checked);
        if (saludChk.checked) saludChk.dataset.touched = '1';
    }
    updateProgressSteps();
});
function toggleND(chk, displayId, hiddenId) {
    const display = document.getElementById(displayId);
    const hidden  = document.getElementById(hiddenId);
    const label   = chk.closest('.nd-check');
    if (chk.checked) {
        if (display) { display.value = 'N/D'; display.disabled = true; display.style.background = 'var(--adv-slate-l)'; }
        if (hidden)  hidden.value = 'N/D';
        if (label)   label.classList.add('active');
    } else {
        if (display) { display.value = ''; display.disabled = false; display.style.background = ''; }
        if (hidden)  hidden.value = '';
        if (label)   label.classList.remove('active');
    }
    calcDias();
    updateProgressSteps();
}

/* ══════════════════════════════════════════════════════════════
   DÍAS AUTOMÁTICOS
══════════════════════════════════════════════════════════════ */
function calcDias() {
    const arribo  = document.getElementById('fecha_arribo')?.value;
    const retorno = document.getElementById('fecha_retorno')?.value;
    const diasEl  = document.getElementById('dias_calculados');
    if (!diasEl) return;
    if (!arribo || !retorno || arribo === 'N/D' || retorno === 'N/D') {
        diasEl.value = ''; return;
    }
    const d1   = new Date(arribo  + 'T00:00:00');
    const d2   = new Date(retorno + 'T00:00:00');
    const diff = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));
    diasEl.value = diff >= 0 ? diff + (diff === 1 ? ' día' : ' días') : 'Fechas inválidas';
}

/* ══════════════════════════════════════════════════════════════
   TRANSPORTE CONDICIONAL
══════════════════════════════════════════════════════════════ */
function toggleTransporte() {
    const tipo = document.getElementById('tipo_transporte')?.value || '';
    document.getElementById('transport-terrestre')?.classList.toggle('visible', tipo === 'terrestre');
    document.getElementById('transport-aereo')?.classList.toggle('visible',    tipo === 'aereo');
}

/* ══════════════════════════════════════════════════════════════
   EDAD AUTOMÁTICA
══════════════════════════════════════════════════════════════ */
function calcEdad(isoDate) {
    const badge = document.getElementById('edad-badge');
    if (!badge) return;
    const fNac = isoDate || document.getElementById('titular_fecha_nacimiento_iso')?.value;
    if (!fNac) { badge.innerHTML = ''; return; }
    const hoy = new Date(), nac = new Date(fNac + 'T00:00:00');
    let edad = hoy.getFullYear() - nac.getFullYear();
    const m = hoy.getMonth() - nac.getMonth();
    if (m < 0 || (m === 0 && hoy.getDate() < nac.getDate())) edad--;
    if (edad < 0 || edad > 120) { badge.innerHTML = ''; return; }
    const etapa = edad < 2 ? 'Bebé' : edad < 12 ? 'Niño/a' : edad < 18 ? 'Adolescente' : edad < 60 ? 'Adulto/a' : 'Adulto mayor';
    badge.innerHTML = `<span class="age-badge"><i class="bi bi-person-check"></i><span class="age-num">${edad}</span><span style="font-size:.7rem;font-weight:600">&nbsp;${edad === 1 ? 'año' : 'años'} · ${etapa}</span></span>`;
}

function _setText(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
}

function calcTotalFromCustom() {
    calcTotal();
}

/* ══════════════════════════════════════════════════════════════
   CÁLCULO DE TOTALES
   CORRECCIÓN: 'personalizado' ya no es un estado válido para el servidor.
   Se mapea a 'mitad_pago' internamente.
══════════════════════════════════════════════════════════════ */
function getEstadoInicial() {
    const radio = document.querySelector('[name="estado_inicial"][type="radio"]:checked');
    if (radio) return radio.value;
    const sel = document.getElementById('estado_inicial_select');
    if (sel && sel.value) return sel.value;
    return document.getElementById('estado_inicial_hidden')?.value || 'mitad_pago';
}
function calcTotal() {
    const precio  = parseFloat(document.getElementById('precio_tour')?.value) || 0;
    const estado  = getEstadoInicial();
    const mpInp   = document.getElementById('monto_pagado_inicial');

    let pagado;

    if (estado === 'pagado') {
        pagado = precio;
        if (mpInp) { mpInp.value = precio > 0 ? precio.toFixed(2) : ''; mpInp.readOnly = true; }
    } else if (estado === 'personalizado') {
        pagado = parseFloat(mpInp?.value) || 0;
        if (mpInp) mpInp.readOnly = false;
    } else {
        pagado = +(precio * 0.5).toFixed(2);
        if (mpInp) { mpInp.value = precio > 0 ? pagado.toFixed(2) : ''; mpInp.readOnly = true; }
    }

    const saldo = +(Math.max(0, precio - pagado)).toFixed(2);
    const pct   = precio > 0 ? Math.round((pagado / precio) * 100) : 0;
    const fmt   = v => 'S/ ' + v.toFixed(2);

    // ── Precio de referencia en bloque 4 ──
    const refEl = document.getElementById('ref-precio-total');
    if (refEl) refEl.textContent = fmt(precio);

    _setText('sp-total', fmt(precio));
    _setText('sp-adel',  fmt(pagado));
    _setText('sp-saldo', fmt(saldo));

    const porEl = document.getElementById('porcentaje_pagado');
    const salEl = document.getElementById('saldo_restante');
    if (porEl) porEl.value = precio > 0 ? pct + '%' : '';
    if (salEl) salEl.value = precio > 0 ? fmt(saldo) : '';

    const sbTotal = document.getElementById('sb-total');
    if (sbTotal) {
        const span = sbTotal.querySelector('span');
        sbTotal.textContent = fmt(precio) + ' ';
        if (span) sbTotal.appendChild(span);
    }

    const hint = document.getElementById('calc-monto-txt');
    if (hint) {
    if (precio <= 0) {
        hint.textContent = '';
       } else if (estado === 'personalizado') {
        hint.textContent = pct > 0 ? `${pct}% pagado` : '';
      } else if (estado === 'pagado') {
        hint.textContent = '100% pagado';
      } else {
        hint.textContent = '50% pagado';
      }
     }
    const badge = document.getElementById('sp-estado-badge');
    if (badge) {
        if (estado === 'pagado') {
            badge.className = 'sp-status-badge paid';
            badge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Pagado completo';
        } else if (estado === 'personalizado') {
            badge.className = 'sp-status-badge';
            badge.innerHTML = `<i class="bi bi-pencil-square"></i> ${pct}% Pagado`;
        } else {
            badge.className = 'sp-status-badge';
            badge.innerHTML = '<i class="bi bi-hourglass-split"></i> 50% Pagado';
        }
    }

    const tipoPago = document.getElementById('tipo_pago');
    if (tipoPago) tipoPago.value = estado === 'pagado' ? 'pago_completo' : 'adelanto';

    updateProgressSteps();
}
/* ══════════════════════════════════════════════════════════════
   ESTADO DE PAGO (radio buttons en bloque de pago)
══════════════════════════════════════════════════════════════ */
function selEst(lbl) {
    document.querySelectorAll('.eo-compact').forEach(el => el.classList.remove('sel'));
    lbl.classList.add('sel');
    const radio = lbl.querySelector('input[type="radio"]');
    radio.checked = true;

    // ── CORRECCIÓN: sincronizar con el hidden del bloque 1 ──
    let val = radio.value;
    // Mapear 'personalizado' a 'mitad_pago' para el servidor
    const serverVal = (val === 'personalizado') ? 'mitad_pago' : val;
    const hidden = document.getElementById('estado_inicial_hidden');
    if (hidden) hidden.value = serverVal;
    // Sincronizar select del bloque 1
    const sel = document.getElementById('estado_inicial_select');
    if (sel) sel.value = serverVal;

    calcTotal();
}
/* ══════════════════════════════════════════════════════════════
   DOCUMENTO — tipo y búsqueda
══════════════════════════════════════════════════════════════ */
function onDocInput(inp) {
    const tipo = document.getElementById('titular_tipo_documento')?.value || 'DNI';
    if      (tipo === 'DNI')  inp.value = inp.value.replace(/\D/g,'').substring(0,8);
    else if (tipo === 'CE')   inp.value = inp.value.replace(/\D/g,'').substring(0,12);
    else if (tipo === 'RUC')  inp.value = inp.value.replace(/\D/g,'').substring(0,11);
    else                      inp.value = inp.value.toUpperCase().substring(0,15);
    updateProgressSteps();
}

function onTipoDocChange() {
    const inp  = document.getElementById('titular_numero_documento');
    const tipo = document.getElementById('titular_tipo_documento')?.value || 'DNI';
    inp.value  = '';
    const cfg = {
        DNI:      { max:8,  mode:'numeric', ph:'8 dígitos' },
        CE:       { max:12, mode:'numeric', ph:'12 dígitos' },
        RUC:      { max:11, mode:'numeric', ph:'11 dígitos' },
        PASAPORTE:{ max:15, mode:'text',    ph:'Alfanumérico' }
    };
    const c = cfg[tipo] || cfg.PASAPORTE;
    inp.maxLength = c.max; inp.inputMode = c.mode; inp.placeholder = c.ph;
    hideDniResult();
}

/* ══════════════════════════════════════════════════════════════
   RENIEC / SUNAT
══════════════════════════════════════════════════════════════ */
async function buscarPorDoc() {
    const doc  = (document.getElementById('titular_numero_documento')?.value || '').trim();
    const tipo = document.getElementById('titular_tipo_documento')?.value || 'DNI';
    const btn  = document.getElementById('btn-lookup');
 
    if (!doc) { showDniResult('err', 'Ingresa el número.'); return; }
    if (tipo === 'DNI' && doc.length !== 8)  { showDniResult('err', 'DNI debe tener 8 dígitos.'); return; }
    if (tipo === 'RUC' && doc.length !== 11) { showDniResult('err', 'RUC debe tener 11 dígitos.'); return; }
 
    setLookupLoading(btn, true, 'btn-lookup');
    showDniResult('load', '<span class="spinner"></span> Consultando...');
 
    try {
        const url  = tipo === 'RUC' ? '/api/buscar-ruc/' + doc : '/api/buscar-dni/' + doc;
        const data = await fetchJSON(url);
 
        // El proxy puede devolver nombre o nombre_completo según ReniecService
        const nombre = data?.nombre_completo || data?.nombre || null;
 
        if (data?.success && nombre) {
            aplicarDatosPersona({ ...data, nombre });
 
            // Si es RUC también rellenar razón social
            if (tipo === 'RUC') {
                const rs = document.getElementById('razon_social');
                if (rs) rs.value = nombre.toUpperCase();
            }
        } else {
            const msg = data?.error || data?.message || 'No encontrado. Ingresa manualmente.';
            showDniResult('err', msg);
        }
    } catch (err) {
        console.error('buscarPorDoc:', err);
        showDniResult('err', 'Sin conexión. Ingresa manualmente.');
    } finally {
        setLookupLoading(btn, false, 'btn-lookup');
    }
}

let _rucTimer = null;
function onRucInput(inp) {
    inp.value = inp.value.replace(/\D/g,'').substring(0,11);
    clearTimeout(_rucTimer);
    if (inp.value.length === 11) _rucTimer = setTimeout(buscarRUC, 700);
}

async function buscarRUC() {
    const ruc = (document.getElementById('ruc_factura')?.value || '').trim();
    const btn = document.getElementById('btn-ruc-lookup');
    if (!ruc || ruc.length !== 11) { showRucResult('err','RUC debe tener 11 dígitos.'); return; }
    setLookupLoading(btn, true, 'btn-ruc-lookup');
    showRucResult('load','<span class="spinner"></span> Consultando SUNAT...');
    try {
        const local = await fetchJSON('/api/buscar-ruc/'+ruc);
        if (local && (local.razon_social || local.nombre)) {
            const rs = (local.razon_social || local.nombre).toUpperCase();
            const rsInp = document.getElementById('razon_social');
            if (rsInp) rsInp.value = rs;
            showRucResult('ok','<i class="bi bi-check-circle-fill"></i> '+rs);
            return;
        }
        const ext = await fetchJSON('https://api.apis.net.pe/v2/sunat/ruc?numero='+ruc);
        if (ext?.razonSocial) {
            const rs = ext.razonSocial.toUpperCase();
            const rsInp = document.getElementById('razon_social');
            if (rsInp) rsInp.value = rs;
            showRucResult('ok','<i class="bi bi-check-circle-fill"></i> '+rs);
        } else {
            showRucResult('err','No encontrado.');
        }
    } catch { showRucResult('err','Error de conexión.'); }
    finally   { setLookupLoading(btn, false, 'btn-ruc-lookup'); }
}

async function buscarPaxDNI(i) {
    const doc = (document.getElementById('pd-' + i)?.value || '').trim();
    const btn = document.getElementById('btn-pax-lookup-' + i);
    const res = document.getElementById('pax-dni-result-' + i);
 
    if (!doc || doc.length !== 8) {
        if (res) { res.className = 'dni-result visible err'; res.innerHTML = 'DNI debe tener 8 dígitos.'; }
        return;
    }
 
    if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner" style="width:10px;height:10px;border-width:2px"></span>'; }
    if (res) { res.className = 'dni-result visible load'; res.innerHTML = '<span class="spinner"></span> Consultando...'; }
 
    try {
        const data = await fetchJSON('/api/buscar-dni/' + doc);
 
        // El proxy puede devolver nombre o nombre_completo según ReniecService
        const nombre = data?.nombre_completo || data?.nombre || null;
 
        if (data?.success && nombre) {
            const n = nombre.toUpperCase();
            const ni = document.getElementById('pax-nombre-' + i);
            if (ni) { ni.value = n; ni.dispatchEvent(new Event('input')); }
            if (res) { res.className = 'dni-result visible ok'; res.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + n; }
            updateProgressSteps();
        } else {
            const msg = data?.error || data?.message || 'No encontrado — ingresa el nombre manualmente.';
            if (res) { res.className = 'dni-result visible err'; res.innerHTML = msg; }
        }
    } catch (err) {
        console.error('buscarPaxDNI error:', err);
        if (res) { res.className = 'dni-result visible err'; res.innerHTML = 'Error al consultar. Ingresa el nombre manualmente.'; }
    } finally {
        if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-search"></i>'; }
    }
}

function aplicarDatosPersona(data) {
    const nombre = (data.nombre || '').toUpperCase();
    const ni = document.getElementById('titular_nombre');
    if (ni) { ni.value = nombre; actualizarNombreTitularSalud(nombre); }
    const ci = document.getElementById('cliente_id');
    if (ci && data.cliente_id) ci.value = data.cliente_id;
    showDniResult('ok','<i class="bi bi-check-circle-fill"></i> '+nombre);
    onTitularChange(); 
    updateProgressSteps();
    updateProgressSteps();
}

async function fetchJSON(url) {
    try {
        const r = await fetch(url, { headers: { 'Accept':'application/json','X-Requested-With':'XMLHttpRequest' } });
        if (!r.ok) return null;
        return await r.json();
    } catch { return null; }
}

function setLookupLoading(btn, loading, id) {
    if (!btn) return;
    btn.disabled = loading;
    btn.innerHTML = loading
        ? '<span class="spinner" style="width:10px;height:10px;border-width:2px;margin-right:4px"></span> Buscando'
        : (id === 'btn-ruc-lookup' ? '<i class="bi bi-search"></i> SUNAT' : '<i class="bi bi-search"></i> Buscar');
}

function showDniResult(t,m) { const el=document.getElementById('dni-result'); if(el){el.className='dni-result visible '+t;el.innerHTML=m;} }
function hideDniResult()    { const el=document.getElementById('dni-result'); if(el) el.className='dni-result'; }
function showRucResult(t,m) { const el=document.getElementById('ruc-result'); if(el){el.className='dni-result visible '+t;el.innerHTML=m;} }

/* ══════════════════════════════════════════════════════════════
   EMAIL WIDGET
══════════════════════════════════════════════════════════════ */
const DOMS = ['gmail.com','hotmail.com','outlook.com','yahoo.com','icloud.com','live.com'];

function emailInput() {
    const u  = (document.getElementById('email-user')?.value || '').trim();
    const dl = document.getElementById('domain-list');
    if (!u || !dl) { closeDomains(); joinEmail(); return; }
    dl.innerHTML = DOMS.map(d =>
        `<li onclick="pickDomain('${d}')"><i class="bi bi-envelope me-1"></i>${d}</li>`
    ).join('') + '<li onclick="closeDomains()" style="color:var(--ink-4);font-style:italic">Escribir otro</li>';
    dl.classList.add('open');
    joinEmail();
}

function handleEmailPaste(e) {
    const p = (e.clipboardData || window.clipboardData).getData('text').trim();
    if (p.includes('@')) {
        e.preventDefault();
        const pts = p.split('@');
        if (pts.length === 2 && pts[0] && pts[1]) {
            const u = document.getElementById('email-user');
            const d = document.getElementById('email-domain');
            if (u) u.value = pts[0];
            if (d) d.value = pts[1];
            joinEmail(); closeDomains();
            onTitularChange(); 
        }
    }
}

function pickDomain(v) { 
    const d = document.getElementById('email-domain'); 
    if(d) d.value=v; 
    closeDomains(); 
    joinEmail(); 
    onTitularChange();
}
function closeDomains() { document.getElementById('domain-list')?.classList.remove('open'); joinEmail(); }
function joinEmail() {
    const u = (document.getElementById('email-user')?.value   || '').trim();
    const d = (document.getElementById('email-domain')?.value || '').trim();
    const h = document.getElementById('titular_email');
    if (u.includes('@')) {
        h.value = u;
        return;
    }
    h.value = (u && d) ? u + '@' + d : '';
}
function loadEmailOld() {
    const raw = document.getElementById('titular_email')?.value || '';
    if (!raw.includes('@')) return;
    const pts = raw.split('@');
    if (pts.length === 2) {
        const u = document.getElementById('email-user');
        const d = document.getElementById('email-domain');
        if (u) u.value = pts[0];
        if (d) d.value = pts[1];
    }
}

/* ══════════════════════════════════════════════════════════════
   UPLOAD VOUCHER
══════════════════════════════════════════════════════════════ */
let voucherAdjunto = false;

function onFile(e) { const f = e.target.files?.[0]; if (f) mostrarPreview(f); }

function onDrop(e) {
    e.preventDefault();
    document.getElementById('uz')?.classList.remove('over');
    const f = e.dataTransfer?.files?.[0];
    if (!f) return;
    try { const dt = new DataTransfer(); dt.items.add(f); document.getElementById('archivo_baucher').files = dt.files; } catch(_) {}
    mostrarPreview(f);
}

function mostrarPreview(f) {
    if (f.size > 5 * 1024 * 1024) { alert('El archivo supera 5 MB.'); return; }
    const uz   = document.getElementById('uz');
    const fprev= document.getElementById('fprev');
    const img  = document.getElementById('prev-img');
    const name = document.getElementById('prev-name');
    if (uz)   uz.style.display = 'none';
    if (name) name.textContent = f.name;
    if (img) {
        if (f.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = ev => { img.src = ev.target.result; img.style.display = 'block'; };
            reader.readAsDataURL(f);
        } else { img.src = ''; img.style.display = 'none'; }
    }
    if (fprev) fprev.classList.add('visible');
    voucherAdjunto = true;
    updateProgressSteps();
}

function removeFile() {
    const inp  = document.getElementById('archivo_baucher');
    const uz   = document.getElementById('uz');
    const fprev= document.getElementById('fprev');
    const img  = document.getElementById('prev-img');
    if (inp)   inp.value = '';
    if (fprev) fprev.classList.remove('visible');
    if (uz)    { uz.style.display = ''; uz.style.border = ''; }
    if (img)   img.src = '';
    voucherAdjunto = false;
    updateProgressSteps();
}

/* ══════════════════════════════════════════════════════════════
   NOMBRE TITULAR → SALUD
══════════════════════════════════════════════════════════════ */
function actualizarNombreTitularSalud(n) {
    const el = document.getElementById('salud-titular-nombre');
    if (el) el.textContent = n || 'nombre del titular';
}

/* ══════════════════════════════════════════════════════════════
   NOTIFICACIONES
══════════════════════════════════════════════════════════════ */
function toggleNotif(item, cbId) {
    const cb = document.getElementById(cbId);
    if (!cb) return;
    item.classList.toggle('checked', cb.checked);
}

/* ══════════════════════════════════════════════════════════════
   SOLO PASAJERO
══════════════════════════════════════════════════════════════ */
function toggleSoloPasajero(chk) {
    const sec = document.getElementById('pax-seccion');
    const msg = document.getElementById('solo-msg');
    if (chk.checked) {
        if (sec) sec.style.display = 'none';
        if (msg) msg.style.display = 'block';
    } else {
        if (sec) sec.style.display = '';
        if (msg) msg.style.display = 'none';
    }
    updateProgressSteps();
}

/* ══════════════════════════════════════════════════════════════
   SALUD MASTER TOGGLE
══════════════════════════════════════════════════════════════ */
function toggleSaludMaster(chk) {
    chk.dataset.touched = '1';
    _updateSaludMasterVisual(chk.checked);
    const h = document.getElementById('salud-sin-condiciones-hidden');
    if (h) h.value = chk.checked ? '1' : '0';
    updateProgressSteps();
}

function _updateSaludMasterVisual(isOn) {
    const banner    = document.getElementById('salud-master-banner');
    const label     = document.getElementById('salud-master-label');
    const badge     = document.getElementById('salud-master-badge');
    const icon      = document.getElementById('salud-master-icon');
    const lista     = document.getElementById('salud-lista');
    const confirmEl = document.getElementById('salud-confirm-msg');
    if (!banner) return;
    if (isOn) {
        banner.classList.remove('has-conditions');
        if (label)     label.textContent  = 'Ningún pasajero tiene condiciones médicas';
        if (badge)     badge.textContent  = 'Sin condiciones';
        if (icon)      icon.className     = 'bi bi-shield-check';
        if (lista)     lista.style.display = 'none';
        if (confirmEl) confirmEl.style.display = 'block';
    } else {
        banner.classList.add('has-conditions');
        if (label)     label.textContent  = 'Algún pasajero tiene alergias o condiciones';
        if (badge)     badge.textContent  = 'Con condiciones';
        if (icon)      icon.className     = 'bi bi-exclamation-triangle';
        if (lista)     lista.style.display = '';
        if (confirmEl) confirmEl.style.display = 'none';
    }
}

/* ══════════════════════════════════════════════════════════════
   PASAJEROS ADICIONALES
══════════════════════════════════════════════════════════════ */
let pN = 0;

function calcPaxEdad(i, iso) {
    const badge = document.getElementById('pax-edad-badge-' + i);
    if (!badge) return;
    if (!iso) { badge.innerHTML = ''; return; }
    const hoy = new Date(), nac = new Date(iso + 'T00:00:00');
    let edad = hoy.getFullYear() - nac.getFullYear();
    const m = hoy.getMonth() - nac.getMonth();
    if (m < 0 || (m === 0 && hoy.getDate() < nac.getDate())) edad--;
    if (edad < 0 || edad > 120) { badge.innerHTML = ''; return; }
    // ── Guardar edad en hidden ──
    const edadInput = document.getElementById('pax-edad-' + i);
    if (edadInput) edadInput.value = edad;
    // ── Actualizar tipo según edad ──
    const tipoInput = document.getElementById('pax-tipo-' + i);
    let tipo = 'adulto';
    if (edad < 2)        tipo = 'bebe';
    else if (edad < 12)  tipo = 'nino';
    else if (edad < 18)  tipo = 'adolescente';
    else if (edad >= 60) tipo = 'adulto_mayor';
    if (tipoInput) tipoInput.value = tipo;
    const etapa = edad < 2 ? 'Bebé' : edad < 12 ? 'Niño/a' : edad < 18 ? 'Adolescente' : edad < 60 ? 'Adulto/a' : 'Adulto mayor';
    badge.innerHTML = `<span style="font-size:.68rem;color:var(--adv-blue);font-weight:600"><i class="bi bi-person-check me-1"></i>${edad} años · ${etapa}</span>`;
}

function addPax() {
    const lista = document.getElementById('pax-lista');
    if (!lista) return;
    const i   = pN++;
    const num = i + 2; // Pasajero 2, 3, 4...
    const d   = document.createElement('div');
    d.className = 'pax-card'; d.id = 'pax-' + i;
    d.innerHTML = `
        <div class="pax-head">
            <span><i class="bi bi-person me-1"></i>Pasajero ${num}</span>
            <button type="button" class="pax-del" onclick="removePax(${i})">
                <i class="bi bi-x"></i>
            </button>
        </div>
 
        <div class="g3" style="padding:.65rem .65rem .4rem">
            <div class="field">
                <label class="lbl">Tipo de documento</label>
                <select name="pasajeros[${i}][tipo_documento]"
                        id="pax-tipodoc-${i}" class="fi">
                    <option value="DNI">DNI</option>
                    <option value="CE">C. Extranjería</option>
                    <option value="PASAPORTE">Pasaporte</option>
                </select>
            </div>
 
            <div class="field">
                <label class="lbl">N° Documento</label>
                <div class="dni-wrap">
                    <div class="dni-row">
                        <span class="ia"><i class="bi bi-card-text"></i></span>
                        <input type="text"
                               name="pasajeros[${i}][numero_documento]"
                               id="pd-${i}"
                               class="fi" placeholder="Número" maxlength="12"
                               inputmode="numeric"
                               oninput="this.value=this.value.replace(/\\D/g,'').substring(0,12)">
                        <button type="button" class="btn-dni-lookup"
                                id="btn-pax-lookup-${i}"
                                onclick="buscarPaxDNI(${i})"
                                title="Buscar en RENIEC">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <div class="dni-result" id="pax-dni-result-${i}"></div>
                </div>
            </div>
 
            <div class="field">
                <label class="lbl">Nombre completo <span class="req">*</span></label>
                <input type="text"
                       name="pasajeros[${i}][nombre_completo]"
                       id="pax-nombre-${i}"
                       class="fi" placeholder="NOMBRES APELLIDOS"
                       oninput="this.value=this.value.toUpperCase();updateProgressSteps()"
                       required>
            </div>
 
            <div class="field">
                <label class="lbl">Tipo de pasajero</label>
                <select name="pasajeros[${i}][tipo]"
                        id="pax-tipo-sel-${i}" class="fi"
                        onchange="document.getElementById('pax-tipo-${i}').value=this.value">
                    <option value="adulto">Adulto</option>
                    <option value="adolescente">Adolescente</option>
                    <option value="nino">Niño</option>
                    <option value="bebe">Bebé</option>
                    <option value="adulto_mayor">Adulto mayor</option>
                </select>
            </div>
 
            <div class="field">
                <label class="lbl">Fecha de nacimiento</label>
                <input type="text" id="pax-fnac-display-${i}"
                       class="fi" placeholder="DD/MM/AAAA" readonly>
                <input type="hidden" name="pasajeros[${i}][fecha_nacimiento]"
                       id="pax-fnac-${i}">
                <div id="pax-edad-badge-${i}" style="margin-top:.3rem"></div>
            </div>
 
            <input type="hidden" name="pasajeros[${i}][edad]" id="pax-edad-${i}" value="">
        </div>
        <input type="hidden" name="pasajeros[${i}][tipo]" id="pax-tipo-${i}" value="adulto">
 
        <!-- ── SEGURO siempre visible (sin abrir salud) ── -->
        <div class="seguro-row">
            <span class="seguro-lbl">
                <i class="bi bi-shield-plus" style="color:#0369a1"></i>
                Seguro de salud
            </span>
            <select name="pasajeros[${i}][seguro_salud]"
                    class="fi seguro-sel" style="flex:1;font-size:.78rem;padding:.3rem .5rem;">
                <option value="">Sin seguro</option>
                <option value="essalud">EsSalud</option>
                <option value="sis">SIS</option>
                <option value="eps">EPS privada</option>
                <option value="ffaa">FFAA / PNP</option>
                <option value="otro">Otro</option>
            </select>
        </div>
 
        <!-- ── Switch condiciones de salud ── -->
        <div class="salud-sw-bar">
            <span class="salud-sw-title">
                <i class="bi bi-heart-pulse" style="color:#b45309"></i>
                Condiciones de salud
                <span class="salud-hint">alergias, restricciones, discapacidad</span>
            </span>
            <label class="sw-label">
                <div class="pax-sw-track" id="pax-sw-track-${i}">
                    <div class="pax-sw-thumb"></div>
                </div>
                <input type="checkbox" id="pax-salud-sw-${i}" style="display:none"
                       onchange="toggleSaludSw(this,'pax-salud-body-${i}','pax-sw-track-${i}','pax-sw-lbl-${i}')">
                <span class="pax-sw-lbl" id="pax-sw-lbl-${i}">Sin condiciones</span>
            </label>
        </div>
 
        <!-- ── Panel salud (se despliega) ── -->
        <div id="pax-salud-body-${i}"
             style="display:none;padding:.8rem .65rem;
                    border-top:1px solid var(--border,#e2e8f0);
                    background:#fffdf7">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem">
 
                <div class="field">
                    <label class="lbl">Alergias</label>
                    <div class="yn-grp">
                        <button type="button" class="yn-btn yn-no active"
                                data-target="pax-alerg-${i}" onclick="togYNPax(this,${i},'alergias')">No</button>
                        <button type="button" class="yn-btn yn-si"
                                data-target="pax-alerg-${i}" onclick="togYNPax(this,${i},'alergias')">Sí</button>
                    </div>
                    <input type="hidden" name="pasajeros[${i}][tiene_alergias]"
                           id="pax-tiene-alergias-${i}" value="no">
                    <div id="pax-alerg-${i}" style="display:none;margin-top:.4rem">
                        <textarea name="pasajeros[${i}][alergias_detalle]" class="fi" rows="2"
                                  placeholder="Medicamentos, alimentos..."></textarea>
                    </div>
                </div>
 
                <div class="field">
                    <label class="lbl">Restricciones alimentarias</label>
                    <div class="yn-grp">
                        <button type="button" class="yn-btn yn-no active"
                                data-target="pax-rest-${i}" onclick="togYN(this)">No</button>
                        <button type="button" class="yn-btn yn-si"
                                data-target="pax-rest-${i}" onclick="togYN(this)">Sí</button>
                    </div>
                    <div id="pax-rest-${i}" style="display:none;margin-top:.4rem">
                        <textarea name="pasajeros[${i}][restricciones]" class="fi" rows="2"
                                  placeholder="Sin gluten, vegano..."></textarea>
                    </div>
                </div>
 
                <div class="field" style="grid-column:1/-1">
                    <label class="lbl">Discapacidad</label>
                    <div class="yn-grp">
                        <button type="button" class="yn-btn yn-no active"
                                data-target="pax-discap-${i}" onclick="togYN(this)">No</button>
                        <button type="button" class="yn-btn yn-si"
                                data-target="pax-discap-${i}" onclick="togYN(this)">Sí</button>
                    </div>
                    <div id="pax-discap-${i}" style="display:none;margin-top:.4rem" data-discap-wrap>
                        <div class="discap-chips">
                            <span class="discap-chip" data-val="motora"         onclick="togDiscap(this)">Motora</span>
                            <span class="discap-chip" data-val="visual"         onclick="togDiscap(this)">Visual</span>
                            <span class="discap-chip" data-val="auditiva"       onclick="togDiscap(this)">Auditiva</span>
                            <span class="discap-chip" data-val="cognitiva"      onclick="togDiscap(this)">Cognitiva</span>
                            <span class="discap-chip" data-val="habla_lenguaje" onclick="togDiscap(this)">Habla/Lenguaje</span>
                            <span class="discap-chip" data-val="psicosocial"    onclick="togDiscap(this)">Psicosocial</span>
                            <span class="discap-chip" data-val="otro"           onclick="togDiscap(this)">Otro</span>
                        </div>
                        <input type="hidden" name="pasajeros[${i}][discapacidades]" value="">
                        <input type="text" class="fi discap-otro"
                               name="pasajeros[${i}][discapacidad_otro]"
                               placeholder="Especifica..." style="display:none;margin-top:.35rem">
                    </div>
                </div>
 
                <div class="field" style="grid-column:1/-1">
                    <label class="lbl">Observaciones médicas</label>
                    <div class="yn-grp">
                        <button type="button" class="yn-btn yn-no active"
                                data-target="pax-obs-${i}" onclick="togYN(this)">No</button>
                        <button type="button" class="yn-btn yn-si"
                                data-target="pax-obs-${i}" onclick="togYN(this)">Sí</button>
                    </div>
                    <div id="pax-obs-${i}" style="display:none;margin-top:.4rem">
                        <textarea name="pasajeros[${i}][obs_medicas]" class="fi" rows="2"
                                  placeholder="Condiciones crónicas, medicamentos..."></textarea>
                    </div>
                </div>
 
            </div>
        </div>
    `;
    lista.appendChild(d);
 
    // Flatpickr fecha nacimiento
    flatpickr('#pax-fnac-display-' + i, {
        locale: 'es', dateFormat: 'd/m/Y', maxDate: 'today', allowInput: false,
        onChange(sel, str, inst) {
            const iso = inst.formatDate(sel[0], 'Y-m-d');
            const hidFnac = document.getElementById('pax-fnac-' + i);
            if (hidFnac) hidFnac.value = iso;
            const hoy = new Date(), nac = new Date(iso);
            let edad = hoy.getFullYear() - nac.getFullYear();
            const m = hoy.getMonth() - nac.getMonth();
            if (m < 0 || (m === 0 && hoy.getDate() < nac.getDate())) edad--;
            const hidEdad = document.getElementById('pax-edad-' + i);
            if (hidEdad) hidEdad.value = edad >= 0 ? edad : '';
            const tipoSel = document.getElementById('pax-tipo-sel-' + i);
            const tipoHid = document.getElementById('pax-tipo-' + i);
            if (tipoSel && tipoHid) {
                let tipo = 'adulto';
                if (edad < 2)        tipo = 'bebe';
                else if (edad < 12)  tipo = 'nino';
                else if (edad < 18)  tipo = 'adolescente';
                else if (edad >= 65) tipo = 'adulto_mayor';
                tipoSel.value = tipo;
                tipoHid.value = tipo;
            }
            calcPaxEdad(i, iso);
        }
    });
 
    const tipoSelEl = document.getElementById('pax-tipo-sel-' + i);
    if (tipoSelEl) {
        tipoSelEl.addEventListener('change', function() {
            const hid = document.getElementById('pax-tipo-' + i);
            if (hid) hid.value = this.value;
        });
    }
 
    paxCnt();
    updateProgressSteps();
}
function removePax(i) {
    document.getElementById('pax-' + i)?.remove();
    paxCnt();
    updateProgressSteps();
}
 function paxCnt() {
    const n     = document.querySelectorAll('#pax-lista .pax-card').length;
    const total = n + 1; // +1 = titular
    const cnt   = document.getElementById('pax-cnt');
    if (cnt) cnt.textContent = n > 0
        ? total + ' pasajeros en total (titular + ' + n + ' adicional' + (n > 1 ? 'es' : '') + ')'
        : '';
    const sp = document.getElementById('sp-pax-txt');
    if (sp)  sp.textContent = 'Total: ' + total + ' pasajero' + (total > 1 ? 's' : '');
    const sb = document.getElementById('sb-pasajeros');
    if (sb)  sb.textContent = total > 1 ? '· ' + total + ' pax' : '';
    
}
function togYNPax(btn, idx, campo) {
    const grp = btn.closest('.yn-grp');
    grp?.querySelectorAll('.yn-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const isSi = btn.classList.contains('yn-si');
    const det  = document.getElementById(btn.dataset.target);
    if (det) det.style.display = isSi ? 'block' : 'none';
    if (!isSi && det) det.querySelectorAll('textarea,input[type="text"]').forEach(t => t.value = '');
 
    if (campo === 'alergias') {
        const hid = document.getElementById('pax-tiene-alergias-' + idx);
        if (hid) hid.value = isSi ? 'si' : 'no';
    }
}
/* ══════════════════════════════════════════════════════════════
   ALERGIAS TOGGLE
══════════════════════════════════════════════════════════════ */
function togAlergPax(lbl, taId) {
    lbl.closest('.sw-group')?.querySelectorAll('.sw-btn').forEach(b => b.classList.remove('sel'));
    lbl.classList.add('sel');
    lbl.querySelector('input').checked = true;
    const wrap = document.getElementById(taId+'-wrap');
    const show = lbl.querySelector('input').value === 'si';
    if (wrap) wrap.style.display = show ? 'block' : 'none';
    if (!show) { const ta = document.getElementById(taId); if(ta) ta.value = ''; }
    updateProgressSteps();
}

/* ══════════════════════════════════════════════════════════════
   FACTURA / HINT PAGO
══════════════════════════════════════════════════════════════ */
function togFactura() {
    const esF = document.getElementById('tipo_comprobante')?.value === 'factura';
    const cf  = document.getElementById('campos-factura');
    if (cf) cf.style.display = esF ? 'block' : 'none';
}

function updOpHint() {
    const v = document.getElementById('metodo_pago')?.value || '';
    const h = document.getElementById('op-hint');
    if (!h) return;
    if (['yape','plin','tunki'].includes(v))                h.textContent = 'Número de operación en la app';
    else if (v.startsWith('transf') || v.startsWith('dep')) h.textContent = 'N° constancia bancaria';
    else if (v.startsWith('tarjeta'))                       h.textContent = 'Últimos 4 dígitos o voucher POS';
    else                                                    h.textContent = 'Código de referencia (opcional)';
}

/* ══════════════════════════════════════════════════════════════
   POLÍTICAS
══════════════════════════════════════════════════════════════ */
function cargarPolitica(tipo) {
    const ta    = document.getElementById('politica_descripcion');
    const badge = document.getElementById('politica-loaded-badge');
    const hid   = document.getElementById('politica_tipo');
    document.getElementById('btn-politica-tour')?.classList.toggle('active',  tipo === 'tours');
    document.getElementById('btn-politica-viaje')?.classList.toggle('active', tipo === 'viajes');
    if (typeof window.POLITICAS_RESERVA !== 'undefined' && window.POLITICAS_RESERVA[tipo]) {
        if (ta)  { ta.value = window.POLITICAS_RESERVA[tipo]; ta.classList.add('ok-val'); }
        if (hid) hid.value = tipo;
        if (badge) { badge.classList.add('visible'); setTimeout(() => badge.classList.remove('visible'), 3000); }
        updateProgressSteps();
    } else {
        fetchJSON('/api/politicas/'+tipo).then(data => {
            if (ta)  { ta.value = data?.contenido || 'Políticas para: '+tipo; }
            if (hid) hid.value = tipo;
            updateProgressSteps();
        });
    }
}

/* ══════════════════════════════════════════════════════════════
   NAVEGACIÓN SIDEBAR
══════════════════════════════════════════════════════════════ */
function scrollToBloque(n) {
    const b = document.getElementById('bloque-'+n);
    if (!b) return;
    b.scrollIntoView({ behavior: 'smooth', block: 'start' });
    b.style.boxShadow = '0 0 0 3px rgba(26,86,219,.25), var(--sh)';
    setTimeout(() => { b.style.boxShadow = ''; }, 1200);
}
function getActiveBloqueIdx() {
    let c = 1, d = Infinity;
    for (let i = 1; i <= TOTAL_BLOQUES; i++) {
        const el = document.getElementById('bloque-'+i);
        if (!el) continue;
        const r = el.getBoundingClientRect();
        if (r.top < window.innerHeight && r.bottom > 0) {
            const dd = Math.abs(r.top - 100);
            if (dd < d) { d = dd; c = i; }
        }
    }
    return c;
}

/* ══════════════════════════════════════════════════════════════
   VALIDACIÓN BLUR
══════════════════════════════════════════════════════════════ */
function validateField(input) {
    const rules = (input.dataset.validate || '').split('|');
    let error = '';
    for (const rule of rules) {
        if (rule === 'required'  && !input.value.trim())                            { error = 'Campo obligatorio.'; break; }
        if (rule === 'numeric'   && input.value && isNaN(parseFloat(input.value))) { error = 'Valor numérico.'; break; }
        if (rule === 'positive'  && input.value && parseFloat(input.value) <= 0)   { error = 'Mayor a cero.'; break; }
        if (rule === 'phone'     && input.value && input.value.length < 7)          { error = 'Número incompleto.'; break; }
    }
    input.classList.remove('err','ok-val');
    input.closest('.ig')?.classList.remove('err-group');
    let errEl = input.closest('.field')?.querySelector('.ferr.live-err');
    if (!errEl) {
        errEl = document.createElement('div');
        errEl.className = 'ferr live-err';
        (input.closest('.field') || input.parentElement).appendChild(errEl);
    }
    if (error) {
        input.classList.add('err');
        input.closest('.ig')?.classList.add('err-group');
        errEl.innerHTML = '<i class="bi bi-exclamation-circle"></i> ' + error;
        errEl.style.display = 'flex';
    } else if (input.value.trim()) {
        input.classList.add('ok-val');
        errEl.style.display = 'none';
    } else {
        errEl.style.display = 'none';
    }
    updateProgressSteps();
}

/* ══════════════════════════════════════════════════════════════
   SUBMIT
   CORRECCIÓN: solo validar campos que realmente existen en el form.
   fecha_tour y hora_salida se manejan como hidden con valores por defecto.
══════════════════════════════════════════════════════════════ */
document.getElementById('form-reserva')?.addEventListener('submit', function(e) {
    // Limpiar N/D antes de enviar
    ['hora_arribo','hora_retorno','hora_salida_vuelo','hora_llegada_vuelo',
     'fecha_arribo','fecha_retorno'].forEach(id => {
        const h = document.getElementById(id);
        if (h && h.value === 'N/D') h.value = '';
    });

    joinEmail();

    // ── Garantizar estado_inicial válido antes de enviar ──
    const estadoSel = document.getElementById('estado_inicial_select');
    const estadoHid = document.getElementById('estado_inicial_hidden');
    if (estadoSel && estadoHid) {
        let val = estadoSel.value || estadoHid.value || 'mitad_pago';
        if (!['mitad_pago','pagado','cancelada'].includes(val)) val = 'mitad_pago';
        estadoHid.value = val;
    }

    // ── Garantizar fecha_tour y hora_salida ──
    const ft = document.getElementById('fecha_tour');
    if (ft && !ft.value) ft.value = new Date().toISOString().split('T')[0];
    const hs = document.getElementById('hora_salida');
    if (hs && !hs.value) hs.value = '08:00';

    // ── Validar campos visibles requeridos ──
    const reqs = [
        'titular_nombre','titular_telefono',
        'nombre_tour','precio_tour',
        'ciudad_destino','ciudad_procedencia',
        'canal_contacto',
        'tipo_comprobante',
        'politica_descripcion'
    ];
    let valid = true;

    reqs.forEach(id => {
        const f = document.getElementById(id) || document.querySelector('[name="'+id+'"]');
        if (!f) return;
        const cf = document.getElementById('campos-factura');
        if (cf && cf.contains(f) && cf.style.display === 'none') return;
        if (!f.value || !f.value.trim()) {
            valid = false;
            f.classList.add('err');
            f.closest('.ig')?.classList.add('err-group');
        }
    });

    const mi  = document.getElementById('monto_pagado_inicial');
const met = (document.getElementById('metodo_pago')?.value || '').trim();
// Solo validar monto si se seleccionó método de pago
if (met && mi && (!mi.value || parseFloat(mi.value) <= 0)) {
    valid = false;
    mi.classList.add('err');
}

    // Voucher: ahora es opcional en el cliente (el servidor tampoco lo requiere)
    // Solo mostrar advertencia visual sin bloquear
    if (!voucherAdjunto) {
        const uz = document.getElementById('uz');
        if (uz) uz.style.border = '2px dashed #f59e0b'; // advertencia amarilla, no roja
    }

    updateProgressSteps();

    if (!valid) {
        e.preventDefault();
        const first = this.querySelector('.err, .has-errors');
        if (first) {
            first.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        // Mostrar mensaje de error visible
        const tLabel = document.getElementById('top-progress-label');
        if (tLabel) {
            tLabel.textContent = '⚠️ Faltan campos obligatorios — revisa el formulario';
            tLabel.style.color = '#ef4444';
            setTimeout(() => { tLabel.style.color = ''; }, 4000);
        }
        return;
    }

    const b = document.getElementById('btn-submit');
    if (b) {
        b.innerHTML = '<span class="spinner" style="width:12px;height:12px;border-width:2px;margin-right:6px"></span> Guardando...';
        b.disabled = true;
    }
});
const FORM_STORAGE_KEY = 'reserva_create_draft';
 
/* ── Campos que NO se deben guardar (seguridad / archivos) ── */
const SKIP_FIELDS = new Set([
    '_token',
    'archivo_baucher',
    'password',
]);
 
/* ── Guardar todo el formulario en sessionStorage ── */
function saveFormDraft() {
    const form = document.getElementById('form-reserva');
    if (!form) return;
 
    const draft = {};
    const elements = form.querySelectorAll('input, select, textarea');
 
    elements.forEach(el => {
        const name = el.name || el.id;
        if (!name || SKIP_FIELDS.has(name)) return;
 
        if (el.type === 'checkbox' || el.type === 'radio') {
            draft[name] = el.checked;
        } else if (el.type === 'file') {
            // no guardamos archivos
        } else {
            draft[name] = el.value;
        }
    });
 
    // Guardar también los campos de email widget (no tienen name en el form)
    const emailUser   = document.getElementById('email-user');
    const emailDomain = document.getElementById('email-domain');
    if (emailUser)   draft['__email_user']   = emailUser.value;
    if (emailDomain) draft['__email_domain'] = emailDomain.value;
 
    // Guardar el código de país de los teléfonos
    ['1','2','3'].forEach(suffix => {
        const codeEl = document.getElementById('phone-code-' + suffix);
        const flagEl = document.getElementById('phone-flag-' + suffix);
        if (codeEl) draft['__phone_code_' + suffix] = codeEl.textContent;
        if (flagEl) draft['__phone_flag_' + suffix] = flagEl.textContent;
    });
 
    // Guardar número de pasajeros adicionales creados (pN)
    draft['__pax_count'] = pN;
 
    try {
        sessionStorage.setItem(FORM_STORAGE_KEY, JSON.stringify(draft));
    } catch (e) {
        // sessionStorage lleno o bloqueado — ignorar silenciosamente
    }
}
 
/* ── Restaurar el formulario desde sessionStorage ── */
function restoreFormDraft() {
    let draft;
    try {
        const raw = sessionStorage.getItem(FORM_STORAGE_KEY);
        if (!raw) return;
        draft = JSON.parse(raw);
    } catch (e) {
        return;
    }
 
    const form = document.getElementById('form-reserva');
    if (!form) return;
 
    // Mostrar banner de "datos recuperados"
    showDraftBanner();
 
    const elements = form.querySelectorAll('input, select, textarea');
    elements.forEach(el => {
        const name = el.name || el.id;
        if (!name || SKIP_FIELDS.has(name) || !(name in draft)) return;
 
        if (el.type === 'checkbox' || el.type === 'radio') {
            el.checked = draft[name];
        } else if (el.type === 'file') {
            // no restauramos archivos
        } else {
            // No sobreescribir si ya tiene valor (ej: old() de Laravel)
            if (!el.value) el.value = draft[name];
        }
    });
 
    // Restaurar email widget
    if (draft['__email_user']) {
        const eu = document.getElementById('email-user');
        if (eu && !eu.value) eu.value = draft['__email_user'];
    }
    if (draft['__email_domain']) {
        const ed = document.getElementById('email-domain');
        if (ed && !ed.value) ed.value = draft['__email_domain'];
    }
    loadEmailOld(); // sincronizar el campo hidden
 
    // Restaurar códigos de teléfono
    ['1','2','3'].forEach(suffix => {
        const codeKey = '__phone_code_' + suffix;
        const flagKey = '__phone_flag_' + suffix;
        if (draft[codeKey]) {
            const codeEl = document.getElementById('phone-code-' + suffix);
            if (codeEl) codeEl.textContent = draft[codeKey];
        }
        if (draft[flagKey]) {
            const flagEl = document.getElementById('phone-flag-' + suffix);
            if (flagEl) flagEl.textContent = draft[flagKey];
        }
    });
 
    // Forzar actualización de funciones dependientes
    setTimeout(() => {
        togFactura();
        calcTotal();
        calcDias();
        toggleTransporte();
        updOpHint();
 
        // Resincronizar flatpickr con valores restaurados
        resyncFlatpickr('fecha_arribo');
        resyncFlatpickr('fecha_retorno');
 
        // Titular → salud
        const tiNombre = document.getElementById('titular_nombre');
        if (tiNombre && tiNombre.value) actualizarNombreTitularSalud(tiNombre.value);
 
        // Toggle solo pasajero
        const soloChk = document.getElementById('solo-pasajero');
        if (soloChk) toggleSoloPasajero(soloChk);
 
        // Salud master
        const saludChk = document.getElementById('salud-master-toggle');
        if (saludChk && saludChk.checked) {
            saludChk.dataset.touched = '1';
            _updateSaludMasterVisual(true);
        }
 
        updateProgressSteps();
    }, 100);
}
 
/* ── Re-sincronizar flatpickr con el valor del hidden ── */
function resyncFlatpickr(fieldId) {
    const hidden = document.getElementById(fieldId);
    const display = document.getElementById(fieldId + '_display');
    if (!hidden || !display || !hidden.value || hidden.value === 'N/D') return;
    const fp = display._flatpickr;
    if (fp) fp.setDate(hidden.value, true, 'Y-m-d');
}
 
/* ── Limpiar draft del sessionStorage ── */
function clearFormDraft() {
    try {
        sessionStorage.removeItem(FORM_STORAGE_KEY);
    } catch (e) {}
}
 
/* ── Banner "borrador recuperado" ── */
function showDraftBanner() {
    // Evitar duplicados
    if (document.getElementById('draft-banner')) return;
 
    const banner = document.createElement('div');
    banner.id = 'draft-banner';
    banner.innerHTML = `
        <i class="bi bi-cloud-check-fill" style="font-size:.95rem;"></i>
        <span>Se recuperaron los datos que estabas completando.</span>
        <button type="button" onclick="dismissDraftBanner()" title="Cerrar"
                style="background:none;border:none;cursor:pointer;color:inherit;
                       padding:0 .2rem;font-size:.9rem;opacity:.7;line-height:1;">
            <i class="bi bi-x-lg"></i>
        </button>
    `;
    banner.style.cssText = `
        display:flex;align-items:center;gap:.6rem;
        padding:.65rem 1rem;border-radius:10px;
        background:#eff6ff;border:1.5px solid #bfdbfe;
        color:#1e40af;font-size:.82rem;font-weight:600;
        margin-bottom:.75rem;animation:draftFadeIn .3s ease;
    `;
 
    // Insertar antes del primer bloque del form
    const form = document.getElementById('form-reserva');
    if (form) form.insertAdjacentElement('beforebegin', banner);
}
 
window.dismissDraftBanner = function() {
    document.getElementById('draft-banner')?.remove();
};
 
/* ── Animación del banner ── */
(function injectDraftStyles() {
    const style = document.createElement('style');
    style.textContent = `
        @keyframes draftFadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        /* Modal de confirmación limpiar */
        #clear-modal-overlay {
            display:none;position:fixed;inset:0;
            background:rgba(0,0,0,.45);z-index:99999;
            align-items:center;justify-content:center;
        }
        #clear-modal-overlay.open { display:flex; }
        #clear-modal-box {
            background:#fff;border-radius:16px;padding:1.5rem;
            width:100%;max-width:380px;margin:1rem;
            box-shadow:0 20px 60px rgba(0,0,0,.2);
            animation:draftFadeIn .2s ease;
        }
        #clear-modal-box .cm-title {
            font-size:1rem;font-weight:700;color:#0d1117;
            display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem;
        }
        #clear-modal-box .cm-body {
            font-size:.83rem;color:#6b7280;line-height:1.55;margin-bottom:1.25rem;
        }
        #clear-modal-box .cm-footer {
            display:flex;gap:.6rem;justify-content:flex-end;
        }
        .cm-btn-cancel {
            padding:8px 18px;border-radius:9px;font-size:.84rem;font-weight:600;
            background:#f3f4f6;color:#6b7280;border:1.5px solid #e5e7eb;
            cursor:pointer;font-family:inherit;transition:all .15s;
        }
        .cm-btn-cancel:hover { background:#e5e7eb; }
        .cm-btn-danger {
            padding:8px 20px;border-radius:9px;font-size:.84rem;font-weight:700;
            background:#dc2626;color:white;border:none;cursor:pointer;
            font-family:inherit;transition:background .15s;
            display:flex;align-items:center;gap:.4rem;
        }
        .cm-btn-danger:hover { background:#b91c1c; }
        /* Botón limpiar en la barra */
        .btn-clear-form {
            display:inline-flex;align-items:center;gap:.4rem;
            padding:9px 16px;border-radius:10px;font-size:.84rem;font-weight:600;
            font-family:inherit;cursor:pointer;transition:all .2s;
            background:transparent;color:#6b7280;
            border:1.5px solid #e5e7eb;
        }
        .btn-clear-form:hover {
            border-color:#dc2626;color:#dc2626;background:#fef2f2;
        }
    `;
    document.head.appendChild(style);
})();
 
/* ══════════════════════════════════════════════════════════════
   MODAL DE CONFIRMACIÓN — Limpiar formulario
══════════════════════════════════════════════════════════════ */
(function injectClearModal() {
    document.addEventListener('DOMContentLoaded', () => {
        const overlay = document.createElement('div');
        overlay.id = 'clear-modal-overlay';
        overlay.innerHTML = `
            <div id="clear-modal-box">
                <div class="cm-title">
                    <i class="bi bi-trash3-fill" style="color:#dc2626;font-size:1.1rem;"></i>
                    ¿Limpiar formulario?
                </div>
                <div class="cm-body">
                    Se borrarán <strong>todos los datos ingresados</strong> en el formulario
                    y no podrás recuperarlos. Esta acción no se puede deshacer.
                </div>
                <div class="cm-footer">
                    <button type="button" class="cm-btn-cancel"
                            onclick="closeClearModal()">Cancelar</button>
                    <button type="button" class="cm-btn-danger"
                            onclick="confirmClearForm()">
                        <i class="bi bi-trash3"></i> Sí, limpiar todo
                    </button>
                </div>
            </div>
        `;
        overlay.addEventListener('click', e => {
            if (e.target === overlay) closeClearModal();
        });
        document.body.appendChild(overlay);
    });
})();
 
window.openClearModal  = function() {
    document.getElementById('clear-modal-overlay')?.classList.add('open');
    document.body.style.overflow = 'hidden';
};
window.closeClearModal = function() {
    document.getElementById('clear-modal-overlay')?.classList.remove('open');
    document.body.style.overflow = '';
};
 
window.confirmClearForm = function() {
    closeClearModal();
    clearFormDraft();
 
    const form = document.getElementById('form-reserva');
    if (!form) return;
 
    // Reset nativo del form
    form.reset();
 
    // Limpiar flatpickr
    ['fecha_arribo_display','fecha_retorno_display',
     'hora_arribo_display','hora_retorno_display',
     'hora_salida_vuelo_display','hora_llegada_vuelo_display'].forEach(id => {
        const el = document.getElementById(id);
        if (el?._flatpickr) el._flatpickr.clear();
    });
 
    // Limpiar widgets personalizados
    const emailUser   = document.getElementById('email-user');
    const emailDomain = document.getElementById('email-domain');
    if (emailUser)   emailUser.value = '';
    if (emailDomain) emailDomain.value = '';
    joinEmail();
 
    // Limpiar pasajeros adicionales
    const paxLista = document.getElementById('pax-lista');
    if (paxLista) paxLista.innerHTML = '';
    pN = 0;
    paxCnt();
 
    // Limpiar preview de voucher
    removeFile();
 
    // Limpiar errores visuales
    form.querySelectorAll('.err, .ok-val').forEach(el => {
        el.classList.remove('err', 'ok-val');
    });
    form.querySelectorAll('.live-err').forEach(el => el.remove());
 
    // Quitar banner de draft si existe
    document.getElementById('draft-banner')?.remove();
 
    // Re-inicializar estados
    togFactura();
    calcTotal();
    calcDias();
    toggleTransporte();
    updOpHint();
    updateProgressSteps();
 
    // Toast confirmación
    showClearToast();
};
 
function showClearToast() {
    const t = document.createElement('div');
    t.style.cssText = `
        position:fixed;top:1.25rem;left:50%;transform:translateX(-50%);
        background:#dc2626;color:white;padding:.65rem 1.4rem;border-radius:12px;
        font-size:.86rem;font-weight:700;z-index:99999;
        box-shadow:0 4px 20px rgba(220,38,38,.35);
        animation:draftFadeIn .25s ease;white-space:nowrap;
        display:flex;align-items:center;gap:.5rem;
    `;
    t.innerHTML = '<i class="bi bi-check-circle-fill"></i> Formulario limpiado correctamente';
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; }, 1800);
    setTimeout(() => t.remove(), 2100);
}
 
/* ══════════════════════════════════════════════════════════════
   GUARDAR AUTOMÁTICAMENTE
   Se engancha a todos los cambios del formulario con debounce.
══════════════════════════════════════════════════════════════ */
(function initAutoSave() {
    let saveTimer = null;
 
    function scheduleSave() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(saveFormDraft, 400);
    }
 
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('form-reserva');
        if (!form) return;
 
        // Escuchar cualquier cambio en el formulario
        form.addEventListener('input',  scheduleSave);
        form.addEventListener('change', scheduleSave);
 
        // Limpiar draft al enviar exitosamente
        form.addEventListener('submit', function(e) {
            // Solo limpiar si el submit no fue cancelado (valid = true)
            // Se verifica si el botón NO fue deshabilitado previamente por error
            setTimeout(() => {
                const btn = document.getElementById('btn-submit');
                if (btn && btn.disabled) {
                    // El form se está enviando → limpiar draft
                    clearFormDraft();
                }
            }, 50);
        });
 
        // Restaurar al cargar (solo si no hay errores de servidor)
        const hasServerErrors = document.querySelector('.lerr') !== null;
        if (!hasServerErrors) {
            restoreFormDraft();
        }
    });
})();
 
/* Tecla Escape cierra el modal de limpiar */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeClearModal();
});
 