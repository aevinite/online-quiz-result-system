<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/db.php';

// Counts for the summary cards.
$q_count = $conn->query('SELECT COUNT(*) FROM questions')->fetchColumn();
$u_count = $conn->query('SELECT COUNT(*) FROM users')->fetchColumn();
$r_count = $conn->query('SELECT COUNT(*) FROM results')->fetchColumn();

$questions = $conn->query('SELECT * FROM questions ORDER BY id DESC')->fetchAll();

$flash = $_GET['msg'] ?? '';

$admin_title = 'Dashboard';
require_once __DIR__ . '/admin_header.php';
?>

<div class="features" style="margin-bottom:24px">
  <div class="feature"><div class="icon">❓</div><h3><?php echo $q_count; ?></h3><p>Questions</p></div>
  <div class="feature"><div class="icon">👨‍🎓</div><h3><?php echo $u_count; ?></h3><p>Registered Students</p></div>
  <div class="feature"><div class="icon">📊</div><h3><?php echo $r_count; ?></h3><p>Quiz Attempts</p></div>
</div>

<div class="card">
  <div class="quiz-header">
    <h2>Manage Questions</h2>
    <a class="btn" href="add_question.php">+ Add Question</a>
  </div>

  <?php if ($flash === 'added'): ?>
    <div class="alert alert-success">Question added successfully.</div>
  <?php elseif ($flash === 'updated'): ?>
    <div class="alert alert-success">Question updated successfully.</div>
  <?php elseif ($flash === 'deleted'): ?>
    <div class="alert alert-success">Question deleted successfully.</div>
  <?php endif; ?>

  <?php if (count($questions) === 0): ?>
    <div class="alert alert-info">No questions yet. Click <strong>Add Question</strong> to create one.</div>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>#</th><th>Question</th><th>Correct</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($questions as $q): ?>
            <tr>
              <td><?php echo $q['id']; ?></td>
              <td><?php echo htmlspecialchars($q['question']); ?></td>
              <td><strong><?php echo htmlspecialchars($q['correct_option']); ?></strong></td>
              <td style="white-space:nowrap">
                <a class="btn btn-sm" href="edit_question.php?id=<?php echo $q['id']; ?>">Edit</a>
                <a class="btn btn-sm btn-danger"
                   href="delete_question.php?id=<?php echo $q['id']; ?>"
                   onclick="return confirm('Delete this question?')">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

</main></body></html>
