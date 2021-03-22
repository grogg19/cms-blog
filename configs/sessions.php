<?php
/**
 * Настройка сессий и куки
 */
return [
    'session_lifetime' => 3600, // Срок жизни сессии 1 час
    'cookie_options' => [
        'expires' => time() + 3600 * 24 * 7, // Срок одна неделя
        'path' => '/',
        'domain' => $_SERVER['SERVER_NAME'],
        'secure' => false,     // or false
        'httponly' => true,    // or false
        'samesite' => 'None' // None || Lax  || Strict
    ]
];
