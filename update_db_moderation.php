<?php
require_once 'db.php';

// Add is_approved column to birds table if it doesn't exist
$query = "ALTER TABLE birds ADD COLUMN is_approved TINYINT(1) DEFAULT 0";
if (mysqli_query($conn, $query)) {
    echo "Added is_approved column to birds table.\n";
} else {
    echo "Column might already exist or error: " . mysqli_error($conn) . "\n";
}

// Optionally approve existing posts so they don't disappear from the gallery
mysqli_query($conn, "UPDATE birds SET is_approved = 1");
echo "Approved existing posts.\n";

echo "Database update complete.\n";
?>
