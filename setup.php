<?php
/**
 * One-time setup: creates the default admin account with a
 * correctly-generated password hash.
 *
 *   Username: admin
 *   Password: admin123
 *
 * Visit  http://localhost/quiz-system/setup.php  ONCE after importing
 * database.sql, then you may delete this file.
 */
require_once __DIR__ . '/config/db.php';

$username = 'admin';
$password = 'admin123';
$hash     = password_hash($password, PASSWORD_DEFAULT);

// Insert admin, or update the password if it already exists.
$error = '';
try {
    $stmt = $conn->prepare(
        'INSERT INTO admins (username, password) VALUES (?, ?)
         ON CONFLICT (username) DO UPDATE SET password = EXCLUDED.password'
    );
    $ok = $stmt->execute([$username, $hash]);
} catch (PDOException $e) {
    $ok    = false;
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Setup</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container" style="max-width:520px;margin-top:60px">
    <div class="card">
      <?php if ($ok): ?>
        <h2>✅ Setup complete</h2>
        <p>The admin account is ready.</p>
        <ul>
          <li><strong>Username:</strong> admin</li>
          <li><strong>Password:</strong> admin123</li>
        </ul>
        <p class="muted">For security you can now delete <code>setup.php</code>.</p>
        <a class="btn" href="admin/login.php">Go to Admin Login</a>
        <a class="btn btn-secondary" href="index.php">Home</a>
      <?php else: ?>
        <h2>❌ Setup failed</h2>
        <p><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
