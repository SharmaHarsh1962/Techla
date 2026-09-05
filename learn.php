<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
require 'php/db_connect.php';

$isOwner = ($_SESSION['username'] === 'tester');

$categories = ['Web Development', 'AI & Machine Learning', 'Design', 'Programming', 'Tools & Utilities'];

$stmt = $pdo->query("SELECT * FROM learn_resources ORDER BY category, title");
$allResources = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grouped = [];
foreach ($categories as $cat) {
    $grouped[$cat] = [];
}
foreach ($allResources as $res) {
    $grouped[$res['category']][] = $res;
}

$pageTitle = 'Learn';
$pageDescription = 'A curated collection of learning resources — web development, AI, design, programming, and tools.';
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

    <main class="main-content projects-page">
        <div class="projects-header">
            <div>
                <h1>Learn</h1>
                <p class="subtitle">Curated resources worth checking out</p>
            </div>
            <?php if ($isOwner): ?>
                <button id="addResourceBtn" class="signin-btn">
                    <i class="fas fa-plus"></i> Add Resource
                </button>
            <?php endif; ?>
        </div>

        <?php foreach ($categories as $cat): ?>
            <div class="todo-section">
                <h2 class="todo-section-title"><i class="fas fa-bookmark"></i> <?php echo htmlspecialchars($cat); ?></h2>

                <?php if (empty($grouped[$cat])): ?>
                    <p class="no-projects">No resources added yet.</p>
                <?php else: ?>
                    <div class="resource-list">
                        <?php foreach ($grouped[$cat] as $res): ?>
                            <div class="resource-item">
                                <div class="resource-body">
                                    <a href="<?php echo htmlspecialchars($res['url']); ?>" target="_blank" class="resource-title">
                                        <?php echo htmlspecialchars($res['title']); ?> <i class="fas fa-arrow-up-right-from-square"></i>
                                    </a>
                                    <p><?php echo htmlspecialchars($res['description']); ?></p>
                                </div>
                                <?php if ($isOwner): ?>
                                    <div class="resource-actions">
                                        <button class="resource-edit-btn" title="Edit"
                                            data-id="<?php echo $res['id']; ?>"
                                            data-title="<?php echo htmlspecialchars($res['title'], ENT_QUOTES); ?>"
                                            data-url="<?php echo htmlspecialchars($res['url'], ENT_QUOTES); ?>"
                                            data-description="<?php echo htmlspecialchars($res['description'], ENT_QUOTES); ?>"
                                            data-category="<?php echo htmlspecialchars($res['category'], ENT_QUOTES); ?>">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <form action="php/delete_resource.php" method="POST" class="resource-delete-form">
                                            <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                                            <button type="submit" class="resource-delete-btn" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </main>

</div>

<?php if ($isOwner): ?>
<div class="modal-overlay" id="resourceModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 id="resourceModalTitle">Add Resource</h3>
            <button id="closeResourceModalBtn" class="modal-close"><i class="fas fa-xmark"></i></button>
        </div>

        <form action="php/save_resource.php" method="POST" id="resourceForm">
            <input type="hidden" name="id" id="resourceId" value="">

            <label for="resourceTitle">Title</label>
            <input type="text" id="resourceTitle" name="title" required>

            <label for="resourceUrl">URL</label>
            <input type="url" id="resourceUrl" name="url" placeholder="https://..." required>

            <label for="resourceDescription">Description</label>
            <textarea id="resourceDescription" name="description" rows="3" required></textarea>

            <label for="resourceCategory">Category</label>
            <select id="resourceCategory" name="category" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="signin-btn full-width" id="resourceSubmitBtn">Add Resource</button>
        </form>
    </div>
</div>

<script src="js/learn.js"></script>
<?php endif; ?>

</body>
</html>