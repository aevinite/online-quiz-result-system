<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

// A specific attempt was requested (just finished a quiz).
$single = null;
if (isset($_GET['id'])) {
    $rid  = (int) $_GET['id'];
    $stmt = $conn->prepare(
        'SELECT score, total_questions, percentage, taken_at
           FROM results WHERE id = ? AND user_id = ?'
    );
    $stmt->execute([$rid, $user_id]);
    $single = $stmt->fetch();
}

// Full history for this student.
$stmt = $conn->prepare(
    'SELECT score, total_questions, percentage, taken_at
       FROM results WHERE user_id = ? ORDER BY taken_at DESC'
);
$stmt->execute([$user_id]);
$history = $stmt->fetchAll();

$page_title = 'Result | Online Quiz System';
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($single): ?>
  <?php
    $pass = $single['percentage'] >= 40;          // 40% pass mark
    $cls  = $pass ? 'pass' : 'fail';
  ?>
  <div class="card">
    <div class="result-score">
      <div class="score-circle <?php echo $cls; ?>">
        <span class="pct"><?php echo (int) $single['percentage']; ?>%</span>
        <span class="lbl"><?php echo $pass ? 'PASSED' : 'FAILED'; ?></span>
      </div>
      <h2><?php echo $pass ? 'Congratulations! 🎉' : 'Keep practising! 💪'; ?></h2>
      <div class="stats">
        <div class="stat">
          <div class="num"><?php echo $single['score']; ?></div>
          <div class="lbl">Correct</div>
        </div>
        <div class="stat">
          <div class="num"><?php echo $single['total_questions'] - $single['score']; ?></div>
          <div class="lbl">Wrong</div>
        </div>
        <div class="stat">
          <div class="num"><?php echo $single['total_questions']; ?></div>
          <div class="lbl">Total</div>
        </div>
      </div>
      <p style="margin-top:20px">
        <a class="btn" href="quiz.php">Retake Quiz</a>
        <a class="btn btn-secondary" href="index.php">Home</a>
      </p>
    </div>
  </div>
<?php endif; ?>

<div class="card">
  <h2>My Quiz History</h2>
  <?php if (count($history) === 0): ?>
    <div class="alert alert-info">You haven't taken any quiz yet. <a href="quiz.php">Start now!</a></div>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>#</th><th>Score</th><th>Total</th><th>Percentage</th><th>Result</th><th>Date</th></tr>
        </thead>
        <tbody>
          <?php $n = 1; foreach ($history as $r): ?>
            <tr>
              <td><?php echo $n++; ?></td>
              <td><?php echo $r['score']; ?></td>
              <td><?php echo $r['total_questions']; ?></td>
              <td><?php echo $r['percentage']; ?>%</td>
              <td>
                <?php if ($r['percentage'] >= 40): ?>
                  <span style="color:var(--success);font-weight:700">Pass</span>
                <?php else: ?>
                  <span style="color:var(--danger);font-weight:700">Fail</span>
                <?php endif; ?>
              </td>
              <td><?php echo date('d M Y, h:i A', strtotime($r['taken_at'])); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
