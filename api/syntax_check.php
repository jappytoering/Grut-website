<?php
$files = [
    __DIR__ . '/prototype-sprint/index.php',
    __DIR__ . '/includes/form_helper.php',
    __DIR__ . '/includes/content_helper.php',
    __DIR__ . '/config/contact.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        // Try to evaluate file contents using token_get_all to check for basic errors? No, token_get_all doesn't catch syntax errors easily.
        // Actually, we can just run php -l using shell_exec if php is available on the server.
        $output = shell_exec("php -l " . escapeshellarg($file) . " 2>&1");
        echo "Syntax check for $file:\n$output\n\n";
    } else {
        echo "File not found: $file\n\n";
    }
}
