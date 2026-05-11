<?php
require_once 'db.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;
$success = '';

// Handle Delete only if logged in
if ($is_logged_in && isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM birds WHERE id=$id AND user_id=$user_id");
    if(mysqli_affected_rows($conn) > 0) {
        $success = "Post deleted successfully.";
    }
}

$result = $is_logged_in ? mysqli_query($conn, "SELECT * FROM birds WHERE user_id=$user_id ORDER BY id DESC") : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts - CTO</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="top-nav">
    <div class="nav-brand">
        CTO Message Board
    </div>
    <div class="nav-links">
        <a href="index.php" class="nav-item">Home</a>
        <a href="gallery.php" class="nav-item">Sighting Gallery</a>
        <a href="stafflogin.php" class="nav-item" style="color:var(--primary-color); font-weight:bold;">Staff Portal</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="myposts.php" class="nav-item active">My Posts</a>
            <a href="logout.php" class="nav-btn-outline">Sign Out</a>
            <a href="addpost.php" class="nav-btn-solid">Add Post</a>
        <?php else: ?>
            <a href="login.php" class="nav-btn-outline">Sign In</a>
            <a href="register.php" class="nav-btn-solid">Register</a>
        <?php endif; ?>
    </div>
</nav>

<div class="page-header">
    <h1>Your Observation Dashboard</h1>
    <p>Manage and review your personal contributions to the CTO records.</p>
</div>

<div class="container" style="margin-top: -30px; position: relative; z-index: 10; padding-bottom: 80px;">
    
    <?php if(!$is_logged_in): ?>
        <div class="login-prompt">
            <div style="font-size: 4rem; margin-bottom: 20px;">🔒</div>
            <h2>Access Restricted</h2>
            <p>Please sign in to your account to view and manage your bird sighting posts.</p>
            <div style="display: flex; gap: 15px; justify-content: center;">
                <a href="login.php" class="nav-btn-solid" style="padding: 12px 40px; font-size: 1.1rem;">Login Now</a>
                <a href="register.php" class="nav-btn-outline" style="padding: 12px 40px; font-size: 1.1rem;">Create Account</a>
            </div>
        </div>
    <?php else: ?>
        
        <?php if($success): ?>
            <div class="alert alert-success" style="margin-bottom: 20px;"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="post-cta">
            <div class="post-cta-text">
                <h2>Ready to share more?</h2>
                <p>Add a new observation to the community gallery today.</p>
            </div>
            <a href="addpost.php" class="nav-btn-solid" style="padding: 15px 30px; font-size: 1.1rem; box-shadow: 0 4px 12px rgba(254, 215, 0, 0.3);">Post Your Post</a>
        </div>

        <h2 style="font-family: var(--font-heading); font-size: 2rem; margin-bottom: 30px; color: #1e293b;">Your Recent Records</h2>

        <?php if(mysqli_num_rows($result) == 0): ?>
            <div class="empty-state">
                <p style="font-size: 1.2rem; color: #64748b; margin-bottom: 20px;">You haven't posted any observations yet.</p>
                <a href="addpost.php" class="nav-btn-solid">Share Your First Sighting</a>
            </div>
        <?php else: ?>
            <div class="species-grid">
                <?php while($bird = mysqli_fetch_assoc($result)): ?>
                <div class="species-card">
                    <div class="species-card-img">
                        <?php if($bird['image_path']): ?>
                            <img src="<?= htmlspecialchars($bird['image_path']) ?>" alt="Bird sighting">
                        <?php else: ?>
                            <div style="width:100%; height:100%; background: linear-gradient(135deg, #e2e8f0, #cbd5e1); display:flex; align-items:center; justify-content:center; color:#64748b; font-weight:600;">No Image Provided</div>
                        <?php endif; ?>
                    </div>
                    <div class="species-card-body">
                        <h3><?= htmlspecialchars($bird['bird_species']) ?></h3>
                        <p class="author-tag">Observed on <?= htmlspecialchars($bird['obs_date']) ?></p>
                        
                        <ul style="margin-top: 15px;">
                            <li><span>Location:</span> <strong><?= htmlspecialchars($bird['location']) ?></strong></li>
                            <li><span>Activity:</span> <strong><?= ucfirst(htmlspecialchars($bird['activity'])) ?></strong></li>
                        </ul>
                        
                        <div class="action-links" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #f1f5f9; display: flex; gap: 20px;">
                            <a href="viewpost.php?id=<?= $bird['id'] ?>">View Details</a>
                            <a href="editpost.php?id=<?= $bird['id'] ?>">Edit</a>
                            <a href="myposts.php?delete=<?= $bird['id'] ?>" class="del" onclick="return confirm('Delete this post permanently?')">Delete</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<footer>
    <div class="footer-bottom">
        &copy; 2026 Centrala Trust for Ornithology. | Made by Hafiz Hassaan
    </div>
</footer>

</body>
</html>

