<?php
include 'config.php';

// Fetch available costumes
$stmt = $pdo->query('SELECT * FROM Costumes WHERE availability = 1');
$costumes = $stmt->fetchAll();

// Display costumes
foreach ($costumes as $costume) {
    echo "<h2>{$costume['name']}</h2>";
    echo "<p>{$costume['description']}</p>";
    echo "<p>Price: \${$costume['price']}</p>";
    echo "<img src='{$costume['image_url']}' alt='{$costume['name']}' />";
    echo "<hr />";
}
?>
