<?php
require_once 'db.php';
if (!isset($_SESSION['staff_id'])) {
    header("Location: stafflogin.php");
    exit();
}

$success = '';

// Handle Post Approval
if (isset($_GET['approve_post'])) {
    $id = (int)$_GET['approve_post'];
    mysqli_query($conn, "UPDATE birds SET is_approved = 1 WHERE id=$id");
    $success = "Observation record approved and published.";
}

// Handle Delete User
if (isset($_GET['delete_user'])) {
    $id = (int)$_GET['delete_user'];
    mysqli_query($conn, "DELETE FROM users WHERE id=$id");
    $success = "User removed from system.";
}

// Handle Delete Post
if (isset($_GET['delete_post'])) {
    $id = (int)$_GET['delete_post'];
    mysqli_query($conn, "DELETE FROM birds WHERE id=$id");
    $success = "Observation record deleted.";
}

// Stats for insight processing
$users_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$total_birds = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM birds"))['count'];
$pending_birds = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM birds WHERE is_approved = 0"))['count'];
$approved_birds = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM birds WHERE is_approved = 1"))['count'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTO Staff Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="admin-body">

<div class="admin-nav">
    <div style="font-family: var(--font-heading); font-size: 1.5rem;">Centrala Trust <span style="color:var(--primary-color);">Staff</span></div>
    <div>
        <span style="color: #94a3b8;">Logged as:</span> <strong><?= htmlspecialchars($_SESSION['staff_username']) ?></strong>
        <a href="index.php">Public Site</a>
        <a href="logout.php">Sign Out</a>
    </div>
</div>

<div class="container dashboard-grid">
    <div class="sidebar">
        <h3>Command Center</h3>
        <div class="sidebar-links">
            <a href="#" class="active" onclick="showPanel('stats', this)">📊 System Health</a>
            <a href="#" onclick="showPanel('data', this)">🦅 Moderation Queue</a>
            <a href="#" onclick="showPanel('users', this)">👥 Member Registry</a>
        </div>
    </div>
    
    <div class="content">
        <?php if($success): ?>
            <div class="alert alert-success" style="border-radius:12px; margin-bottom:30px;"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Stats Panel -->
        <div id="stats" class="panel active">
            <h2 style="font-family:var(--font-heading); margin-bottom:10px;">Network Overview</h2>
            <p style="margin-bottom:30px; color:#64748b;">Real-time metrics for the Centrala Trust for Ornithology ecosystem.</p>
            <div style="display:flex; gap:20px;">
                <div class="stat-card">
                    <h3><?= $total_birds ?></h3>
                    <p>Total Posts</p>
                </div>
                <div class="stat-card" style="border-top: 4px solid #f59e0b;">
                    <h3 style="color: #d97706;"><?= $pending_birds ?></h3>
                    <p>Pending Approval</p>
                </div>
                <div class="stat-card">
                    <h3><?= $users_count ?></h3>
                    <p>Active Members</p>
                </div>
            </div>
            
            <div style="margin-top: 40px; padding: 30px; background: #eff6ff; border-radius: 12px; border: 1px solid #bfdbfe;">
                <h4 style="color: #1e40af; margin-bottom: 10px;">System Status: Operational</h4>
                <p style="color: #3b82f6; font-size: 0.9rem;">All data synchronization services with the Centrala Environmental Agency are active.</p>
            </div>
        </div>

        <!-- Bird Data Panel for Moderation -->
        <div id="data" class="panel">
            <h2 style="font-family:var(--font-heading); margin-bottom:10px;">Moderation Queue</h2>
            <p style="margin-bottom:20px; color:#64748b;">Review and approve new citizen observations before they go public.</p>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Observation</th>
                        <th>Details</th>
                        <th>Reporter</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $b_result = mysqli_query($conn, "SELECT * FROM birds ORDER BY is_approved ASC, created_at DESC");
                    while($b = mysqli_fetch_assoc($b_result)):
                    ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($b['bird_species']) ?></strong><br>
                            <small style="color:#64748b;"><?= htmlspecialchars($b['location']) ?></small>
                        </td>
                        <td>
                            <small><?= htmlspecialchars($b['obs_date']) ?> @ <?= htmlspecialchars($b['obs_time']) ?></small><br>
                            <small><?= ucfirst(htmlspecialchars($b['activity'])) ?> (<?= $b['duration'] ?>m)</small>
                        </td>
                        <td><?= htmlspecialchars($b['username']) ?></td>
                        <td>
                            <?php if($b['is_approved']): ?>
                                <span class="badge badge-approved">Published</span>
                            <?php else: ?>
                                <span class="badge badge-pending">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if(!$b['is_approved']): ?>
                                <a href="staffdashboard.php?approve_post=<?= $b['id'] ?>" class="btn-action btn-approve">Approve</a>
                            <?php endif; ?>
                            <a href="staffdashboard.php?delete_post=<?= $b['id'] ?>" class="btn-action btn-wipe" onclick="return confirm('Delete this record permanently?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Users Panel -->
        <div id="users" class="panel">
            <h2 style="font-family:var(--font-heading); margin-bottom:10px;">Member Registry</h2>
            <p style="margin-bottom:20px; color:#64748b;">Manage access for citizen scientists and community members.</p>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Member Since</th>
                        <th>Access Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $u_result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
                    while($u = mysqli_fetch_assoc($u_result)):
                    ?>
                    <tr>
                        <td>#<?= $u['id'] ?></td>
                        <td style="font-weight:700;"><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= date('M d, Y', strtotime($u['reg_date'])) ?></td>
                        <td>
                            <a href="staffdashboard.php?delete_user=<?= $u['id'] ?>" class="btn-action btn-wipe" onclick="return confirm('Revoke member access permanently?')">Ban Member</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
function showPanel(id, el) {
    document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.sidebar-links a').forEach(a => a.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    el.classList.add('active');
}
</script>

<footer>
    <div class="footer-bottom">
        &copy; 2026 Centrala Trust for Ornithology. | Made by Hafiz Hassaan
    </div>
</footer>

</body>
</html>
