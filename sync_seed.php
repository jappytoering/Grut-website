<?php
$seed = __DIR__ . '/storage/content.sqlite.seed';
$db = __DIR__ . '/storage/content.sqlite';

if (file_exists($seed)) {
    if (copy($seed, $db)) {
        // Zorg ervoor dat de webserver (www-data) kan schrijven naar de DB én de map (voor SQLite journal files)
        chmod(__DIR__ . '/storage', 0777);
        chmod($db, 0666);
        
        echo "Database content successfully deployed to test environment! ✅<br>";
        echo "<a href='/'>Ga naar de website</a> of <a href='/admin/login.php'>Login</a>.";
    } else {
        echo "Failed to copy seed database. Check permissions.";
    }
} else {
    echo "No seed file found.";
}
