<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // ── apis.net.pe — RENIEC (DNI) + SUNAT (RUC) ─────────────────────
    // Token gratis en: https://apis.net.pe  (100 consultas/día gratis)
    // Agregar al .env: RENIEC_API_TOKEN=tu_token_aqui
    'reniec' => [
        'token' => env('RENIEC_API_TOKEN', ''),
    ],

    // ── apiperu.dev — token ya obtenido ──────────────────────────────
    'apiperu' => [
        'token'    => env('APIPERU_TOKEN', 'e71f6c9e49b4350a1d29099dcfbb59b63de9fd35f160139620f4c0027d4e283b'),
        'base_url' => env('APIPERU_BASE_URL', 'https://apiperu.dev/api'),
        'timeout'  => 8,
    ],

    'whatsapp' => [
        'token'    => env('WHATSAPP_TOKEN', ''),
        'phone_id' => env('WHATSAPP_PHONE_ID', ''),
    ],
    'fonnte' => [
    'token' => env('FONNTE_TOKEN'),
    ],

];