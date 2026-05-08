{{-- =====================================================================
     UBICACIÓN: resources/views/reservas/partials/_header.blade.php
     DESCRIPCIÓN: Header fijo de la página de nueva reserva.
     ===================================================================== --}}
<div class="page-header">
    <div class="page-header-inner">
        <div class="ph-brand">
            <div class="ph-logo"><i class="bi bi-compass"></i></div>
            <div class="ph-text">
                <div class="eyebrow"><i class="bi bi-airplane-fill"></i> Adventure &middot; Agencia de Viajes</div>
                <h1>Nueva Reserva</h1>
            </div>
        </div>
        <a href="{{ route('reservas.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Volver a Reservas
        </a>
    </div>
</div>