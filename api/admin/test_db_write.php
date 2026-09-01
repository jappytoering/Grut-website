<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$storage = __DIR__ . '/../../storage';
echo "Storage writable: " . (is_writable($storage) ? 'YES' : 'NO') . "<br>";
$dbFile = $storage . '/content.sqlite';
echo "DB writable: " . (is_writable($dbFile) ? 'YES' : 'NO') . "<br>";

try {
    require_once __DIR__ . '/../../includes/db_helper.php';
    $pdo = get_cms_connection();
    $stmt = $pdo->prepare("INSERT INTO pages (slug, status, template, seo_title) VALUES ('test-slug-123', 'draft', 'default', 'Test')");
    $stmt->execute();
    echo "DB Insert: SUCCESS<br>";
    
    $stmt = $pdo->prepare("DELETE FROM pages WHERE slug = 'test-slug-123'");
    $stmt->execute();
    echo "DB Delete: SUCCESS<br>";
} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage() . "<br>";
}
