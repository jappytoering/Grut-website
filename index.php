<?php

require_once __DIR__ . '/includes/db_helper.php';
require_once __DIR__ . '/includes/content_helper.php';
// Dit script vangt het tonen van dynamische CMS pagina's op.
require_once __DIR__ . '/includes/auth_helper.php';
require_once __DIR__ . '/includes/form_helper.php';



// Bepaal de huidige slug (zonder taal prefix)
$raw_uri = $_SERVER['REDIRECT_URL'] ?? $_SERVER['REQUEST_URI'];
$uri = urldecode(parse_url($raw_uri, PHP_URL_PATH));
$locale = 'nl'; // fallback
if (preg_match('#^/(en|fy)(/.*)?$#', $uri, $matches)) {
    $locale = $matches[1];
    $slug = trim($matches[2] ?? '/', '/');
} else {
    $slug = trim($uri, '/');
}
if (empty($slug)) $slug = 'home'; // home is de standaard homepage slug

// Zoek pagina in de database (zowel published als draft)
$page = get_page_by_slug($slug);

if (!$page) {
    // Pagina niet gevonden in database.
    http_response_code(404);
    echo "<h1>404 Pagina niet gevonden</h1>";
    exit;
}

// Controleer draft status
if ($page['status'] === 'draft') {
    AuthEngine::require_login(); // Dit stuurt de gebruiker naar /admin/login.php als ze niet ingelogd zijn
}

// Laad de vaste template shell, die zal $page en render_page_blocks() gebruiken
require __DIR__ . '/templates/page-shell.php';
