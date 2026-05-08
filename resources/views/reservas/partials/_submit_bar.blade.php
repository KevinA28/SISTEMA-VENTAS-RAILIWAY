{{-- =====================================================================
     UBICACIÓN: resources/views/reservas/partials/_submit_bar.blade.php
     DESCRIPCIÓN: Barra de acción inferior sticky con total y botón de guardar.
     ===================================================================== --}}
<div class="sbar">
    <div class="sbar-left">
        <div class="sbar-label">Total de la reserva</div>
        <div class="sbar-value" id="sb-total">
            S/ 0.00 <span id="sb-pasajeros"></span>
        </div>
        <div style="display:flex;gap:1rem;margin-top:.25rem;font-size:.72rem;color:var(--ink-4);font-weight:600;align-items:center;flex-wrap:wrap">
         <span>Pagado: <strong id="sp-adel" style="color:var(--adv-green)">S/ 0.00</strong></span>
         <span>Faltante: <strong id="sp-saldo" style="color:var(--adv-amber-d)">S/ 0.00</strong></span>
         <span id="sp-estado-badge" class="sp-status-badge" style="display:inline-flex;align-items:center;gap:.25rem;margin-top:0;vertical-align:middle"></span>
      </div>
    </div>
    <div class="sbar-actions">
        <a href="{{ route('reservas.index') }}" class="btn-secondary">
            <i class="bi bi-x"></i> Cancelar
        </a>
        <button type="submit" form="form-reserva" class="btn-primary" id="btn-submit">
            <i class="bi bi-check-circle"></i> Guardar reserva
          </button>
    </div>
</div>