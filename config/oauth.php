<?php

return [
    'egov' => [
        'enabled'=> env('AUTH_ENABLED', false),
        'scope' => env('AUTH_CLIENT_SCOPE'),
        'auth_url' => env('AUTH_URL'),
        'client_id' => env('AUTH_CLIENT_ID'),
        'client_secret' => env('AUTH_CLIENT_SECRET'),
    ]
];
