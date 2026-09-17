<?php
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['username'] ?? '');
    $lastName  = trim($_POST['last-name'] ?? '');
    $newPassword = $_POST['password'] ?? '';
    $inputFullName = $firstName . ' ' . $lastName;

    if (!empty($firstName) && !empty($lastName) && !empty($newPassword)) {
        try {
            $dbPath = __DIR__ . '/school.db';
            $pdo = new PDO("sqlite:" . $dbPath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT id FROM users WHERE LOWER(username) = LOWER(?) OR LOWER(name) = LOWER(?)");
            $stmt->execute([$firstName, $inputFullName]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                
                $updateStmt = $pdo->prepare("UPDATE users SET pass = ? WHERE id = ?");
                $updateStmt->execute([$newPassword, $user['id']]);

                $success = "Password updated successfully! You can now <a href='index.php'>login</a>.";
            } else {
                $error = "No user found with that name/username.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form class="input-container" method="POST" autocomplete="off">
        <h1>Reset Password</h1>

        <?php if (!empty($error)): ?>
            <p style="color: red; font-size: 14px; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p style="color: green; font-size: 14px; text-align: center;"><?php echo $success; ?></p>
        <?php endif; ?>

        <label for="username">First Name</label>
        <input type="text" id="username" name="username" placeholder="Enter First Name" required>

        <label for="last-name">Last Name</label>
        <input type="text" id="last-name" name="last-name" placeholder="Enter Last Name" required>

        <label for="password">New Password</label>
        <input type="password" id="password" name="password" placeholder="Enter New Password" required>
        
        <button type="submit">Update Password</button>

        <p style="font-size: 14px; text-align: center;"><a href="index.php">Back to Login</a></p>
    </form>
</body>
</html>