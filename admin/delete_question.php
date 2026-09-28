<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/db.php';

$id = (int) ($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $conn->prepare('DELETE FROM questions WHERE id = ?');
    $stmt->execute([$id]);
}
header('Location: dashboard.php?msg=deleted');
exit;
