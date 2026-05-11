<?php
require_once 'db.php';

if (!isset($_GET['id'])) {
    header("Location: gallery.php");
    exit();
}

$post_id = (int)$_GET['id'];
$query = "SELECT * FROM birds WHERE id = $post_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: gallery.php");
    exit();
}

$bird = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($bird['bird_species']) ?> - CTO Sighting</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="viewpost-body">

<nav class="top-nav">
    <div class="nav-brand">
        CTO Message Board
    </div>
    <div class="nav-links">
        <a href="index.php" class="nav-item">Home</a>
        <a href="gallery.php" class="nav-item active">Sighting Gallery</a>
        <a href="stafflogin.php" class="nav-item" style="color:var(--primary-color); font-weight:bold;">Staff Portal</a>
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

<div class="viewpost-header">
    <?php if($bird['image_path']): ?>
        <img src="<?= htmlspecialchars($bird['image_path']) ?>" alt="Sighting of <?= htmlspecialchars($bird['bird_species']) ?>">
    <?php else: ?>
        <div style="position:absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(135deg, #1e293b, #0f172a); z-index:1;"></div>
    <?php endif; ?>
    
    <div class="viewpost-title-box">
        <h1><?= htmlspecialchars($bird['bird_species']) ?></h1>
        <p>Observed by <strong><?= htmlspecialchars($bird['username']) ?></strong> &bull; <?= date('F j, Y', strtotime($bird['created_at'])) ?></p>
    </div>
</div>

<div class="viewpost-container">
    <div class="viewpost-content">
        <div class="viewpost-main">
            <h2>Observation Notes</h2>
            <div class="viewpost-comments">
                "<?= nl2br(htmlspecialchars($bird['comments'])) ?>"
            </div>
            
            <div class="viewpost-actions">
                <a href="gallery.php" class="nav-btn-outline" style="border-color: var(--secondary-color); color: var(--secondary-color);">
                    &larr; Back to Gallery
                </a>
                
                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $bird['user_id']): ?>
                    <a href="editpost.php?id=<?= $bird['id'] ?>" class="nav-btn-solid">Edit This Post</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="viewpost-sidebar">
            <div class="sidebar-stat">
                <h4>Location</h4>
                <p><?= htmlspecialchars($bird['location']) ?> Region</p>
            </div>
            <div class="sidebar-stat">
                <h4>Date & Time</h4>
                <p><?= htmlspecialchars($bird['obs_date']) ?> at <?= htmlspecialchars($bird['obs_time']) ?></p>
            </div>
            <div class="sidebar-stat">
                <h4>Activity Observed</h4>
                <p><?= ucfirst(htmlspecialchars($bird['activity'])) ?></p>
            </div>
            <div class="sidebar-stat">
                <h4>Duration</h4>
                <p><?= htmlspecialchars($bird['duration']) ?> Minutes</p>
            </div>
        </div>
    </div>
</div>

<footer style="margin-top: 0;">
    <div class="footer-bottom">
        &copy; 2026 Centrala Trust for Ornithology. | Made by Hafiz Hassaan
    </div>
</footer>

</body>
</html>
