<?php
/**
 * Stores PHP sessions in MySQL (table `sessions`).
 * Needed on Vercel, where each request may run on a different server
 * and file-based sessions would be lost between pages.
 */
require_once __DIR__ . '/db.php';

class DbSessionHandler implements SessionHandlerInterface
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function open($path, $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read($id): string
    {
        $stmt = mysqli_prepare($this->conn, 'SELECT data FROM sessions WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 's', $id);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        return $row ? $row['data'] : '';
    }

    public function write($id, $data): bool
    {
        $now  = time();
        $stmt = mysqli_prepare(
            $this->conn,
            'INSERT INTO sessions (id, data, updated_at) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE data = VALUES(data), updated_at = VALUES(updated_at)'
        );
        mysqli_stmt_bind_param($stmt, 'ssi', $id, $data, $now);
        return mysqli_stmt_execute($stmt);
    }

    public function destroy($id): bool
    {
        $stmt = mysqli_prepare($this->conn, 'DELETE FROM sessions WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 's', $id);
        return mysqli_stmt_execute($stmt);
    }

    #[\ReturnTypeWillChange]
    public function gc($max_lifetime)
    {
        $cutoff = time() - $max_lifetime;
        $stmt   = mysqli_prepare($this->conn, 'DELETE FROM sessions WHERE updated_at < ?');
        mysqli_stmt_bind_param($stmt, 'i', $cutoff);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_affected_rows($stmt);
    }
}

session_set_save_handler(new DbSessionHandler($conn), true);
