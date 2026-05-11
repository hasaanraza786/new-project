<?php
require_once 'db.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$query = "SELECT * FROM birds WHERE is_approved = 1";
if ($search) {
    $query .= " AND (bird_species LIKE '%$search%' OR location LIKE '%$search%' OR comments LIKE '%$search%' OR username LIKE '%$search%')";
}
$query .= " ORDER BY id ASC";

$birds_result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Gallery - CTO</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

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

<div class="page-header">
    <div class="page-header-content">
        <h1>Observation Gallery</h1>
        <p>Explore recent bird sightings and photographic records from the Centrala region.</p>
        <form action="gallery.php" method="GET" class="search-container">
            <input type="text" name="search" placeholder="Search by species, region, or observer..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">SEARCH</button>
        </form>
    </div>
</div>

<div class="gallery-wrapper">
    <div class="container">
        <?php if(mysqli_num_rows($birds_result) == 0): ?>
            <div style="text-align:center; padding: 80px 20px; background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <h3 style="color: var(--secondary-color); font-family: var(--font-heading); font-size: 2rem; margin-bottom: 10px;">No sightings found</h3>
                <p style="color: var(--text-medium); font-size: 1.1rem;">Try adjusting your search keywords to discover more records.</p>
            </div>
        <?php else: ?>
            <div class="species-grid">
                <?php while($bird = mysqli_fetch_assoc($birds_result)): ?>
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
                        <p class="author-tag">Observed by <?= htmlspecialchars($bird['username']) ?></p>
                        
                        <ul>
                            <li><span>Location:</span> <strong><?= htmlspecialchars($bird['location']) ?></strong></li>
                            <li><span>Date:</span> <strong><?= htmlspecialchars($bird['obs_date']) ?></strong></li>
                            <li><span>Activity:</span> <strong><?= ucfirst(htmlspecialchars($bird['activity'])) ?></strong></li>
                        </ul>
                        
                        <a href="viewpost.php?id=<?= $bird['id'] ?>" class="view-btn">View Full Details</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer>
    <div class="footer-bottom">
        &copy; 2026 Centrala Trust for Ornithology. | Made by Hafiz Hassaan
    </div>
</footer>

</body>
</html>
