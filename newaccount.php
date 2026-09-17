<?php
require_once 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = trim($_POST['role']); 

    if (!empty($username) && !empty($password) && !empty($role)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $password, $role]);
            $message = "Account created successfully! <a href='index.php'>Login here</a>";
        } catch (PDOException $e) {
            $message = "Error: Username might already exist.";
        }
    } else {
        $message = "Please fill out all fields.";
    }
}
?>