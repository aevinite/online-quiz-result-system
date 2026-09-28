<?php
session_start();
require_once __DIR__ . '/config/db.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Please enter both email and password.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT id, name, password FROM users WHERE email = ?');
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $name, $hash);

        if (mysqli_stmt_fetch($stmt) && password_verify($password, $hash)) {
            mysqli_stmt_close($stmt);
            $_SESSION['user_id']   = $id;
            $_SESSION['user_name'] = $name;
            header('Location: quiz.php');
            exit;
        } else {
            $errors[] = 'Invalid email or password.';
        }
        mysqli_stmt_close($stmt);
    }
}

$page_title = 'Login | Online Quiz System';
require_once __DIR__ . '/includes/header.php';
?>

<div class="card form-narrow">
  <h2>Student Login</h2>

  <?php foreach ($errors as $e): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($e); ?></div>
  <?php endforeach; ?>

  <form method="post" action="login.php">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>

    <button type="submit" class="btn btn-block">Login</button>
  </form>
  <p class="muted" style="margin-top:14px">New student? <a href="register.php">Register here</a></p>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
