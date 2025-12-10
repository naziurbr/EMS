<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    */
    'name' => 'Event Management System',
    'env' => 'development',
    'debug' => true,
    'base_url' => 'http://localhost/for%20testing/event-management-system/public',
    'timezone' => 'Asia/Dhaka',
    'locale' => 'en',
    'key' => 'base64:' . base64_encode(random_bytes(32)),
    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    */
    'db' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'event_ems',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => 'InnoDB',
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Configuration
    |--------------------------------------------------------------------------
    */
    'session' => [
        'driver' => 'file',
        'lifetime' => 120,
        'expire_on_close' => false,
        'encrypt' => false,
        'path' => '/',
        'domain' => null,
        'secure' => isset($_SERVER['HTTPS']),
        'http_only' => true,
        'same_site' => 'lax',
    ],

    /*
    |--------------------------------------------------------------------------
    | Mail Configuration
    |--------------------------------------------------------------------------
    */
    'mail' => [
        'driver' => 'smtp',
        'host' => 'smtp.mailtrap.io',
        'port' => 2525,
        'from' => [
            'address' => 'noreply@example.com',
            'name' => 'Event Management System',
        ],
        'encryption' => 'tls',
        'username' => null,
        'password' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | File Uploads
    |--------------------------------------------------------------------------
    */
    'uploads' => [
        'path' => __DIR__ . '/../public/uploads',
        'url' => 'http://localhost/for%20testing/event-management-system/public/uploads',
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
        'max_size' => 5, // MB
    ],
];
