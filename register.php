<?php
session_start();
require_once __DIR__ . '/config/db.php';

$errors = [];
$name = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    // ---- Server-side validation (PHP) ----
    if ($name === '')                         $errors[] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
    if (strlen($password) < 6)                $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm)               $errors[] = 'Passwords do not match.';

    // Check duplicate email
    if (!$errors) {
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'That email is already registered. Please login.';
        }
    }

    // ---- Insert user ----
    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?) RETURNING id');
        if ($stmt->execute([$name, $email, $hash])) {
            $_SESSION['user_id']   = $stmt->fetchColumn();
            $_SESSION['user_name'] = $name;
            header('Location: quiz.php');
            exit;
        } else {
            $errors[] = 'Registration failed. Please try again.';
        }
    }
}

$page_title = 'Register | Online Quiz System';
require_once __DIR__ . '/includes/header.php';
?>

<div class="card form-narrow">
  <h2>Student Registration</h2>

  <?php foreach ($errors as $e): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($e); ?></div>
  <?php endforeach; ?>

  <form method="post" action="register.php" onsubmit="return validateRegister()">
    <label for="name">Full Name</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>

    <label for="confirm">Confirm Password</label>
    <input type="password" id="confirm" name="confirm" required>

    <button type="submit" class="btn btn-block">Register</button>
  </form>
  <p class="muted" style="margin-top:14px">Already have an account? <a href="login.php">Login here</a></p>
</div>

<script src="js/main.js"></script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
