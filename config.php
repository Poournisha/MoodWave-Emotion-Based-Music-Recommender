<?php
// Database configuration
define('DB_PATH', __DIR__ . '/moodwave.db');
define('DB_TYPE', 'sqlite');

// Application settings
define('APP_NAME', 'MoodWave');
define('APP_URL', 'http://localhost');
define('SESSION_TIMEOUT', 3600); // 1 hour

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Check session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
    session_unset();
    session_destroy();
    header('Location: index.html');
    exit;
}
$_SESSION['last_activity'] = time();
?>