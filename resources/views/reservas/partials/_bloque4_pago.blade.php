{{-- =====================================================================
     UBICACIÓN: resources/views/reservas/partials/_bloque4_pago.blade.php
     DESCRIPCIÓN: Bloque 4 — Pago y comprobante.
     Campos: comprobante fiscal (boleta/factura + RUC), tipo de pago
     (50% / completo / personalizado), método, cálculos automáticos,
     voucher adjunto, responsable del grupo.
     ===================================================================== --}}
<div class="fb" id="bloque-4">
    <div class="fb-num-badge" id="fb-status-4">4</div>
    <div class="fb-head">
        <div class="fb-ico amber"><i class="bi bi-credit-card"></i></div>
        <div class="fb-titles">
            <h3>Pago y comprobante</h3>
            <p>Tipo de pago, monto y comprobante adjunto</p>
        </div>
    </div>
    <div class="fb-body">

        {{-- ── COMPROBANTE FISCAL ── --}}
        <div class="st">Comprobante fiscal</div>
        <div class="g12">
            <div class="field">
                <label class="lbl" for="tipo_comprobante">Tipo <span class="req">*</span></label>
                <select name="tipo_comprobante" id="tipo_comprobante"
                        class="fi" required
                        onchange="togFactura()"
                        data-validate="required" data-bloque="4">
                    <option value="boleta"  {{ old('tipo_comprobante','boleta') == 'boleta'  ? 'selected' : '' }}>Boleta</option>
                    <option value="factura" {{ old('tipo_comprobante')          == 'factura' ? 'selected' : '' }}>Factura</option>
                </select>
            </div>

            {{-- Campos factura — se muestran solo si tipo = factura --}}
            <div id="campos-factura" style="display:none">
                <div class="g2">
                    <div class="field">
                        <label class="lbl" for="ruc_factura">RUC <span class="req">*</span></label>
                        <div class="dni-wrap">
                            <div class="dni-row">
                                <span class="ia">RUC</span>
                                <input type="text"
                                       name="ruc_factura"
                                       id="ruc_factura"
                                       value="{{ old('ruc_factura') }}"
                                       class="fi" placeholder="20XXXXXXXXX"
                                       maxlength="11" inputmode="numeric"
                                       oninput="onRucInput(this)">
                                <button type="button" class="btn-dni-lookup"
                                        id="btn-ruc-lookup" onclick="buscarRUC()">
                                    <i class="bi bi-search"></i> SUNAT
                                </button>
                            </div>
                            <div class="dni-result" id="ruc-result"></div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="lbl" for="razon_social">Razón social <span class="req">*</span></label>
                        <input type="text"
                               name="razon_social"
                               id="razon_social"
                               value="{{ old('razon_social') }}"
                               class="fi" placeholder="EMPRESA S.A.C." maxlength="200"
                               oninput="this.value=this.value.toUpperCase()">
                        <div class="fhint">Se autocompleta al buscar el RUC</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TIPO DE PAGO ── --}}
        <div class="st">Tipo de pago</div>

        <div class="field">
            <label class="lbl">Selecciona cómo paga <span class="req">*</span></label>
            <div class="estado-inline">
                <label class="eo-compact e-mitad {{ old('estado_inicial','mitad_pago') == 'mitad_pago' ? 'sel' : '' }}"
                       onclick="selEst(this)">
                    <input type="radio" name="estado_inicial" value="mitad_pago"
                           {{ old('estado_inicial','mitad_pago') == 'mitad_pago' ? 'checked' : '' }}>
                    <span class="eo-dot"></span>
                    <i class="bi bi-hourglass-split"></i> 50% Pagado
                </label>
                <label class="eo-compact e-pagado {{ old('estado_inicial') == 'pagado' ? 'sel' : '' }}"
                       onclick="selEst(this)">
                    <input type="radio" name="estado_inicial" value="pagado"
                           {{ old('estado_inicial') == 'pagado' ? 'checked' : '' }}>
                    <span class="eo-dot"></span>
                    <i class="bi bi-patch-check"></i> Pagado completo
                </label>
                <label class="eo-compact e-custom {{ old('estado_inicial') == 'personalizado' ? 'sel' : '' }}"
                       onclick="selEst(this)">
                    <input type="radio" name="estado_inicial" value="personalizado"
                           {{ old('estado_inicial') == 'personalizado' ? 'checked' : '' }}>
                    <span class="eo-dot"></span>
                    <i class="bi bi-pencil-square"></i> Personalizado
                </label>
            </div>
        </div>

        {{-- ── REGISTRO DEL PAGO ── --}}
        <div class="g3">
            <div class="field">
                <label class="lbl" for="metodo_pago">Método de pago <span class="req">*</span></label>
                <select name="metodo_pago" id="metodo_pago"
                        class="fi"
                        onchange="updOpHint();updateProgressSteps()"
                        data-validate="required" data-bloque="4">
                    <option value="">Seleccionar...</option>
                    <optgroup label="Efectivo">
                        <option value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                    </optgroup>
                    <optgroup label="Pagos digitales">
                        <option value="yape"  {{ old('metodo_pago') == 'yape'  ? 'selected' : '' }}>Yape</option>
                        <option value="plin"  {{ old('metodo_pago') == 'plin'  ? 'selected' : '' }}>Plin</option>
                        <option value="tunki" {{ old('metodo_pago') == 'tunki' ? 'selected' : '' }}>Tunki</option>
                    </optgroup>
                    <optgroup label="Transferencia bancaria">
                        <option value="transf_bcp"   {{ old('metodo_pago') == 'transf_bcp'   ? 'selected' : '' }}>Transf. BCP</option>
                        <option value="transf_bbva"  {{ old('metodo_pago') == 'transf_bbva'  ? 'selected' : '' }}>Transf. BBVA</option>
                        <option value="transf_inter" {{ old('metodo_pago') == 'transf_inter' ? 'selected' : '' }}>Transf. Interbank</option>
                        <option value="transf_sc"    {{ old('metodo_pago') == 'transf_sc'    ? 'selected' : '' }}>Transf. Scotiabank</option>
                        <option value="transf_bn"    {{ old('metodo_pago') == 'transf_bn'    ? 'selected' : '' }}>Transf. Banco Nación</option>
                        <option value="transf_otros" {{ old('metodo_pago') == 'transf_otros' ? 'selected' : '' }}>Otro banco</option>
                    </optgroup>
                    <optgroup label="Depósito bancario">
                        <option value="dep_bcp"   {{ old('metodo_pago') == 'dep_bcp'   ? 'selected' : '' }}>Depósito BCP</option>
                        <option value="dep_bbva"  {{ old('metodo_pago') == 'dep_bbva'  ? 'selected' : '' }}>Depósito BBVA</option>
                        <option value="dep_inter" {{ old('metodo_pago') == 'dep_inter' ? 'selected' : '' }}>Depósito Interbank</option>
                        <option value="dep_otros" {{ old('metodo_pago') == 'dep_otros' ? 'selected' : '' }}>Depósito otro banco</option>
                    </optgroup>
                    <optgroup label="Tarjeta">
                        <option value="tarjeta_credito" {{ old('metodo_pago') == 'tarjeta_credito' ? 'selected' : '' }}>Tarjeta crédito</option>
                        <option value="tarjeta_debito"  {{ old('metodo_pago') == 'tarjeta_debito'  ? 'selected' : '' }}>Tarjeta débito</option>
                    </optgroup>
                </select>
                @error('metodo_pago')
                    <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label class="lbl" for="monto_pagado_inicial">
                    Monto pagado (S/) <span class="req">*</span>
                </label>
                <div class="ig {{ $errors->has('monto_pagado_inicial') ? 'err-group' : '' }}">
                    <span class="ia">S/</span>
                    <input type="number"
                           name="monto_pagado_inicial"
                           id="monto_pagado_inicial"
                           value="{{ old('monto_pagado_inicial') }}"
                           class="fi {{ $errors->has('monto_pagado_inicial') ? 'err' : '' }}"
                           step="0.01" min="0.01" placeholder="—"
                           oninput="calcTotalFromCustom();calcTotal()"
                           data-validate="required|numeric|positive"
                           data-bloque="4">
                </div>
                <div class="fhint" style="margin-top:.28rem">
              <span style="background-color:#ffeb3b;padding:4px 8px;border-radius:4px;display:inline-block;color:#000;">
              Precio total: <strong id="ref-precio-total">S/ 0.00</strong>
              </span>
              &nbsp;·&nbsp; <span id="calc-monto-txt" style="font-weight:700;color:var(--adv-blue)"></span>
           </div>
                @error('monto_pagado_inicial')
                    <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label class="lbl">Fecha de pago</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="fecha_pago"
                           value="{{ old('fecha_pago', date('Y-m-d')) }}" class="fi">
                </div>
            </div>
        </div>
        <div class="field" style="max-width:360px">
            <label class="lbl">N&deg; operación / referencia <span class="opt">(opcional)</span></label>
            <input type="text" name="numero_operacion"
                   value="{{ old('numero_operacion') }}"
                   class="fi" placeholder="Código de transacción..." maxlength="100">
            <div class="fhint" id="op-hint">Código visible en Yape, Plin o constancia bancaria</div>
        </div>

        {{-- ── COMPROBANTE ADJUNTO ── --}}
        <div class="st">Comprobante adjunto <span class="req">*</span></div>

        <div class="upload-zone" id="uz"
             ondragover="event.preventDefault();this.classList.add('over')"
             ondragleave="this.classList.remove('over')"
             ondrop="onDrop(event)">
            <input type="file" name="archivo_baucher" id="archivo_baucher"
                   accept=".jpg,.jpeg,.png,.pdf,.webp" onchange="onFile(event)">
            <div class="uz-icon"><i class="bi bi-cloud-arrow-up"></i></div>
            <div class="uz-text">
                Arrastra aquí o <strong style="color:var(--adv-blue)">haz clic para seleccionar</strong>
            </div>
            <div class="uz-sub">JPG &middot; PNG &middot; PDF &middot; WEBP &mdash; máx. 5 MB</div>
        </div>

        <div class="file-preview" id="fprev">
            <img id="prev-img" src="" alt="">
            <div class="file-preview-bar">
                <span class="fn" id="prev-name">—</span>
                <button type="button" class="fr" onclick="removeFile()">
                    <i class="bi bi-x-circle me-1"></i> Quitar
                </button>
            </div>
        </div>
    </div>
</div>