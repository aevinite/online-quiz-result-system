<?php
// Guard for admin-only pages. Include at the top of each admin page.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
