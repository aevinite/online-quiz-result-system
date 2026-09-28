<?php
// Shared admin header (include AFTER auth.php).
$admin_title = isset($admin_title) ? $admin_title : 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($admin_title); ?> | Quiz Admin</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <nav class="navbar">
    <div class="nav-inner">
      <a class="brand" href="dashboard.php">🛠️ Quiz Admin</a>
      <div class="nav-links">
        <a href="dashboard.php">Questions</a>
        <a href="add_question.php">Add Question</a>
        <a href="../index.php">View Site</a>
        <a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['admin_username']); ?>)</a>
      </div>
    </div>
  </nav>
  <main class="container">
