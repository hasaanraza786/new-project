<?php
require_once 'db.php';

// Get some stats
$users_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$birds_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM birds"))['count'];

// Get recent
$birds_result = mysqli_query($conn, "SELECT * FROM birds WHERE is_approved = 1 ORDER BY created_at DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centrala Trust for Ornithology</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- AUDUBON STYLE NAVBAR -->
<!-- AUDUBON STYLE NAVBAR -->
<nav class="top-nav">
    <div class="nav-brand">
        CTO Message Board
    </div>
    <div class="nav-links">
        <a href="index.php" class="nav-item">Home</a>
        <a href="gallery.php" class="nav-item">Sighting Gallery</a>
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

<!-- AUDUBON STYLE HERO -->
<section class="hero reveal" style="background: url('images/main_wallpaper.jpeg') center/cover no-repeat;">
    <div class="hero-content reveal stagger-1">
        <span class="hero-eyebrow">BIRDS NEED YOU</span>
        <h1>Build a Bright Future for Birds in Centrala</h1>
        <p>We can achieve new levels of impact for birds, people, and the planet thanks to support from caring people like you who log observations.</p>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="addpost.php" class="cta-button">Log an Observation</a>
        <?php else: ?>
            <a href="register.php" class="cta-button">Join Our Data Network</a>
        <?php endif; ?>
    </div>
</section>

<!-- USER GUIDE SECTION -->
<div class="container reveal">
    <div style="text-align:center; margin-bottom: 50px;">
        <span class="hero-eyebrow" style="color: var(--primary-color);">Community Access</span>
        <h2 class="section-title">Embark on Your Avian Journey</h2>
    </div>
    <div class="nature-boxes-grid" style="grid-template-columns: 1fr 1fr; gap: 40px;">
        <div class="nature-box reveal stagger-1" style="border-top-color: var(--accent-blue); padding: 50px;">
            <span class="icon" style="font-size: 4rem;">👤</span>
            <h3>Secure Membership Login</h3>
            <p>Step into the world of Centrala's Ornithology by registering your unique profile. Our secure portal allows you to connect with nature on a professional level, granting you exclusive access to our citizen science tools and data repository.</p>
        </div>
        <div class="nature-box reveal stagger-2" style="border-top-color: var(--accent-green); padding: 50px;">
            <span class="icon" style="font-size: 4rem;">📑</span>
            <h3>Post & Preserve Observations</h3>
            <p>Once authenticated, you possess the power to document life. Share your sightings with the world, upload high-definition captures, and maintain a private archive of your discoveries. You retain full authority to edit or remove your contributions at your discretion.</p>
        </div>
    </div>
</div>

<!-- BIRD BEAUTY & STATIC GALLERY (NO NAMES) -->
<div class="container reveal" style="margin-top: 100px;">
    <div style="text-align:center; margin-bottom: 60px;">
        <span class="hero-eyebrow" style="color: var(--primary-color);">Visual Splendor</span>
        <h2 class="section-title">The Masterpieces of the Sky</h2>
    </div>

    <div class="species-grid">
        <div class="species-card reveal stagger-1">
            <div class="species-card-img" style="height: 400px;">
                <img src="images/GOLDFINCH.jpg" alt="Bird" class="zoom-animate" onclick="this.classList.toggle('clicked')">
            </div>
        </div>
        <div class="species-card reveal stagger-2">
            <div class="species-card-img" style="height: 400px;">
                <img src="images/MAGPIE.jpg" alt="Bird" class="zoom-animate" onclick="this.classList.toggle('clicked')">
            </div>
        </div>
        <div class="species-card reveal stagger-3">
            <div class="species-card-img" style="height: 400px;">
                <img src="images/images (1).jpg" alt="Bird" class="zoom-animate" onclick="this.classList.toggle('clicked')">
            </div>
        </div>
        <div class="species-card reveal stagger-1">
            <div class="species-card-img" style="height: 400px;">
                <img src="images/images (2).jpg" alt="Bird" class="zoom-animate" onclick="this.classList.toggle('clicked')">
            </div>
        </div>
    </div>
</div>

<!-- FRAMED NATURE CONTENT -->
<div class="container reveal">
    <div class="framed-content reveal">
        <span class="feature-tag">The Soul of Nature</span>
        <h2>The Timeless Beauty of Our Avian Companions</h2>
        <p>
            Birds are the ultimate symbols of freedom and grace, their wings cutting through the silence of the dawn to bring a symphony of life to our urban landscapes. In Centrala, every chirp and every flutter of a wing is a reminder of the raw, unfiltered beauty that survives and thrives alongside us.
        </p>
        <p style="margin-top: 30px;">
            To witness a bird in its natural habitat is to observe a masterpiece in motion—a delicate balance of power and fragility that has inspired poets and scientists for centuries. By protecting their environment, we are not just saving a species; we are preserving the very spirit of our planet.
        </p>
    </div>
</div>

<!-- ADDITIONAL NATURE INFO -->
<div class="container reveal" style="margin-top: 60px; margin-bottom: 100px;">
    <div class="nature-boxes-grid">
        <div class="nature-box reveal stagger-1">
            <span class="icon">✨</span>
            <h3>Feathered Elegance</h3>
            <p>Every feather is a miracle of engineering and color. The vibrant hues and intricate patterns we see today are the result of millions of years of evolution, designed to dazzle and protect.</p>
        </div>
        <div class="nature-box reveal stagger-2">
            <span class="icon">🌍</span>
            <h3>Environmental Guardians</h3>
            <p>As indicators of ecological health, birds are our most vital allies. Their well-being reflects the purity of our air, the health of our forests, and the overall vitality of the earth.</p>
        </div>
    </div>
</div>

<footer>
    <div class="footer-content reveal">
        <div class="footer-col">
            <h3>Centrala Trust for Ornithology</h3>
            <p>Assisting urban planning by capturing empirical data regarding urban and suburban bird populations.</p>
        </div>
        <div class="footer-col">
            <h3>Quick Links</h3>
            <a href="index.php">Home Dashboard</a>
            <a href="gallery.php">Sighting Gallery</a>
            <a href="register.php">Become a Member</a>
            <a href="stafflogin.php">CTO Staff Access</a>
            <a href="staffregister.php">CTO Staff Registration</a>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2026 Centrala Environmental Agency & CTO. | Made by Hafiz Hassaan
    </div>
</footer>

<script>
    // Scroll Reveal Animation
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>

</body>
</html>
