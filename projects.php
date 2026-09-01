<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
require 'php/db_connect.php';

$stmt = $pdo->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Projects - Techla</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="page-wrapper">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content projects-page">
        <div class="projects-header">
            <div>
                <h1>My Projects</h1>
                <p class="subtitle">Showcase what you've built</p>
            </div>
            <button id="addProjectBtn" class="signin-btn">
                <i class="fas fa-plus"></i> Add Project
            </button>
        </div>

        <div class="projects-grid">
            <?php if (empty($projects)): ?>
                <p class="no-projects">No projects yet. Click "Add Project" to showcase your first one.</p>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <a href="project_detail.php?id=<?php echo $project['id']; ?>" class="project-card">
                        <?php if ($project['image_path']): ?>
                            <img src="<?php echo htmlspecialchars($project['image_path']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                        <?php else: ?>
                            <div class="project-card-placeholder"><i class="fas fa-image"></i></div>
                        <?php endif; ?>
                        <div class="project-card-body">
                            <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($project['description'], 0, 80)); ?>...</p>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

</div>
<div class="modal-overlay" id="addProjectModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Add New Project</h3>
            <button id="closeModalBtn" class="modal-close"><i class="fas fa-xmark"></i></button>
        </div>

        <form action="php/add_project.php" method="POST" enctype="multipart/form-data">
            <label for="title">Project Title</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="project_url">Project Link (optional)</label>
            <input type="url" id="project_url" name="project_url" placeholder="https://...">

            <label for="image">Thumbnail Image</label>
            <input type="file" id="image" name="image" accept="image/*">

            <button type="submit" class="signin-btn full-width">Add Project</button>
        </form>
    </div>
</div>

<script>
document.getElementById('addProjectBtn').addEventListener('click', () => {
    document.getElementById('addProjectModal').classList.add('active');
});
document.getElementById('closeModalBtn').addEventListener('click', () => {
    document.getElementById('addProjectModal').classList.remove('active');
});
</script>
</body>
</html>