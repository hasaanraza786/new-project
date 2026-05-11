<?php
require_once 'db.php';
$result = mysqli_query($conn, "SELECT id, bird_species, username, image_path FROM birds ORDER BY created_at DESC");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['id'] . " | " . $row['bird_species'] . " | " . $row['username'] . " | " . $row['image_path'] . "\n";
}
?>
