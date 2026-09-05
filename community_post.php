<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
require 'php/db_connect.php';

$postId = $_GET['id'] ?? null;

if (!$postId) {
    header('Location: community.php');
    exit;
}

$postStmt = $pdo->prepare("
    SELECT community_posts.*, users.username
    FROM community_posts
    JOIN users ON community_posts.user_id = users.id
    WHERE community_posts.id = ?
");
$postStmt->execute([$postId]);
$post = $postStmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header('Location: community.php');
    exit;
}

$commentsStmt = $pdo->prepare("
    SELECT community_comments.*, users.username
    FROM community_comments
    JOIN users ON community_comments.user_id = users.id
    WHERE community_comments.post_id = ?
    ORDER BY community_comments.created_at ASC
");
$commentsStmt->execute([$postId]);
$comments = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
$pageTitle = 'Post by ' . $post['username'];
$pageDescription = substr($post['content'], 0, 155);
include 'includes/head.php';
?>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="page-wrapper">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <a href="community.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Community</a>

        <div class="feed-post feed-post-detail">
            <div class="feed-post-header">
                <i class="fas fa-circle-user feed-avatar"></i>
                <div>
                    <strong><?php echo htmlspecialchars($post['username']); ?></strong>
                    <small><?php echo date('M j, Y \a\t g:i A', strtotime($post['created_at'])); ?></small>
                </div>
            </div>
            <p class="feed-content"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            <?php if ($post['github_url']): ?>
                <a href="<?php echo htmlspecialchars($post['github_url']); ?>" target="_blank" class="feed-github-badge">
                    <i class="fab fa-github"></i> <?php echo htmlspecialchars($post['github_url']); ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="comments-section">
            <h3 class="comments-heading"><?php echo count($comments); ?> Comment<?php echo count($comments) == 1 ? '' : 's'; ?></h3>

            <form action="php/add_comment.php" method="POST" class="comment-form">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <input type="text" name="content" placeholder="Write a comment..." required>
                <button type="submit" class="signin-btn">Reply</button>
            </form>

            <div class="comments-list">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-item">
                        <i class="fas fa-circle-user comment-avatar"></i>
                        <div class="comment-body">
                            <div class="comment-meta">
                                <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                                <small><?php echo date('M j, g:i A', strtotime($comment['created_at'])); ?></small>
                            </div>
                            <p><?php echo htmlspecialchars($comment['content']); ?></p>
                        </div>
                        <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>
                            <form action="php/delete_comment.php" method="POST" class="comment-delete-form">
                                <input type="hidden" name="id" value="<?php echo $comment['id']; ?>">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <button type="submit" class="comment-delete-btn" title="Delete comment"><i class="fas fa-trash"></i></button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

</div>

</body>
</html>