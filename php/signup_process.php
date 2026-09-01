<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (strlen($username) < 3) {
        die("Username must be at least 3 characters.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }
    if (strlen($password) < 8) {
        die("Password must be at least 8 characters.");
    }

    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $checkStmt->execute([$username, $email]);

    if ($checkStmt->rowCount() > 0) {
        die("Username or email already taken.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $insertStmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $insertStmt->execute([$username, $email, $hashedPassword]);

    header("Location: ../login.html");
    exit;

} else {
    header("Location: ../signup.html");
    exit;
}
?>