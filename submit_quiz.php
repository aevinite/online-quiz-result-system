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
$result = $conn->query('SELECT id, correct_option FROM questions');
$total  = 0;
$score  = 0;

foreach ($result as $row) {
    $total++;
    $qid = $row['id'];
    if (isset($answers[$qid]) && strtoupper($answers[$qid]) === strtoupper($row['correct_option'])) {
        $score++;
    }
}

$percentage = $total > 0 ? round(($score / $total) * 100, 2) : 0;

// Store the attempt.
$stmt = $conn->prepare(
    'INSERT INTO results (user_id, score, total_questions, percentage) VALUES (?, ?, ?, ?) RETURNING id'
);
$stmt->execute([$user_id, $score, $total, $percentage]);
$result_id = $stmt->fetchColumn();

// Go to the result page for this attempt.
header('Location: result.php?id=' . $result_id);
exit;
