<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
require 'php/db_connect.php';

$isOwner = ($_SESSION['username'] === 'tester');

$aiModels = ['Claude', 'ChatGPT', 'Gemini', 'Upcoming'];
$taskCategories = ['Coding', 'Debugging', 'Writing', 'Brainstorming', 'Learning'];

$promptsStmt = $pdo->query("SELECT * FROM tool_prompts ORDER BY ai_model, task_category, title");
$allPrompts = $promptsStmt->fetchAll(PDO::FETCH_ASSOC);

$grouped = [];
foreach ($aiModels as $model) {
    $grouped[$model] = [];
    foreach ($taskCategories as $cat) {
        $grouped[$model][$cat] = [];
    }
}
foreach ($allPrompts as $p) {
    if (isset($grouped[$p['ai_model']][$p['task_category']])) {
        $grouped[$p['ai_model']][$p['task_category']][] = $p;
    }
}

$websitesStmt = $pdo->query("SELECT * FROM helpful_websites ORDER BY title");
$websites = $websitesStmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Tools';
$pageDescription = 'Pre-made AI prompts organized by model and task, plus a curated list of helpful websites.';
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
                <h1>Tools</h1>
                <p class="subtitle">Pre-made prompts and helpful sites</p>
            </div>
            <?php if ($isOwner): ?>
                <div style="display:flex; gap:10px;">
                    <button id="addPromptBtn" class="signin-btn"><i class="fas fa-plus"></i> Add Prompt</button>
                    <button id="addWebsiteBtn" class="signin-btn"><i class="fas fa-plus"></i> Add Website</button>
                </div>
            <?php endif; ?>
        </div>

        <h2 class="tools-main-heading"><i class="fas fa-wand-magic-sparkles"></i> Pre-Made Prompts</h2>

        <?php foreach ($aiModels as $model):
            $hasAny = false;
            foreach ($taskCategories as $cat) {
                if (!empty($grouped[$model][$cat])) { $hasAny = true; break; }
            }
        ?>
            <div class="model-section">
                <h3 class="model-title"><i class="fas fa-robot"></i> <?php echo htmlspecialchars($model); ?></h3>

                <?php if (!$hasAny): ?>
                    <p class="no-projects">No prompts added yet for <?php echo htmlspecialchars($model); ?>.</p>
                <?php else: ?>
                    <?php foreach ($taskCategories as $cat): ?>
                        <?php if (!empty($grouped[$model][$cat])): ?>
                            <h4 class="task-subtitle"><?php echo htmlspecialchars($cat); ?></h4>
                            <div class="resource-list">
                                <?php foreach ($grouped[$model][$cat] as $prompt): ?>
                                    <div class="prompt-item">
                                        <div class="prompt-body">
                                            <strong><?php echo htmlspecialchars($prompt['title']); ?></strong>
                                            <p class="prompt-text" id="prompt-<?php echo $prompt['id']; ?>"><?php echo nl2br(htmlspecialchars($prompt['prompt_text'])); ?></p>
                                        </div>
                                        <div class="resource-actions">
                                            <button class="copy-prompt-btn" data-target="prompt-<?php echo $prompt['id']; ?>" title="Copy">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <?php if ($isOwner): ?>
                                                <button class="prompt-edit-btn" title="Edit"
                                                    data-id="<?php echo $prompt['id']; ?>"
                                                    data-model="<?php echo htmlspecialchars($prompt['ai_model'], ENT_QUOTES); ?>"
                                                    data-category="<?php echo htmlspecialchars($prompt['task_category'], ENT_QUOTES); ?>"
                                                    data-title="<?php echo htmlspecialchars($prompt['title'], ENT_QUOTES); ?>"
                                                    data-text="<?php echo htmlspecialchars($prompt['prompt_text'], ENT_QUOTES); ?>">
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                                <form action="php/delete_prompt.php" method="POST" class="resource-delete-form">
                                                    <input type="hidden" name="id" value="<?php echo $prompt['id']; ?>">
                                                    <button type="submit" class="resource-delete-btn" title="Delete"><i class="fas fa-trash"></i></button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <h2 class="tools-main-heading"><i class="fas fa-globe"></i> Helpful Websites</h2>
        <?php if (empty($websites)): ?>
            <p class="no-projects">No websites added yet.</p>
        <?php else: ?>
            <div class="resource-list">
                <?php foreach ($websites as $site): ?>
                    <div class="resource-item">
                        <div class="resource-body">
                            <a href="<?php echo htmlspecialchars($site['url']); ?>" target="_blank" class="resource-title">
                                <?php echo htmlspecialchars($site['title']); ?> <i class="fas fa-arrow-up-right-from-square"></i>
                            </a>
                            <p><?php echo htmlspecialchars($site['description']); ?></p>
                        </div>
                        <?php if ($isOwner): ?>
                            <div class="resource-actions">
                                <button class="website-edit-btn" title="Edit"
                                    data-id="<?php echo $site['id']; ?>"
                                    data-title="<?php echo htmlspecialchars($site['title'], ENT_QUOTES); ?>"
                                    data-url="<?php echo htmlspecialchars($site['url'], ENT_QUOTES); ?>"
                                    data-description="<?php echo htmlspecialchars($site['description'], ENT_QUOTES); ?>">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <form action="php/delete_website.php" method="POST" class="resource-delete-form">
                                    <input type="hidden" name="id" value="<?php echo $site['id']; ?>">
                                    <button type="submit" class="resource-delete-btn" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

</div>

<?php if ($isOwner): ?>
<div class="modal-overlay" id="promptModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 id="promptModalTitle">Add Prompt</h3>
            <button id="closePromptModalBtn" class="modal-close"><i class="fas fa-xmark"></i></button>
        </div>
        <form action="php/save_prompt.php" method="POST" id="promptForm">
            <input type="hidden" name="id" id="promptId" value="">

            <label for="promptModel">AI Model</label>
            <select id="promptModel" name="ai_model" required>
                <?php foreach ($aiModels as $model): ?>
                    <option value="<?php echo htmlspecialchars($model); ?>"><?php echo htmlspecialchars($model); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="promptCategory">Task Category</label>
            <select id="promptCategory" name="task_category" required>
                <?php foreach ($taskCategories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="promptTitle">Title</label>
            <input type="text" id="promptTitle" name="title" required>

            <label for="promptText">Prompt Text</label>
            <textarea id="promptText" name="prompt_text" rows="5" required></textarea>

            <button type="submit" class="signin-btn full-width" id="promptSubmitBtn">Add Prompt</button>
        </form>
    </div>
</div>

<div class="modal-overlay" id="websiteModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 id="websiteModalTitle">Add Website</h3>
            <button id="closeWebsiteModalBtn" class="modal-close"><i class="fas fa-xmark"></i></button>
        </div>
        <form action="php/save_website.php" method="POST" id="websiteForm">
            <input type="hidden" name="id" id="websiteId" value="">

            <label for="websiteTitle">Title</label>
            <input type="text" id="websiteTitle" name="title" required>

            <label for="websiteUrl">URL</label>
            <input type="url" id="websiteUrl" name="url" placeholder="https://..." required>

            <label for="websiteDescription">Description</label>
            <textarea id="websiteDescription" name="description" rows="3" required></textarea>

            <button type="submit" class="signin-btn full-width" id="websiteSubmitBtn">Add Website</button>
        </form>
    </div>
</div>

<script src="js/tools.js"></script>
<?php endif; ?>

<script>
document.querySelectorAll('.copy-prompt-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const text = document.getElementById(btn.dataset.target).innerText;
        navigator.clipboard.writeText(text).then(() => {
            const icon = btn.querySelector('i');
            icon.classList.remove('fa-copy');
            icon.classList.add('fa-check');
            setTimeout(() => {
                icon.classList.remove('fa-check');
                icon.classList.add('fa-copy');
            }, 1500);
        });
    });
});
</script>

</body>
</html>