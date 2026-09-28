<?php
// Shared header + navigation. Starts the session once.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$page_title = isset($page_title) ? $page_title : 'Online Quiz System';
$logged_in  = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($page_title); ?></title>
  <link rel="stylesheet" href="<?php echo isset($base) ? $base : ''; ?>css/style.css">
</head>
<body>
  <nav class="navbar">
    <div class="nav-inner">
      <a class="brand" href="<?php echo isset($base) ? $base : ''; ?>index.php">📝 QuizMaster</a>
      <div class="nav-links">
        <a href="<?php echo isset($base) ? $base : ''; ?>index.php">Home</a>
        <?php if ($logged_in): ?>
          <a href="<?php echo isset($base) ? $base : ''; ?>quiz.php">Take Quiz</a>
          <a href="<?php echo isset($base) ? $base : ''; ?>result.php">My Results</a>
          <a href="<?php echo isset($base) ? $base : ''; ?>logout.php">Logout (<?php echo htmlspecialchars($_SESSION['user_name']); ?>)</a>
        <?php else: ?>
          <a href="<?php echo isset($base) ? $base : ''; ?>login.php">Login</a>
          <a href="<?php echo isset($base) ? $base : ''; ?>register.php">Register</a>
          <a href="<?php echo isset($base) ? $base : ''; ?>admin/login.php">Admin</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>
  <main class="container">
