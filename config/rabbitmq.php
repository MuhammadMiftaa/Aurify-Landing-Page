<?php

return [
    'host'         => env('RABBITMQ_HOST', '127.0.0.1'),
    'port'         => env('RABBITMQ_PORT', 5672),
    'user'         => env('RABBITMQ_USER', 'guest'),
    'password'     => env('RABBITMQ_PASSWORD', 'guest'),
    'virtual_host' => env('RABBITMQ_VIRTUAL_HOST', '/'),

    'exchange' => [
        'name' => 'refina_admin',
        'type' => 'topic',
    ],

    'routing_keys' => [
        'asset_codes'             => 'master.asset_codes',
        'wallet_types'            => 'master.wallet_types',
        'transaction_categories'  => 'master.transaction_categories',
    ],
];
