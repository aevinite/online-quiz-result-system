<?php
/**
 * Database connection (MySQLi).
 * Local (XAMPP/WAMP/MySQL) values are used by default.
 * On Vercel, set DB_HOST, DB_PORT, DB_USER, DB_PASS, DB_NAME and DB_SSL
 * as environment variables to point at a cloud MySQL database.
 */

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_PORT = (int) (getenv('DB_PORT') ?: 3306);
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'root';
$DB_NAME = getenv('DB_NAME') ?: 'quiz_system';
$DB_SSL  = getenv('DB_SSL') === 'true';   // cloud databases usually require SSL

$conn  = mysqli_init();
$flags = 0;
if ($DB_SSL) {
    mysqli_ssl_set($conn, null, null, null, null, null);
    $flags = MYSQLI_CLIENT_SSL | MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT;
}

try {
    mysqli_real_connect($conn, $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT, null, $flags);
} catch (mysqli_sql_exception $e) {
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}

mysqli_set_charset($conn, 'utf8mb4');
?>
