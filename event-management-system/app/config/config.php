<?php

return [
    // Application
    'name' => 'Event Management System',
    'env' => 'development',
    'debug' => true,
    'url' => 'http://localhost/for%20testing/event-management-system/public',
    'timezone' => 'UTC',
    'locale' => 'en',
    'key' => 'base64:YOUR_APPLICATION_KEY',
    'cipher' => 'AES-256-CBC',
    
    // Database
    'database' => [
        'default' => 'mysql',
        'connections' => [
            'mysql' => [
                'driver' => 'mysql',
                'host' => 'localhost',
                'port' => '3306',
                'database' => 'event_ems',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => 'InnoDB',
            ],
        ],
    ],
    
    // Session
    'session' => [
        'driver' => 'file',
        'lifetime' => 120,
        'expire_on_close' => false,
        'encrypt' => false,
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'http_only' => true,
        'same_site' => 'lax',
    ],
    
    // Mail
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
];
