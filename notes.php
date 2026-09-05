<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
require 'php/db_connect.php';

$stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY updated_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php
$pageTitle = 'Notes';
$pageDescription = 'Your personal notes on Techla — jot down ideas, plans, and reminders in one place.';
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
                <h1>Notes</h1>
                <p class="subtitle">Jot down ideas, plans, and reminders</p>
            </div>
            <button id="addNoteBtn" class="signin-btn">
                <i class="fas fa-plus"></i> Add Note
            </button>
        </div>

        <div class="projects-grid">
            <?php if (empty($notes)): ?>
                <p class="no-projects">No notes yet. Click "Add Note" to write your first one.</p>
            <?php else: ?>
                <?php foreach ($notes as $note): ?>
                    <div class="note-card"
                        data-id="<?php echo $note['id']; ?>"
                        data-title="<?php echo htmlspecialchars($note['title'], ENT_QUOTES); ?>"
                        data-content="<?php echo htmlspecialchars($note['content'], ENT_QUOTES); ?>">
                        <div class="note-card-actions">
                            <button class="note-edit-btn" title="Edit"><i class="fas fa-pen"></i></button>
                            <form action="php/delete_note.php" method="POST" class="note-delete-form">
                                <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
                                <button type="submit" class="note-delete-btn" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                        <h3><?php echo htmlspecialchars($note['title']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($note['content'], 0, 100)); ?></p>
                        <small class="note-date">Updated <?php echo date('M j, Y', strtotime($note['updated_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

</div>

<div class="modal-overlay" id="noteModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 id="noteModalTitle">Add New Note</h3>
            <button id="closeNoteModalBtn" class="modal-close"><i class="fas fa-xmark"></i></button>
        </div>

        <form action="php/save_note.php" method="POST" id="noteForm">
            <input type="hidden" name="id" id="noteId" value="">

            <label for="noteTitle">Title</label>
            <input type="text" id="noteTitle" name="title" required>

            <label for="noteContent">Content</label>
            <textarea id="noteContent" name="content" rows="6" required></textarea>

            <button type="submit" class="signin-btn full-width" id="noteSubmitBtn">Add Note</button>
        </form>
    </div>
</div>

<script src="js/notes.js"></script>
</body>
</html>