<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
require 'php/db_connect.php';

$stmt = $pdo->prepare("
    SELECT community_posts.*, users.username,
        (SELECT COUNT(*) FROM community_comments WHERE community_comments.post_id = community_posts.id) AS comment_count
    FROM community_posts
    JOIN users ON community_posts.user_id = users.id
    ORDER BY community_posts.created_at DESC
");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
$pageTitle = 'Community';
$pageDescription = "See what others are building on Techla's community feed, share your own projects, and join the conversation.";
include 'includes/head.php';
?>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="page-wrapper">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content projects-page">
        <div class="projects-header">
            <div>
                <h1>Community</h1>
                <p class="subtitle">Share updates and projects with everyone</p>
            </div>
            <button id="addPostBtn" class="signin-btn">
                <i class="fas fa-plus"></i> New Post
            </button>
        </div>

        <div class="feed">
            <?php if (empty($posts)): ?>
                <p class="no-projects">No posts yet. Be the first to share something.</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="feed-post" onclick="window.location='community_post.php?id=<?php echo $post['id']; ?>'">
                        <div class="feed-post-header">
                            <i class="fas fa-circle-user feed-avatar"></i>
                            <div>
                                <strong><?php echo htmlspecialchars($post['username']); ?></strong>
                                <small><?php echo date('M j, Y \a\t g:i A', strtotime($post['created_at'])); ?></small>
                            </div>
                            <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                                <form action="php/delete_post.php" method="POST" class="feed-delete-form" onclick="event.stopPropagation()">
                                    <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                                    <button type="submit" class="feed-delete-btn" title="Delete post" onclick="return confirm('Delete this post? All comments will be deleted too.')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <p class="feed-content"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                        <?php if ($post['github_url']): ?>
                            <a href="<?php echo htmlspecialchars($post['github_url']); ?>" target="_blank" class="feed-github-badge" onclick="event.stopPropagation()">
                                <i class="fab fa-github"></i> <?php echo htmlspecialchars($post['github_url']); ?>
                            </a>
                        <?php endif; ?>
                        <div class="feed-post-footer">
                            <span><i class="fas fa-comment"></i> <?php echo $post['comment_count']; ?> comment<?php echo $post['comment_count'] == 1 ? '' : 's'; ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

</div>

<div class="modal-overlay" id="postModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>New Post</h3>
            <button id="closePostModalBtn" class="modal-close"><i class="fas fa-xmark"></i></button>
        </div>

        <form action="php/add_post.php" method="POST">
            <label for="content">What are you working on?</label>
            <textarea id="content" name="content" rows="4" required></textarea>

            <label for="github_url">GitHub Link (optional)</label>
            <input type="url" id="github_url" name="github_url" placeholder="https://github.com/...">

            <button type="submit" class="signin-btn full-width">Post</button>
        </form>
    </div>
</div>

<script>
document.getElementById('addPostBtn').addEventListener('click', () => {
    document.getElementById('postModal').classList.add('active');
});
document.getElementById('closePostModalBtn').addEventListener('click', () => {
    document.getElementById('postModal').classList.remove('active');
});
</script>

</body>
</html>