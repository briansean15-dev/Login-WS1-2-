<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$currentUser = $_SESSION['lms_current_user'];

try {
    $dbPath = __DIR__ . '/../school.db';
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $usersStmt = $pdo->query("SELECT * FROM users");
    $allUsers = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

    $historyStmt = $pdo->query("SELECT * FROM login_history ORDER BY id DESC LIMIT 10");
    $loginHistory = $historyStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($currentUser['name']); ?> (Admin)</h1>
        <p><a href="../index.php?logout=true">Logout</a></p>

        <hr>

        <h2>Registered Users</h2>
        <table border="1" cellpadding="8">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Name</th>
                <th>Role</th>
                <th>Email</th>
            </tr>
            <?php foreach ($allUsers as $u): ?>
            <tr>
                <td><?php echo htmlspecialchars($u['id']); ?></td>
                <td><?php echo htmlspecialchars($u['username']); ?></td>
                <td><?php echo htmlspecialchars($u['name']); ?></td>
                <td><?php echo htmlspecialchars($u['role']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h2>Recent Login History</h2>
        <table border="1" cellpadding="8">
            <tr>
                <th>User</th>
                <th>Role</th>
                <th>Timestamp</th>
                <th>Status</th>
            </tr>
            <?php foreach ($loginHistory as $log): ?>
            <tr>
                <td><?php echo htmlspecialchars($log['username']); ?></td>
                <td><?php echo htmlspecialchars($log['role']); ?></td>
                <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
                <td><?php echo htmlspecialchars($log['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>