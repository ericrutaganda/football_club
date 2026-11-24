<?php
declare(strict_types=1);

define('APP_NAME', 'Football Club Manager');
define('BASE_URL', ''); // Example: '/football_club' if installed in a subfolder.
define('DB_HOST', 'localhost');
define('DB_NAME', 'football_club');
define('DB_USER', 'root');
define('DB_PASS', '');
define('APP_DEBUG', false);

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

date_default_timezone_set('Africa/Kigali');
