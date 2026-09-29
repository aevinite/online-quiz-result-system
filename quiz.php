<?php
session_start();
require_once __DIR__ . '/config/db.php';

// Only logged-in students can take the quiz.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Load all questions (correct answers are NOT sent to the browser).
$questions = $conn->query('SELECT id, question, option_a, option_b, option_c, option_d FROM questions ORDER BY id')->fetchAll();
$total = count($questions);

$page_title = 'Quiz | Online Quiz System';
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($total === 0): ?>
  <div class="card">
    <h2>No questions available</h2>
    <div class="alert alert-info">The admin has not added any questions yet. Please check back later.</div>
  </div>
<?php else: ?>
  <div class="card">
    <div class="quiz-header">
      <h2>Quiz Time!</h2>
      <div class="timer" id="timer">10:00</div>
    </div>

    <div class="progress-bar"><span id="progress"></span></div>

    <form id="quizForm" method="post" action="submit_quiz.php">
      <?php foreach ($questions as $i => $q): ?>
        <div class="question-block <?php echo $i === 0 ? 'active' : ''; ?>" data-index="<?php echo $i; ?>">
          <div class="q-count">Question <?php echo $i + 1; ?> of <?php echo $total; ?></div>
          <div class="question-text"><?php echo htmlspecialchars($q['question']); ?></div>
          <div class="options">
            <?php foreach (['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d'] as $letter => $col): ?>
              <label class="option">
                <input type="radio" name="answer[<?php echo $q['id']; ?>]" value="<?php echo $letter; ?>">
                <span><?php echo htmlspecialchars($q[$col]); ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="quiz-nav">
        <button type="button" class="btn btn-secondary" id="prevBtn">&larr; Previous</button>
        <button type="button" class="btn" id="nextBtn">Next &rarr;</button>
        <button type="submit" class="btn btn-success" id="submitBtn" style="display:none">Submit Quiz</button>
      </div>
    </form>
  </div>

  <script>
    // Total time for the quiz in seconds (10 minutes).
    window.QUIZ_TIME = 600;
  </script>
  <script src="js/quiz.js"></script>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
