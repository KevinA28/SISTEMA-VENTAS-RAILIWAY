{{-- =====================================================================
     UBICACIÓN: resources/views/reservas/create.blade.php
     DESCRIPCIÓN: Esqueleto principal del formulario de nueva reserva.
     Solo contiene el layout, incluye todos los partials.
     VERSION: 8.0
     ===================================================================== --}}
@extends('layouts.app')
@section('titulo', 'Nueva Reserva — Adventure')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Familjen+Grotesk:ital,wght@0,400;0,600;0,700;1,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="{{ asset('css/reservas-form.css') }}">
<link rel="stylesheet" href="{{ asset('css/reservas-form-fixes.css') }}">
@endpush

@section('contenido')
<div class="page-wrap">

    {{-- HEADER FIJO --}}
    @include('reservas.partials._header')

    {{-- FORM PRINCIPAL --}}
    <div class="form-main">

        {{-- Mensajes de error / éxito --}}
        @if($errors->any())
        <div class="lerr">
            <strong><i class="bi bi-exclamation-triangle me-1"></i> Corrige los errores antes de guardar:</strong>
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif
        @if(session('error'))
        <div class="lerr"><strong><i class="bi bi-exclamation-triangle me-1"></i> Error:</strong> {{ session('error') }}</div>
        @endif
        @if(session('success'))
        <div class="msg-ok"><i class="bi bi-check-circle me-1"></i> {{ session('success') }}</div>
        @endif

         <form method="POST" action="{{ route('reservas.store') }}" enctype="multipart/form-data" id="form-reserva" novalidate>
            @csrf
            @include('reservas.partials._bloque1_viaje')
            @include('reservas.partials._bloque2_titular_pasajeros')
            @include('reservas.partials._bloque4_pago')
            @include('reservas.partials._bloque5_politicas')
        </form>

        @include('reservas.partials._submit_bar')
    </div>{{-- fin form-main --}}
</div>{{-- fin page-wrap --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="{{ asset('js/politicas.js') }}"></script>
<script src="{{ asset('js/reservas-create.js') }}"></script>
@endpush