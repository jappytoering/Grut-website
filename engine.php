<?php
// Dit script vangt het tonen van dynamische CMS pagina's op.
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
require_once __DIR__ . '/includes/content_helper.php';
require_once __DIR__ . '/includes/auth_helper.php';

$dbPath = __DIR__ . '/storage/content.sqlite';
if (!file_exists($dbPath)) {
    die("CMS Database not found.");
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
$stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ?");
$stmt->execute([$slug]);
$page = $stmt->fetch(PDO::FETCH_ASSOC);

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

// Helper voor het renderen van de blokken binnen de shell
function render_page_blocks($page_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM page_blocks WHERE page_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$page_id]);
    $blocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    ob_start();
    foreach ($blocks as $block) {
        $type = $block['block_type'];
        $content = json_decode($block['content_json'] ?? '{}', true);
        $template_file = __DIR__ . "/components/{$type}.php";
        
        if (file_exists($template_file)) {
            require $template_file;
        } else {
            // Fallback default component
            $template_file = __DIR__ . "/components/default.php";
            if (file_exists($template_file)) {
                require $template_file;
            } else {
                echo "<!-- Component '{$type}' not found -->";
            }
        }
    }
    return ob_get_clean();
}

// Laad de vaste template shell, die zal $page en render_page_blocks() gebruiken
require __DIR__ . '/templates/page-shell.php';
} catch (\Throwable $e) {
    http_response_code(200);
    echo "<h1>FATAL ERROR CAUGHT:</h1>";
    echo "<pre>" . htmlspecialchars($e->getMessage() . "\n" . $e->getTraceAsString()) . "</pre>";
}
