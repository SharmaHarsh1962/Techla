<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

if ($_SESSION['username'] !== 'tester') {
    die("You don't have permission to do that.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../about.php');
    exit;
}

$title = trim($_POST['title']);
$description = trim($_POST['description']);

if ($title === '' || $description === '') {
    die("Title and description are required.");
}

$existing = $pdo->query("SELECT id, image_path FROM upcoming_project ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$imagePath = $existing['image_path'] ?? null;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $fileType = $_FILES['image']['type'];

    if (!in_array($fileType, $allowedTypes)) {
        die("Only JPG, PNG, WEBP, or GIF images are allowed.");
    }

    $maxSize = 5 * 1024 * 1024;
    if ($_FILES['image']['size'] > $maxSize) {
        die("Image must be under 5MB.");
    }

    $uploadDir = '../assets/project_uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $safeFileName = uniqid('upcoming_') . '.' . $extension;
    $destination = $uploadDir . $safeFileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
        $imagePath = 'assets/project_uploads/' . $safeFileName;
    }
}

if ($existing) {
    $stmt = $pdo->prepare("UPDATE upcoming_project SET title = ?, description = ?, image_path = ? WHERE id = ?");
    $stmt->execute([$title, $description, $imagePath, $existing['id']]);
} else {
    $stmt = $pdo->prepare("INSERT INTO upcoming_project (title, description, image_path) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $imagePath]);
}

header('Location: ../about.php');
exit;
?>