<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/db.php';

$id = (int) ($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'DELETE FROM questions WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
header('Location: dashboard.php?msg=deleted');
exit;
