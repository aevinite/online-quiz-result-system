<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: quiz.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$answers = $_POST['answer'] ?? [];   // [question_id => 'A'/'B'/'C'/'D']

// Load correct answers from DB (never trust the browser).
$result = mysqli_query($conn, 'SELECT id, correct_option FROM questions');
$total  = 0;
$score  = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $total++;
    $qid = $row['id'];
    if (isset($answers[$qid]) && strtoupper($answers[$qid]) === strtoupper($row['correct_option'])) {
        $score++;
    }
}

$percentage = $total > 0 ? round(($score / $total) * 100, 2) : 0;

// Store the attempt.
$stmt = mysqli_prepare(
    $conn,
    'INSERT INTO results (user_id, score, total_questions, percentage) VALUES (?, ?, ?, ?)'
);
mysqli_stmt_bind_param($stmt, 'iiid', $user_id, $score, $total, $percentage);
mysqli_stmt_execute($stmt);
$result_id = mysqli_insert_id($conn);
mysqli_stmt_close($stmt);

// Go to the result page for this attempt.
header('Location: result.php?id=' . $result_id);
exit;
