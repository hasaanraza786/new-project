<?php
require_once 'db.php';

$brain_dir = 'C:/Users/User/.gemini/antigravity/brain/7acd636f-dfdf-483c-91a6-c454d500eb22/';
$upload_dir = 'uploads/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Find the generated images
$images = [
    'Wood Pigeon' => glob($brain_dir . 'wood_pigeon_*.png')[0] ?? '',
    'House Sparrow' => glob($brain_dir . 'house_sparrow_*.png')[0] ?? '',
    'Starling' => glob($brain_dir . 'starling_*.png')[0] ?? '',
    'Blue Tit' => glob($brain_dir . 'blue_tit_*.png')[0] ?? '',
    'Blackbird' => glob($brain_dir . 'blackbird_*.png')[0] ?? '',
    'Robin' => glob($brain_dir . 'robin_*.png')[0] ?? '',
    'Goldfinch' => '', // Failed to generate
    'Magpie' => '' // Failed to generate
];

$user_id = 1; // Assuming user_id 1 exists, or we just insert it
$username = 'Admin';
$location = 'Erean';
$date = date('Y-m-d');
$time = date('H:i');
$activity = 'visit';
$duration = 10;
$comments = 'Beautiful specimen spotted today.';

foreach ($images as $species => $src_path) {
    $image_path = '';
    if ($src_path && file_exists($src_path)) {
        $filename = uniqid() . '.png';
        $dest_path = $upload_dir . $filename;
        if (copy($src_path, $dest_path)) {
            $image_path = 'uploads/' . $filename;
        }
    }
    
    $query = "INSERT INTO birds (user_id, username, location, obs_time, obs_date, bird_species, activity, duration, comments, image_path) 
              VALUES ('$user_id', '$username', '$location', '$time', '$date', '$species', '$activity', '$duration', '$comments', '$image_path')";
    
    mysqli_query($conn, $query);
    echo "Inserted $species\n";
}

echo "Done.";
?>
