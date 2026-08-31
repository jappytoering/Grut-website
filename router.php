<?php
/**
 * Lokale Ontwikkelomgeving Router (alleen voor php -S)
 * Zorgt ervoor dat URL's zoals /en/prototype-sprint correct worden gerouteerd
 * alsof mod_rewrite op Apache dit doet.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Check of de URI start met een taaltag: /en/ of /fy/
if (preg_match('#^/(en|fy)(/.*)?$#', $uri, $matches)) {
    // Strip de taaltag van de URI
    $rest = $matches[2] ?? '/';
    $internal_path = rtrim($rest, '/');
    if (empty($internal_path)) {
        $internal_path = '/index.php'; // fallback root
    }

    $file_path = __DIR__ . $internal_path;
    
    // Als het een map is (bijv /prototype-sprint), laad dan index.php
    if (is_dir($file_path)) {
        $file_path .= '/index.php';
    }

    if (file_exists($file_path) && is_file($file_path)) {
        // Wijzig SCRIPT_NAME etc. voor interne consistentie (optioneel, afhankelijk van hoe app werkt)
        $_SERVER['SCRIPT_NAME'] = str_replace(__DIR__, '', $file_path);
        
        $ext = pathinfo($file_path, PATHINFO_EXTENSION);
        if ($ext === 'php') {
            require $file_path;
            return true;
        } else {
            // Laat PHP-webserver andere bestanden serveren
            return false;
        }
    }
}

// Standaard afhandeling: bestand of folder index
$requested_file = __DIR__ . $uri;
if (is_dir($requested_file) && file_exists($requested_file . '/index.php')) {
    require $requested_file . '/index.php';
    return true;
}

// Laat PHP ingebouwde webserver de request afhandelen
return false;
