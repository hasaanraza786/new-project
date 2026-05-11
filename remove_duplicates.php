<?php
require_once 'db.php';

// First, get the paths of images we are about to delete so we can remove them from disk
$query = "SELECT image_path FROM birds WHERE id > 8";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    if (!empty($row['image_path']) && file_exists($row['image_path'])) {
        unlink($row['image_path']);
        echo "Deleted file: " . $row['image_path'] . "\n";
    }
}

// Now delete the records
$delete_query = "DELETE FROM birds WHERE id > 8";
if (mysqli_query($conn, $delete_query)) {
    echo "Deleted duplicate records from database.\n";
} else {
    echo "Error deleting records: " . mysqli_error($conn) . "\n";
}

// Reset auto-increment if needed (optional, but good for clean state if they ever truncate again)
mysqli_query($conn, "ALTER TABLE birds AUTO_INCREMENT = 9");

echo "Cleanup complete.\n";
?>
