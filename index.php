<?php session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.html');
exit; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Techla</title>
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
        <a href="projects.html">Projects</a>
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
            <li><a href="projects.html"><i class="fas fa-folder"></i> Projects</a></li>
            <li><a href="community.html"><i class="fas fa-users"></i> Community</a></li>
            <li><a href="about.html"><i class="fas fa-circle-info"></i> About</a></li>
        </ul>

        <div class="upgrade-box">
            <h4>Upgrade to Pro</h4>
            <p>Unlock unlimited chat, advanced tools & more.</p>
            <button>Upgrade Now</button>
        </div>

        <div class="user-profile">
            <i class="fas fa-circle-user"></i>
            <div>
                <p>Harsh Sharma</p>
                <small>Free Plan</small>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <div class="welcome-badge"><i class="fas fa-hand"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></div>
        <h1>Hello, <span class="highlight"><?php echo htmlspecialchars($_SESSION['username']); ?></span></h1>
        <p class="subtitle">How can I help you today?</p>

        <div class="chat-card">
            <div class="chat-header">
                <span><i class="fas fa-microchip"></i> Techla AI</span>
                <div class="chat-header-actions">
                    <button><i class="fas fa-volume-high"></i></button>
                    <button><i class="fas fa-rotate-right"></i></button>
                    <button><i class="fas fa-ellipsis-vertical"></i></button>
                </div>
            </div>

            <div class="chat-messages" id="chatMessages">
                <div class="message ai-message">
                    <div class="avatar"><i class="fas fa-microchip"></i></div>
                    <div class="message-text">
                        Hi Harsh! I'm Techla AI. Ask me anything or explore topics you're interested in.
                    </div>
                </div>
            </div>

            <div class="suggestions">
                <button class="suggestion-btn"><i class="fas fa-code"></i> Explain React in simple terms</button>
                <button class="suggestion-btn"><i class="fas fa-palette"></i> Best practices for CSS?</button>
                <button class="suggestion-btn"><i class="fas fa-rocket"></i> Help me plan a project</button>
                <button class="suggestion-btn"><i class="fas fa-lightbulb"></i> Tell me a fun fact!</button>
            </div>

            <div class="chat-input-area">
                <input type="text" id="chatInput" placeholder="Ask anything...">
                <button id="sendBtn"><i class="fas fa-paper-plane"></i></button>
            </div>

            <p class="disclaimer">Techla AI can make mistakes. Consider checking important information.</p>
        </div>
    </main>

    <aside class="right-panel">
        <div class="panel-card">
            <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
            <div class="quick-actions-grid">
                <a href="ai-chat.html" class="action-item">
                    <i class="fas fa-comment-dots"></i>
                    <p>AI Chat</p>
                    <small>Talk with AI</small>
                </a>
                <a href="tools.html" class="action-item">
                    <i class="fas fa-wrench"></i>
                    <p>Code Tools</p>
                    <small>Use Utilities</small>
                </a>
                <a href="learn.html" class="action-item">
                    <i class="fas fa-compass"></i>
                    <p>Learning Path</p>
                    <small>Start Learning</small>
                </a>
                <a href="projects.html" class="action-item">
                    <i class="fas fa-folder-open"></i>
                    <p>My Projects</p>
                    <small>View Projects</small>
                </a>
            </div>
        </div>

        <div class="panel-card">
            <h3><i class="fas fa-fire"></i> Popular Topics</h3>
            <ul class="topics-list">
                <li>Web Development <i class="fas fa-chevron-right"></i></li>
                <li>Artificial Intelligence <i class="fas fa-chevron-right"></i></li>
                <li>UI/UX Design <i class="fas fa-chevron-right"></i></li>
                <li>Machine Learning <i class="fas fa-chevron-right"></i></li>
                <li>Frontend Frameworks <i class="fas fa-chevron-right"></i></li>
            </ul>
        </div>

        <div class="panel-card">
            <h3><i class="fas fa-quote-left"></i> Daily Quote</h3>
            <blockquote>
                "The best way to predict the future is to build it."
                <footer>— Peter Drucker</footer>
            </blockquote>
        </div>
    </aside>

</div><!-- closes page-wrapper -->
<script src="js/chat.js"></script>
</body>
</html>