<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../community.php');
    exit;
}

$postId = $_POST['post_id'] ?? '';
$content = trim($_POST['content']);

if ($content === '' || $postId === '') {
    header('Location: ../community_post.php?id=' . $postId);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO community_comments (post_id, user_id, content) VALUES (?, ?, ?)");
$stmt->execute([$postId, $_SESSION['user_id'], $content]);

header('Location: ../community_post.php?id=' . $postId);
exit;
?>