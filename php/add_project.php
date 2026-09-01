<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../projects.php');
    exit;
}

$title = trim($_POST['title']);
$description = trim($_POST['description']);
$projectUrl = trim($_POST['project_url']);

if ($title === '' || $description === '') {
    die("Title and description are required.");
}

$imagePath = null;

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
    $safeFileName = uniqid('project_') . '.' . $extension;
    $destination = $uploadDir . $safeFileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
        $imagePath = 'assets/project_uploads/' . $safeFileName;
    } else {
        die("Failed to upload image.");
    }
}

$stmt = $pdo->prepare("INSERT INTO projects (user_id, title, description, image_path, project_url) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$_SESSION['user_id'], $title, $description, $imagePath, $projectUrl]);

header('Location: ../projects.php');
exit;
?>