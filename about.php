<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
require 'php/db_connect.php';

$isOwner = ($_SESSION['username'] === 'tester');

$stmt = $pdo->query("SELECT * FROM upcoming_project ORDER BY id DESC LIMIT 1");
$upcoming = $stmt->fetch(PDO::FETCH_ASSOC);

$pageTitle = 'About';
$pageDescription = "Learn more about Harsh Sharma, the creator of Techla, and see what's coming next.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'includes/head.php'; ?>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="page-wrapper">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content about-page">

        <div class="about-card">
            <div class="about-profile-header">
                <img src="assets/harsh_dev.jpg" alt="Harsh Sharma" class="about-avatar-img">
    <div>
        <h1>Harsh Sharma</h1>
        <p class="about-role">Creator of Techla</p>
    </div>
</div>
            <p class="about-bio">
                Hi, I'm Harsh — a BS CIT student based in Nepal, currently building Techla as a space to experiment with web development, AI, and full-stack projects. When I'm not coding, you'll usually find me gaming or exploring new tech. This site is where I share what I'm building, learning, and thinking about next.
            </p>

            <div class="about-links">
                <a href="https://github.com/SharmaHarsh1962" target="_blank" class="about-link">
                    <i class="fab fa-github"></i> GitHub
                </a>
                <a href="https://www.linkedin.com/in/harsh-sharma-b27380434" target="_blank" class="about-link">
                    <i class="fab fa-linkedin"></i> LinkedIn
                </a>
                <a href="https://www.instagram.com/fitflix_2062/" target="_blank" class="about-link">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
                <a href="mailto:sharmasarita0669@gmail.com" class="about-link">
                    <i class="fas fa-envelope"></i> Email
                </a>
            </div>
        </div>

        <div class="about-card">
            <div class="about-header">
                <h2><i class="fas fa-rocket"></i> Upcoming Project</h2>
                <?php if ($isOwner): ?>
                    <button id="editUpcomingBtn" class="signin-btn">
                        <i class="fas fa-pen"></i> Edit
                    </button>
                <?php endif; ?>
            </div>

            <?php if ($upcoming && $upcoming['image_path']): ?>
                <img src="<?php echo htmlspecialchars($upcoming['image_path']); ?>" alt="Upcoming project" class="upcoming-image">
                <h3><?php echo htmlspecialchars($upcoming['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($upcoming['description'])); ?></p>
            <?php else: ?>
                <div class="upcoming-placeholder">
                    <i class="fas fa-hourglass-half"></i>
                    <p>Soon Gone Devlop</p>
                </div>
            <?php endif; ?>
        </div>

    </main>

</div>

<?php if ($isOwner): ?>
<div class="modal-overlay" id="upcomingModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Update Upcoming Project</h3>
            <button id="closeUpcomingModalBtn" class="modal-close"><i class="fas fa-xmark"></i></button>
        </div>

        <form action="php/save_upcoming.php" method="POST" enctype="multipart/form-data">
            <label for="up_title">Title</label>
            <input type="text" id="up_title" name="title" value="<?php echo $upcoming ? htmlspecialchars($upcoming['title']) : ''; ?>" required>

            <label for="up_description">Description</label>
            <textarea id="up_description" name="description" rows="4" required><?php echo $upcoming ? htmlspecialchars($upcoming['description']) : ''; ?></textarea>

            <label for="up_image">Image</label>
            <input type="file" id="up_image" name="image" accept="image/*">

            <button type="submit" class="signin-btn full-width">Save</button>
        </form>
    </div>
</div>

<script>
document.getElementById('editUpcomingBtn').addEventListener('click', () => {
    document.getElementById('upcomingModal').classList.add('active');
});
document.getElementById('closeUpcomingModalBtn').addEventListener('click', () => {
    document.getElementById('upcomingModal').classList.remove('active');
});
</script>
<?php endif; ?>

</body>
</html>