<?php
$files = ['index.php', 'gallery.php', 'addpost.php', 'myposts.php', 'editpost.php', 'login.php', 'register.php', 'staffdashboard.php', 'stafflogin.php', 'staffregister.php'];

$new_nav = <<<'HTML'
<!-- AUDUBON STYLE NAVBAR -->
<nav class="top-nav">
    <div class="nav-brand">
        CTO Message Board
    </div>
    <div class="nav-links">
        <a href="index.php" class="nav-item">Home</a>
        <a href="gallery.php" class="nav-item">Message Board</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="myposts.php" class="nav-item">My Posts</a>
            <a href="logout.php" class="nav-btn-outline">Sign Out</a>
            <a href="addpost.php" class="nav-btn-solid">Add Post</a>
        <?php else: ?>
            <a href="login.php" class="nav-btn-outline">Sign In</a>
            <a href="register.php" class="nav-btn-solid">Register</a>
        <?php endif; ?>
    </div>
</nav>
HTML;

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Replace the old nav. Search for everything between <nav class="top-nav"> ... </nav>
    $pattern = '/<nav class="top-nav">.*?<\/nav>/s';
    
    if (preg_match($pattern, $content)) {
        $content = preg_replace($pattern, $new_nav, $content);
        file_put_contents($file, $content);
        echo "Updated Nav in $file\n";
    } else {
        // Look for any nav tag
        $pattern_alt = '/<nav[^>]*>.*?<\/nav>/s';
        if (preg_match($pattern_alt, $content)) {
            $content = preg_replace($pattern_alt, $new_nav, $content);
            file_put_contents($file, $content);
            echo "Updated Nav in $file (alt)\n";
        }
    }
}
?>
