<?php
$page_title = 'Home | Online Quiz System';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <h1>Test Your Knowledge</h1>
  <p>Welcome to <strong>QuizMaster</strong> — register, take a timed multiple-choice quiz,
     and get your result instantly. Built as a Web Technology project using HTML, CSS,
     JavaScript, PHP and MySQL.</p>
  <div class="hero-actions">
    <?php if ($logged_in): ?>
      <a class="btn" href="quiz.php">Start Quiz</a>
      <a class="btn btn-secondary" href="result.php">View My Results</a>
    <?php else: ?>
      <a class="btn" href="register.php">Get Started</a>
      <a class="btn btn-secondary" href="login.php">Login</a>
    <?php endif; ?>
  </div>
</section>

<section class="features">
  <div class="feature">
    <div class="icon">⏱️</div>
    <h3>Timed Quiz</h3>
    <p>A JavaScript countdown auto-submits your answers when time runs out.</p>
  </div>
  <div class="feature">
    <div class="icon">✅</div>
    <h3>Instant Results</h3>
    <p>PHP calculates your score and percentage the moment you submit.</p>
  </div>
  <div class="feature">
    <div class="icon">🗄️</div>
    <h3>Saved History</h3>
    <p>Every attempt is stored in MySQL so you can review past results.</p>
  </div>
  <div class="feature">
    <div class="icon">🛠️</div>
    <h3>Admin Panel</h3>
    <p>Admins can add, edit and delete quiz questions anytime.</p>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
