<?php
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$success = '';
$error = '';

$locations = ['Erean', 'Brunad', 'Bylyn', 'Docia', 'Marend', 'Pryn', 'Zord', 'Yaean', 'Frestin', 'Stonyam', 'Ryall', 'Ruril', 'Keivia', 'Tallan', 'Adohad', 'Obelyn', 'Holmer', 'Vertwall'];
$birds = ['Wood Pigeon', 'House Sparrow', 'Starling', 'Blue Tit', 'Blackbird', 'Robin', 'Goldfinch', 'Magpie', 'Other/Unknown'];
$activities = ['visit', 'feeding', 'nesting', 'Other'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $bird_species = mysqli_real_escape_string($conn, $_POST['bird_species']);
    $obs_date = mysqli_real_escape_string($conn, $_POST['obs_date']);
    $obs_time = mysqli_real_escape_string($conn, $_POST['obs_time']);
    $activity = mysqli_real_escape_string($conn, $_POST['activity']);
    $duration = (int)$_POST['duration'];
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);
    
    $image_path = '';
    
    // Handle File Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png'];
        $file_type = $_FILES['image']['type'];
        $file_size = $_FILES['image']['size'];
        
        if ($file_size > 1258291) { // 1.2 MB limit
            $error = "Image size exceeds 1.2 MB limit.";
        } elseif (!in_array($file_type, $allowed_types)) {
            $error = "Only PNG or JPG images are allowed.";
        } else {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = "uploads/" . uniqid() . "." . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $filename)) {
                $image_path = $filename;
            } else {
                $error = "Failed to upload image.";
            }
        }
    }
    
    if (empty($error)) {
        if (!in_array($location, $locations) || !in_array($bird_species, $birds) || !in_array($activity, $activities)) {
            $error = "Invalid selection detected.";
        } else {
            $query = "INSERT INTO birds (user_id, username, location, obs_time, obs_date, bird_species, activity, duration, comments, image_path) 
                      VALUES ('$user_id', '$username', '$location', '$obs_time', '$obs_date', '$bird_species', '$activity', '$duration', '$comments', '$image_path')";
            if (mysqli_query($conn, $query)) {
                $success = "Observation logged successfully! Your post is pending review by our staff and will be visible in the gallery once approved.";
            } else {
                $error = "Failed to record observation. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Sighting - CTO</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- AUDUBON STYLE NAVBAR -->
<!-- AUDUBON STYLE NAVBAR -->
<!-- AUDUBON STYLE NAVBAR -->
<nav class="top-nav">
    <div class="nav-brand">
        CTO Message Board
    </div>
    <div class="nav-links">
        <a href="index.php" class="nav-item">Home</a>
        <a href="gallery.php" class="nav-item">Sighting Gallery</a>
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

<div class="container">
    <div class="form-container" style="max-width: 600px;">
        <h2>Record Sighting</h2>
        <p style="text-align:center; margin-bottom:20px;">Contribute to the Centrala Trust for Ornithology.</p>
        
        <?php if($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?> <a href="myposts.php">View My Posts</a></div>
        <?php endif; ?>
        
        <form method="POST" action="addpost.php" enctype="multipart/form-data">
            <div class="form-group">
                <label>Bird Species *</label>
                <select name="bird_species" required style="width:100%; padding:12px; border:1px solid #ccc; border-radius:4px;">
                    <option value="">Select a bird...</option>
                    <?php foreach($birds as $b): ?>
                        <option value="<?= $b ?>"><?= $b ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Location (Centrala Region) *</label>
                <select name="location" required style="width:100%; padding:12px; border:1px solid #ccc; border-radius:4px;">
                    <option value="">Select location...</option>
                    <?php foreach($locations as $l): ?>
                        <option value="<?= $l ?>"><?= $l ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="display:flex; gap:15px;">
                <div class="form-group" style="flex:1;">
                    <label>Date of Observation *</label>
                    <input type="date" name="obs_date" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Time of Observation *</label>
                    <input type="time" name="obs_time" required>
                </div>
            </div>
            <div style="display:flex; gap:15px;">
                <div class="form-group" style="flex:1;">
                    <label>Primary Activity *</label>
                    <select name="activity" required style="width:100%; padding:12px; border:1px solid #ccc; border-radius:4px;">
                        <option value="">Select activity...</option>
                        <?php foreach($activities as $a): ?>
                            <option value="<?= $a ?>"><?= ucfirst($a) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Duration (Minutes) *</label>
                    <input type="number" name="duration" min="1" required>
                </div>
            </div>
            <div class="form-group">
                <label>Free Text Comments *</label>
                <textarea name="comments" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label>Photographic Image (Optional) (Max 1.2MB, JPG/PNG)</label>
                <input type="file" name="image" accept=".jpg, .jpeg, .png">
            </div>
            <button type="submit" class="submit-btn">Publish Observation</button>
        </form>
    </div>
</div>

<footer>
    <div class="footer-bottom">
        &copy; 2026 Centrala Trust for Ornithology. Built for CEA Environmental Planning. | Made by Hafiz Hassaan
    </div>
</footer>

</body>
</html>
