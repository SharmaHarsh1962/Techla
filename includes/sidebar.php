<aside class="sidebar">
    <ul class="sidebar-links">
        <li><a href="index.php"><i class="fas fa-house"></i> Home</a></li>
        <li><a href="ai-chat.html"><i class="fas fa-comment-dots"></i> AI Chat</a></li>
        <li><a href="tools.html"><i class="fas fa-screwdriver-wrench"></i> Tools</a></li>
        <li><a href="notes.php"><i class="fas fa-note-sticky"></i> Notes</a></li>
        <li><a href="learn.html"><i class="fas fa-book-open"></i> Learn</a></li>
        <li><a href="projects.php"><i class="fas fa-folder"></i> Projects</a></li>
        <li><a href="community.html"><i class="fas fa-users"></i> Community</a></li>
        <li><a href="about.html"><i class="fas fa-circle-info"></i> About</a></li>
        <li><a href="todos.php"><i class="fas fa-circle-info"></i> Todo list</a></li>
    </ul>
    <div class="user-profile">
        <i class="fas fa-circle-user"></i>
        <div>
            <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <small>Free Plan</small>
        </div>
    </div>
</aside>