<?php
require_once 'db.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM staff WHERE username='$username'");
    if (mysqli_num_rows($result) > 0) {
        $staff = mysqli_fetch_assoc($result);
        if (password_verify($password, $staff['password'])) {
            $_SESSION['staff_id'] = $staff['id'];
            $_SESSION['staff_username'] = $staff['username'];
            header("Location: staffdashboard.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No staff account found with that username.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTO Staff Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="staff-auth-body">

<div class="container" style="display:flex; justify-content:center; align-items:center; min-height:100vh;">
    <div class="form-container form-container-staff" style="width: 100%; max-width: 450px;">
        <h2>CTO Staff Login</h2>
        <p style="text-align: center; margin-bottom: 20px;">Restricted Area for Data Processing</p>
        
        <?php if($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="stafflogin.php">
            <div class="form-group">
                <label>Admin Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Admin Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="submit-btn" style="background:var(--text-dark);">Access Dashboard</button>
            <p style="text-align: center; margin-top: 15px;">
                <a href="staffregister.php" class="nav-btn-outline" style="display:block; margin-bottom:15px; border-color:var(--text-dark); color:var(--text-dark);">Create Staff Account</a>
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
