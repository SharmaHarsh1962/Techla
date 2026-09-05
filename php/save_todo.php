<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../todos.php');
    exit;
}

$todoId = $_POST['id'] ?? '';
$task = trim($_POST['task']);
$projectId = $_POST['project_id'] ?? '';
$projectId = $projectId === '' ? null : $projectId;

if ($task === '') {
    die("Task cannot be empty.");
}

if ($todoId !== '') {
    $stmt = $pdo->prepare("UPDATE todos SET task = ?, project_id = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$task, $projectId, $todoId, $_SESSION['user_id']]);
} else {
    $stmt = $pdo->prepare("INSERT INTO todos (user_id, project_id, task) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $projectId, $task]);
}

header('Location: ../todos.php');
exit;
?>