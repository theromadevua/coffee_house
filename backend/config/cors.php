<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods'   => ['*'],
    'allowed_origins'   => [
        'http://coffee.test',
        'http://localhost:3002',
        'http://localhost:3003',
        'http://127.0.1:3002',
        'http://localhost:3003',
        'http://127.0.1:80',
        'http://coffee.test:80',
        'http://192.168.88.26:3002'
    ],
    'allowed_origins_patterns' => [
        '/^https?:\/\/([a-z0-9-]+\.)?annaponsprojects\.com$/',
    ],
    'allowed_headers'   => ['*'],
    'exposed_headers'   => [],
    'max_age'           => 3600,
    'supports_credentials' => true,
    'allow_credentials' => true,
];