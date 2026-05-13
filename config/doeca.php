<?php

return [
    'municipio' => env('DOECA_MUNICIPIO', 'Prefeitura Municipal'),
    'estado' => env('DOECA_ESTADO', 'PR'),
    'rodape' => env('DOECA_RODAPE', 'Feito com ❤ para o serviço público'),

    'turnstile' => [
        'site_key' => env('TURNSTILE_SITE_KEY', ''),
        'secret_key' => env('TURNSTILE_SECRET_KEY', ''),
    ],

    'rate_limit' => [
        'max_tentativas' => 5,
        'bloqueio_minutos' => 15,
    ],

    'upload' => [
        'disco' => 'uploads',
        'max_size' => 51200,
        'tipos' => ['application/pdf'],
    ],

    'importacao' => [
        'pasta' => storage_path('app/importacao'),
        'regex' => '/^(\d{4}-\d{2}-\d{2})__(.+)\.pdf$/i',
    ],
];
