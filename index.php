<?php
$uri = $_SERVER['REQUEST_URI'];
$slug = trim(urldecode(parse_url($uri, PHP_URL_PATH)), '/');

echo "REQUEST_URI: " . htmlspecialchars($uri) . "<br>";
echo "SLUG: " . htmlspecialchars($slug) . "<br>";

$dbPath = __DIR__ . '/storage/content.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);
$stmt = $pdo->prepare("SELECT slug FROM pages WHERE slug = ?");
$stmt->execute([$slug]);
$found = $stmt->fetch(PDO::FETCH_ASSOC);

echo "DB FOUND: " . ($found ? "YES" : "NO") . "<br>";

