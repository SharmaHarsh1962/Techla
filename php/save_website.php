<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'tester') {
    die("You don't have permission to do that.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../tools.php');
    exit;
}

$id = $_POST['id'] ?? '';
$title = trim($_POST['title']);
$url = trim($_POST['url']);
$description = trim($_POST['description']);

if ($title === '' || $url === '') {
    die("Title and URL are required.");
}

if ($id !== '') {
    $stmt = $pdo->prepare("UPDATE helpful_websites SET title = ?, url = ?, description = ? WHERE id = ?");
    $stmt->execute([$title, $url, $description, $id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO helpful_websites (title, url, description) VALUES (?, ?, ?)");
    $stmt->execute([$title, $url, $description]);
}

header('Location: ../tools.php');
exit;
?>