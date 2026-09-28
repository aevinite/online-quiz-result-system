<?php
/**
 * Stores PHP sessions in the database (table `sessions`).
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
        $stmt = $this->conn->prepare('SELECT data FROM sessions WHERE id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetchColumn();
        return $data !== false ? $data : '';
    }

    public function write($id, $data): bool
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO sessions (id, data, updated_at) VALUES (?, ?, ?)
             ON CONFLICT (id) DO UPDATE SET data = EXCLUDED.data, updated_at = EXCLUDED.updated_at'
        );
        return $stmt->execute([$id, $data, time()]);
    }

    public function destroy($id): bool
    {
        $stmt = $this->conn->prepare('DELETE FROM sessions WHERE id = ?');
        return $stmt->execute([$id]);
    }

    #[\ReturnTypeWillChange]
    public function gc($max_lifetime)
    {
        $stmt = $this->conn->prepare('DELETE FROM sessions WHERE updated_at < ?');
        $stmt->execute([time() - $max_lifetime]);
        return $stmt->rowCount();
    }
}

session_set_save_handler(new DbSessionHandler($conn), true);
