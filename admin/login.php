<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT id, password FROM admins WHERE username = ?');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id']       = $admin['id'];
        $_SESSION['admin_username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid admin credentials.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | Quiz System</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <nav class="navbar">
    <div class="nav-inner">
      <a class="brand" href="../index.php">📝 QuizMaster</a>
      <div class="nav-links"><a href="../index.php">Back to Site</a></div>
    </div>
  </nav>

  <main class="container">
    <div class="card form-narrow">
      <h2>Admin Login</h2>
      <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form method="post" action="login.php">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" class="btn btn-block">Login</button>
      </form>
      <p class="muted" style="margin-top:14px">Default &rarr; user: <strong>admin</strong>, pass: <strong>admin123</strong> (run setup.php first).</p>
    </div>
  </main>
</body>
</html>
