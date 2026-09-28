<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/db.php';

$errors = [];
$q = ['question' => '', 'option_a' => '', 'option_b' => '', 'option_c' => '', 'option_d' => '', 'correct_option' => 'A'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $q['question']       = trim($_POST['question'] ?? '');
    $q['option_a']       = trim($_POST['option_a'] ?? '');
    $q['option_b']       = trim($_POST['option_b'] ?? '');
    $q['option_c']       = trim($_POST['option_c'] ?? '');
    $q['option_d']       = trim($_POST['option_d'] ?? '');
    $q['correct_option'] = strtoupper(trim($_POST['correct_option'] ?? ''));

    if ($q['question'] === '')                     $errors[] = 'Question text is required.';
    foreach (['option_a','option_b','option_c','option_d'] as $o)
        if ($q[$o] === '')                          $errors[] = 'All four options are required.';
    if (!in_array($q['correct_option'], ['A','B','C','D'], true))
        $errors[] = 'Correct option must be A, B, C or D.';

    if (!$errors) {
        $stmt = $conn->prepare(
            'INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct_option)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        if ($stmt->execute([
            $q['question'], $q['option_a'], $q['option_b'], $q['option_c'], $q['option_d'], $q['correct_option'],
        ])) {
            header('Location: dashboard.php?msg=added');
            exit;
        }
        $errors[] = 'Could not save question.';
    }
    $errors = array_unique($errors);
}

$admin_title = 'Add Question';
require_once __DIR__ . '/admin_header.php';
?>

<div class="card" style="max-width:640px;margin:0 auto">
  <h2>Add New Question</h2>
  <?php foreach ($errors as $e): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($e); ?></div>
  <?php endforeach; ?>

  <form method="post" action="add_question.php">
    <label>Question</label>
    <textarea name="question" required><?php echo htmlspecialchars($q['question']); ?></textarea>

    <label>Option A</label>
    <input type="text" name="option_a" value="<?php echo htmlspecialchars($q['option_a']); ?>" required>
    <label>Option B</label>
    <input type="text" name="option_b" value="<?php echo htmlspecialchars($q['option_b']); ?>" required>
    <label>Option C</label>
    <input type="text" name="option_c" value="<?php echo htmlspecialchars($q['option_c']); ?>" required>
    <label>Option D</label>
    <input type="text" name="option_d" value="<?php echo htmlspecialchars($q['option_d']); ?>" required>

    <label>Correct Option</label>
    <select name="correct_option" required>
      <?php foreach (['A','B','C','D'] as $opt): ?>
        <option value="<?php echo $opt; ?>" <?php echo $q['correct_option'] === $opt ? 'selected' : ''; ?>><?php echo $opt; ?></option>
      <?php endforeach; ?>
    </select>

    <button type="submit" class="btn btn-block btn-success">Save Question</button>
    <a class="btn btn-secondary btn-block" href="dashboard.php">Cancel</a>
  </form>
</div>

</main></body></html>
