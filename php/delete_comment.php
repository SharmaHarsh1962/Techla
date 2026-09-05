<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = $_POST['id'] ?? '';
    $postId = $_POST['post_id'] ?? '';

    $stmt = $pdo->prepare("DELETE FROM community_comments WHERE id = ? AND user_id = ?");
    $stmt->execute([$commentId, $_SESSION['user_id']]);

    header('Location: ../community_post.php?id=' . $postId);
    exit;
}

header('Location: ../community.php');
exit;
?>