<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'tester') {
    die("You don't have permission to do that.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../learn.php');
    exit;
}

$resourceId = $_POST['id'] ?? '';
$title = trim($_POST['title']);
$url = trim($_POST['url']);
$description = trim($_POST['description']);
$category = trim($_POST['category']);

if ($title === '' || $url === '' || $category === '') {
    die("Title, URL, and category are required.");
}

if ($resourceId !== '') {
    $stmt = $pdo->prepare("UPDATE learn_resources SET title = ?, url = ?, description = ?, category = ? WHERE id = ?");
    $stmt->execute([$title, $url, $description, $category, $resourceId]);
} else {
    $stmt = $pdo->prepare("INSERT INTO learn_resources (title, url, description, category) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $url, $description, $category]);
}

header('Location: ../learn.php');
exit;
?>