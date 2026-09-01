<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
require 'php/db_connect.php';

$userId = $_SESSION['user_id'];

$generalStmt = $pdo->prepare("SELECT * FROM todos WHERE user_id = ? AND project_id IS NULL ORDER BY created_at DESC");
$generalStmt->execute([$userId]);
$generalTodos = $generalStmt->fetchAll(PDO::FETCH_ASSOC);

$projectTodosStmt = $pdo->prepare("
    SELECT todos.*, projects.title AS project_title
    FROM todos
    JOIN projects ON todos.project_id = projects.id
    WHERE todos.user_id = ?
    ORDER BY todos.created_at DESC
");
$projectTodosStmt->execute([$userId]);
$projectTodos = $projectTodosStmt->fetchAll(PDO::FETCH_ASSOC);

$projectsStmt = $pdo->prepare("SELECT id, title FROM projects WHERE user_id = ? ORDER BY title");
$projectsStmt->execute([$userId]);
$allProjects = $projectsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Dos - Techla</title>
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
                <h1>To-Dos</h1>
                <p class="subtitle">Track general tasks and project-specific work</p>
            </div>
            <button id="addTodoBtn" class="signin-btn">
                <i class="fas fa-plus"></i> Add To-Do
            </button>
        </div>

        <div class="todo-section">
            <h2 class="todo-section-title"><i class="fas fa-list-check"></i> General To-Dos</h2>
            <div class="todo-list">
                <?php if (empty($generalTodos)): ?>
                    <p class="no-projects">No general to-dos yet.</p>
                <?php else: ?>
                    <?php foreach ($generalTodos as $todo): ?>
                        <div class="todo-item <?php echo $todo['is_done'] ? 'todo-done' : ''; ?>"
                             data-id="<?php echo $todo['id']; ?>"
                             data-task="<?php echo htmlspecialchars($todo['task'], ENT_QUOTES); ?>"
                             data-project="">
                            <form action="php/toggle_todo.php" method="POST" class="todo-toggle-form">
                                <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                                <button type="submit" class="todo-checkbox">
                                    <?php if ($todo['is_done']): ?><i class="fas fa-check"></i><?php endif; ?>
                                </button>
                            </form>
                            <span class="todo-task"><?php echo htmlspecialchars($todo['task']); ?></span>
                            <div class="todo-actions">
                                <button class="todo-edit-btn" title="Edit"><i class="fas fa-pen"></i></button>
                                <form action="php/delete_todo.php" method="POST" class="todo-delete-form">
                                    <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                                    <button type="submit" class="todo-delete-btn" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="todo-section">
            <h2 class="todo-section-title"><i class="fas fa-folder"></i> Project To-Dos</h2>
            <div class="todo-list">
                <?php if (empty($projectTodos)): ?>
                    <p class="no-projects">No project to-dos yet.</p>
                <?php else: ?>
                    <?php foreach ($projectTodos as $todo): ?>
                        <div class="todo-item <?php echo $todo['is_done'] ? 'todo-done' : ''; ?>"
                             data-id="<?php echo $todo['id']; ?>"
                             data-task="<?php echo htmlspecialchars($todo['task'], ENT_QUOTES); ?>"
                             data-project="<?php echo $todo['project_id']; ?>">
                            <form action="php/toggle_todo.php" method="POST" class="todo-toggle-form">
                                <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                                <button type="submit" class="todo-checkbox">
                                    <?php if ($todo['is_done']): ?><i class="fas fa-check"></i><?php endif; ?>
                                </button>
                            </form>
                            <span class="todo-task"><?php echo htmlspecialchars($todo['task']); ?></span>
                            <span class="todo-project-badge"><?php echo htmlspecialchars($todo['project_title']); ?></span>
                            <div class="todo-actions">
                                <button class="todo-edit-btn" title="Edit"><i class="fas fa-pen"></i></button>
                                <form action="php/delete_todo.php" method="POST" class="todo-delete-form">
                                    <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                                    <button type="submit" class="todo-delete-btn" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

</div>

<div class="modal-overlay" id="todoModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 id="todoModalTitle">Add New To-Do</h3>
            <button id="closeTodoModalBtn" class="modal-close"><i class="fas fa-xmark"></i></button>
        </div>

        <form action="php/save_todo.php" method="POST" id="todoForm">
            <input type="hidden" name="id" id="todoId" value="">

            <label for="todoTask">Task</label>
            <input type="text" id="todoTask" name="task" required>

            <label for="todoProject">Link to Project (optional)</label>
            <select id="todoProject" name="project_id">
                <option value="">None — General To-Do</option>
                <?php foreach ($allProjects as $proj): ?>
                    <option value="<?php echo $proj['id']; ?>"><?php echo htmlspecialchars($proj['title']); ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="signin-btn full-width" id="todoSubmitBtn">Add To-Do</button>
        </form>
    </div>
</div>

<script src="js/todos.js"></script>
</body>
</html>