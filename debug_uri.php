<?php
$uri = $_SERVER['REQUEST_URI'];
$parsed = parse_url($uri, PHP_URL_PATH);
$urldecoded = urldecode($parsed);
$slug = trim($urldecoded, '/');

echo "REQUEST_URI: " . htmlspecialchars($uri) . "\n";
echo "Parsed: " . htmlspecialchars($parsed) . "\n";
echo "Urldecoded: " . htmlspecialchars($urldecoded) . "\n";
echo "Slug: " . htmlspecialchars($slug) . "\n";
