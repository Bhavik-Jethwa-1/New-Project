<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie','*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5173'], // Add your network URL too
    'allowed_headers' => ['Content-Type', 'X-Requested-With', 'Authorization', 'Accept', 'Origin'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
