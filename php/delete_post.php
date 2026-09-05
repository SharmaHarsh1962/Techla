<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['id'] ?? '';

    $stmt = $pdo->prepare("DELETE FROM community_posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$postId, $_SESSION['user_id']]);
}

header('Location: ../community.php');
exit;
?>