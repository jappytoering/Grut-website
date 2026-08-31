<?php
$dir = __DIR__ . '/storage';
$db = $dir . '/content.sqlite';
$forms = $dir . '/forms.json';
echo "Dir perms: " . substr(sprintf('%o', fileperms($dir)), -4) . " Writable: " . (is_writable($dir) ? 'Yes' : 'No') . "\n";
if (file_exists($db)) {
    echo "DB perms: " . substr(sprintf('%o', fileperms($db)), -4) . " Writable: " . (is_writable($db) ? 'Yes' : 'No') . "\n";
} else {
    echo "DB does not exist\n";
}
if (file_exists($forms)) {
    echo "Forms perms: " . substr(sprintf('%o', fileperms($forms)), -4) . " Writable: " . (is_writable($forms) ? 'Yes' : 'No') . "\n";
} else {
    echo "Forms does not exist\n";
}
try {
    file_put_contents($forms, "test", FILE_APPEND);
    echo "Can write to forms\n";
} catch (Exception $e) {
    echo "Cannot write forms: " . $e->getMessage() . "\n";
}
try {
    $pdo = new PDO('sqlite:' . $db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("UPDATE pages SET updated_at = CURRENT_TIMESTAMP WHERE id = 1");
    echo "Can write to DB\n";
} catch (Exception $e) {
    echo "Cannot write DB: " . $e->getMessage() . "\n";
}
