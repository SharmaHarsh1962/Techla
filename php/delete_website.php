<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'tester') {
    die("You don't have permission to do that.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $stmt = $pdo->prepare("DELETE FROM helpful_websites WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: ../tools.php');
exit;
?>