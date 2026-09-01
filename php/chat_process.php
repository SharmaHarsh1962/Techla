<?php
session_start();
require 'db_connect.php';
require 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$userMessage = trim($data['message'] ?? '');

if ($userMessage === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Empty message']);
    exit;
}

$userId = $_SESSION['user_id'];

$insertUser = $pdo->prepare("INSERT INTO chat_messages (user_id, role, message) VALUES (?, 'user', ?)");
$insertUser->execute([$userId, $userMessage]);

$payload = json_encode([
    'model' => NVIDIA_MODEL,
    'messages' => [
        ['role' => 'system', 'content' => 'You are Techla AI, a helpful assistant on Harsh\'s personal website.'],
        ['role' => 'user', 'content' => $userMessage]
    ],
    'max_tokens' => 512,
    'temperature' => 0.7
]);

$ch = curl_init(NVIDIA_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . NVIDIA_API_KEY
]);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 45);

$response = curl_exec($ch);
$curlError = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'cURL failed', 'details' => $curlError]);
    exit;
}

if ($httpCode !== 200) {
    http_response_code(500);
    echo json_encode(['error' => 'AI request failed', 'details' => $response]);
    exit;
}

$result = json_decode($response, true);
$aiReply = $result['choices'][0]['message']['content'] ?? 'Sorry, I had trouble responding.';

$insertAi = $pdo->prepare("INSERT INTO chat_messages (user_id, role, message) VALUES (?, 'ai', ?)");
$insertAi->execute([$userId, $aiReply]);

echo json_encode(['reply' => $aiReply]);
?>