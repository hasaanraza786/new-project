<?php
require_once 'db.php';
// Delete any duplicates and keep only one of each species.
mysqli_query($conn, "DELETE FROM birds WHERE id > 8");
echo "Deleted duplicates.";
?>
