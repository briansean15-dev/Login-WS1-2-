<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../index.php");
    exit();
}

$currentUser = $_SESSION['lms_current_user'];

try {
    $dbPath = __DIR__ . '/../school.db';
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'student'");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($currentUser['name']); ?> (Teacher)</h1>
        <p><a href="../index.php?logout=true">Logout</a></p>
        <hr>

        <h2>Enrolled Students</h2>
        <table border="1" cellpadding="8">
            <tr>
                <th>Name</th>
                <th>Email</th>
            </tr>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?php echo htmlspecialchars($student['name']); ?></td>
                <td><?php echo htmlspecialchars($student['email']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>