<?php

return [
    'driver' => 'array',
    'lifetime' => 120,
    'expire_on_close' => true,
    'encrypt' => false,
    'files' => storage_path('framework/sessions'),
    'lottery' => [0, 100],
    'cookie' => 'aestetic_workbench_session',
    'path' => '/',
    'domain' => null,
    'secure' => null,
    'http_only' => true,
    'same_site' => 'lax',
    'partitioned' => false,
];
