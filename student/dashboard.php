<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

$currentUser = $_SESSION['lms_current_user'];


try {
    $dbPath = __DIR__ . '/../school.db';
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($currentUser['name']); ?> (Student)</h1>
        <p><a href="../index.php?logout=true">Logout</a></p>
        <hr>
        <p>This is your student portal. Your courses and materials will appear here.</p>
    </div>
</body>
</html>