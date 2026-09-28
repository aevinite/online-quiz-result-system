<?php
/**
 * Database connection (PDO + PostgreSQL on Supabase).
 * Set DB_HOST, DB_PORT, DB_USER, DB_PASS and DB_NAME as environment
 * variables (Vercel), or put them in config/db.local.php for local runs.
 * Use the Supabase *Session pooler* details (Project -> Connect).
 */

if (is_file(__DIR__ . '/db.local.php')) {
    require_once __DIR__ . '/db.local.php';   // defines $DB_LOCAL = [...]
}
$local = isset($DB_LOCAL) ? $DB_LOCAL : [];

$DB_HOST = getenv('DB_HOST') ?: ($local['host'] ?? 'localhost');
$DB_PORT = (int) (getenv('DB_PORT') ?: ($local['port'] ?? 5432));
$DB_USER = getenv('DB_USER') ?: ($local['user'] ?? 'postgres');
$DB_PASS = getenv('DB_PASS') !== false ? getenv('DB_PASS') : ($local['pass'] ?? '');
$DB_NAME = getenv('DB_NAME') ?: ($local['name'] ?? 'postgres');

try {
    $conn = new PDO(
        "pgsql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;sslmode=require",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => true,   // safe with Supabase's pooler
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}
?>
