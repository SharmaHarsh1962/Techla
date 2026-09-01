<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../notes.php');
    exit;
}

$noteId = $_POST['id'] ?? '';
$title = trim($_POST['title']);
$content = trim($_POST['content']);

if ($title === '' || $content === '') {
    die("Title and content are required.");
}

if ($noteId !== '') {
    $stmt = $pdo->prepare("UPDATE notes SET title = ?, content = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $content, $noteId, $_SESSION['user_id']]);
} else {
    $stmt = $pdo->prepare("INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $title, $content]);
}

header('Location: ../notes.php');
exit;
?>