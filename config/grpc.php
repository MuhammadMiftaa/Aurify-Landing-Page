<?php

return [
    /*
    |--------------------------------------------------------------------------
    | gRPC Service Addresses
    |--------------------------------------------------------------------------
    | Addresses read from .env: GRPC_WALLET_ADDRESS, etc.
    | Format: host:port (no scheme — grpcurl uses -plaintext flag)
    */
    'wallet_address'      => env('GRPC_WALLET_ADDRESS', 'localhost:10001'),
    'transaction_address' => env('GRPC_TRANSACTION_ADDRESS', 'localhost:10002'),
    'investment_address'  => env('GRPC_INVESTMENT_ADDRESS', 'localhost:10003'),

    /*
    |--------------------------------------------------------------------------
    | grpcurl Binary Path
    |--------------------------------------------------------------------------
    | Absolute path to the grpcurl binary used for PHP → gRPC calls.
    | Install: go install github.com/fullstorydev/grpcurl/cmd/grpcurl@latest
    */
    'grpcurl_path' => env('GRPCURL_PATH', '/usr/local/bin/grpcurl'),
];
