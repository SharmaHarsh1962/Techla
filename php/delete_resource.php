<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'tester') {
    die("You don't have permission to do that.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resourceId = $_POST['id'] ?? '';
    $stmt = $pdo->prepare("DELETE FROM learn_resources WHERE id = ?");
    $stmt->execute([$resourceId]);
}

header('Location: ../learn.php');
exit;
?>