<?php
$dbPath = __DIR__ . '/storage/content.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);
$stmt = $pdo->query("SELECT slug FROM pages");
$slugs = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo "Slugs in DB:\n";
print_r($slugs);

$seedPath = __DIR__ . '/storage/content.sqlite.seed';
if (file_exists($seedPath)) {
    $pdo2 = new PDO('sqlite:' . $seedPath);
    $stmt2 = $pdo2->query("SELECT slug FROM pages");
    $slugs2 = $stmt2->fetchAll(PDO::FETCH_COLUMN);
    echo "\nSlugs in SEED:\n";
    print_r($slugs2);
}
