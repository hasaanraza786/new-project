<?php
require_once 'db.php';
// Truncate table and reset auto increment
mysqli_query($conn, "TRUNCATE TABLE birds");
echo "Table truncated.";
?>
