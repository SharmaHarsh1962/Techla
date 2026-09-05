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

$content = trim($_POST['content']);
$githubUrl = trim($_POST['github_url']);

if ($content === '') {
    die("Post content cannot be empty.");
}

$stmt = $pdo->prepare("INSERT INTO community_posts (user_id, content, github_url) VALUES (?, ?, ?)");
$stmt->execute([$_SESSION['user_id'], $content, $githubUrl]);

header('Location: ../community.php');
exit;
?>