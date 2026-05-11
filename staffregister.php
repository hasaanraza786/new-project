<?php
require_once 'db.php';
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $check = mysqli_query($conn, "SELECT id FROM staff WHERE username='$username'");
        if (mysqli_num_rows($check) > 0) {
            $error = "A staff account with this username already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO staff (username, password) VALUES ('$username', '$hashed_password')";
            if (mysqli_query($conn, $query)) {
                $success = "Staff registration successful! You can now access the staff portal.";
            } else {
                $error = "Registration failed. Please try again.";
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
    <title>Staff Registration - CTO</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="staff-auth-body">

<div class="container" style="display:flex; justify-content:center; align-items:center; min-height:100vh;">
    <div class="form-container form-container-staff" style="width: 100%; max-width: 450px;">
        <h2>CTO Staff Registration</h2>
        <p style="text-align: center; margin-bottom: 20px;">Create a restricted Data Processing account</p>
        
        <?php if($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?> <a href="stafflogin.php">Sign In to Dashboard</a></div>
        <?php endif; ?>
        
        <form method="POST" action="staffregister.php">
            <div class="form-group">
                <label>Admin Username (No spaces)</label>
                <input type="text" name="username" pattern="[a-zA-Z0-9]+" title="Only letters and numbers" required>
            </div>
            <div class="form-group">
                <label>Admin Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="submit-btn" style="background:var(--text-dark);">Register Staff Account</button>
            <p style="text-align: center; margin-top: 15px;">
                <a href="stafflogin.php" class="nav-btn-outline" style="display:block; margin-bottom:15px; border-color:var(--text-dark); color:var(--text-dark);">Back to Staff Login</a>
                <a href="index.php" style="color:#666;">← Back to Public Website</a>
            </p>
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
