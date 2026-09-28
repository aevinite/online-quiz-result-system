<?php
session_start();
// Clear only student session keys, then destroy.
$_SESSION = [];
session_destroy();
header('Location: index.php');
exit;
