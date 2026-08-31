<?php
$seed = __DIR__ . '/storage/content.sqlite.seed';
$db = __DIR__ . '/storage/content.sqlite';

if (file_exists($seed)) {
    if (copy($seed, $db)) {
        echo "Database content successfully deployed to test environment! ✅<br>";
        echo "<a href='/'>Ga naar de website</a> of <a href='/admin/login.php'>Login</a>.";
    } else {
        echo "Failed to copy seed database. Check permissions.";
    }
} else {
    echo "No seed file found.";
}
