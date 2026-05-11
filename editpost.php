<?php
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header("Location: myposts.php");
    exit();
}

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['id'];

// Verify ownership
$check = mysqli_query($conn, "SELECT * FROM birds WHERE id=$post_id AND user_id=$user_id");
if (mysqli_num_rows($check) == 0) {
    header("Location: myposts.php");
    exit();
}
$bird = mysqli_fetch_assoc($check);

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
    
    $image_path = $bird['image_path']; // retain old by default
    
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
            $query = "UPDATE birds SET location='$location', obs_time='$obs_time', obs_date='$obs_date', bird_species='$bird_species', activity='$activity', duration='$duration', comments='$comments', image_path='$image_path' WHERE id=$post_id AND user_id=$user_id";
            if (mysqli_query($conn, $query)) {
                $success = "Post updated successfully!";
                $bird = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM birds WHERE id=$post_id")); // Reload data
            } else {
                $error = "Failed to update observation. Please try again.";
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
    <title>Edit Observation - CTO</title>
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
        <h2>Edit Observation</h2>
        <p style="text-align:center; margin-bottom:20px;">Correct errors in your observation report.</p>
        
        <?php if($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?> <a href="myposts.php">View My Posts</a></div>
        <?php endif; ?>
        
        <form method="POST" action="editpost.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $bird['id'] ?>">
            <div class="form-group">
                <label>Bird Species *</label>
                <select name="bird_species" required style="width:100%; padding:12px; border:1px solid #ccc; border-radius:4px;">
                    <?php foreach($birds as $b): ?>
                        <option value="<?= $b ?>" <?= $bird['bird_species']==$b ? 'selected' : '' ?>><?= $b ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Location (Centrala Region) *</label>
                <select name="location" required style="width:100%; padding:12px; border:1px solid #ccc; border-radius:4px;">
                    <?php foreach($locations as $l): ?>
                        <option value="<?= $l ?>" <?= $bird['location']==$l ? 'selected' : '' ?>><?= $l ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="display:flex; gap:15px;">
                <div class="form-group" style="flex:1;">
                    <label>Date of Observation *</label>
                    <input type="date" name="obs_date" value="<?= htmlspecialchars($bird['obs_date']) ?>" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Time of Observation *</label>
                    <input type="time" name="obs_time" value="<?= htmlspecialchars($bird['obs_time']) ?>" required>
                </div>
            </div>
            <div style="display:flex; gap:15px;">
                <div class="form-group" style="flex:1;">
                    <label>Primary Activity *</label>
                    <select name="activity" required style="width:100%; padding:12px; border:1px solid #ccc; border-radius:4px;">
                        <?php foreach($activities as $a): ?>
                            <option value="<?= $a ?>" <?= $bird['activity']==$a ? 'selected' : '' ?>><?= ucfirst($a) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Duration (Minutes) *</label>
                    <input type="number" name="duration" min="1" value="<?= htmlspecialchars($bird['duration']) ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Free Text Comments *</label>
                <textarea name="comments" rows="4" required><?= htmlspecialchars($bird['comments']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Photographic Image (Upload new to replace) (Max 1.2MB, JPG/PNG)</label>
                <input type="file" name="image" accept=".jpg, .jpeg, .png">
            </div>
            <button type="submit" class="submit-btn" style="background:#55783A;">Update Observation</button>
            <p style="text-align:center; margin-top: 15px;"><a href="myposts.php" style="color:#666;">Cancel</a></p>
        </form>
    </div>
</div>

<footer>
    <div class="footer-bottom">
        &copy; 2026 Centrala Trust for Ornithology. | Made by Hafiz Hassaan
    </div>
</footer>

</body>
</html>
