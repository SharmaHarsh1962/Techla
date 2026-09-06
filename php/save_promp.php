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
$aiModel = trim($_POST['ai_model']);
$taskCategory = trim($_POST['task_category']);
$title = trim($_POST['title']);
$promptText = trim($_POST['prompt_text']);

if ($title === '' || $promptText === '') {
    die("Title and prompt text are required.");
}

if ($id !== '') {
    $stmt = $pdo->prepare("UPDATE tool_prompts SET ai_model = ?, task_category = ?, title = ?, prompt_text = ? WHERE id = ?");
    $stmt->execute([$aiModel, $taskCategory, $title, $promptText, $id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO tool_prompts (ai_model, task_category, title, prompt_text) VALUES (?, ?, ?, ?)");
    $stmt->execute([$aiModel, $taskCategory, $title, $promptText]);
}

header('Location: ../tools.php');
exit;
?>