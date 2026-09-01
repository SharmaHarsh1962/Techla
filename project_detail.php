<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
require 'php/db_connect.php';

$projectId = $_GET['id'] ?? null;

if (!$projectId) {
    header('Location: projects.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
$stmt->execute([$projectId, $_SESSION['user_id']]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    header('Location: projects.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['title']); ?> - Techla</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">
        <svg class="logo-mark" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="logoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#a78bfa"/>
                    <stop offset="100%" stop-color="#7c3aed"/>
                </linearGradient>
            </defs>
            <polygon points="20,2 36,11 36,29 20,38 4,29 4,11" fill="url(#logoGrad)"/>
            <path d="M13 15 H27 M20 15 V27" stroke="white" stroke-width="3" stroke-linecap="round"/>
        </svg>
        TECHLA
    </div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="ai-chat.html">AI Chat</a>
        <a href="tools.html">Tools</a>
        <a href="learn.html">Learn</a>
        <a href="projects.php">Projects</a>
        <a href="community.html">Community</a>
        <a href="about.html">About</a>
    </div>
    <div class="nav-actions">
        <button class="icon-btn"><i class="fas fa-magnifying-glass"></i></button>
        <button class="icon-btn"><i class="fas fa-sun"></i></button>
        <a href="php/logout.php" class="signin-btn">Log Out</a>
    </div>
</nav>

<div class="page-wrapper">

    <aside class="sidebar">
        <ul class="sidebar-links">
            <li><a href="index.php"><i class="fas fa-house"></i> Home</a></li>
            <li><a href="ai-chat.html"><i class="fas fa-comment-dots"></i> AI Chat</a></li>
            <li><a href="tools.html"><i class="fas fa-screwdriver-wrench"></i> Tools</a></li>
            <li><a href="learn.html"><i class="fas fa-book-open"></i> Learn</a></li>
            <li><a href="projects.php"><i class="fas fa-folder"></i> Projects</a></li>
            <li><a href="community.html"><i class="fas fa-users"></i> Community</a></li>
            <li><a href="about.html"><i class="fas fa-circle-info"></i> About</a></li>
        </ul>
        <div class="user-profile">
            <i class="fas fa-circle-user"></i>
            <div>
                <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <small>Free Plan</small>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <a href="projects.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Projects</a>

        <div class="detail-card">
            <?php if ($project['image_path']): ?>
                <img src="<?php echo htmlspecialchars($project['image_path']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="detail-image">
            <?php endif; ?>

            <div class="detail-body">
                <h1><?php echo htmlspecialchars($project['title']); ?></h1>
                <p class="detail-date">Added <?php echo date('F j, Y', strtotime($project['created_at'])); ?></p>

                <p class="detail-description"><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>

                <?php if ($project['project_url']): ?>
                    <a href="<?php echo htmlspecialchars($project['project_url']); ?>" target="_blank" class="signin-btn">
                        <i class="fas fa-arrow-up-right-from-square"></i> Visit Project
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </main>

</div>

</body>
</html>