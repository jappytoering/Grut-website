<?php
// Dit script vangt het tonen van dynamische CMS pagina's op.
require_once __DIR__ . '/includes/content_helper.php';

$dbPath = __DIR__ . '/storage/content.sqlite';
if (!file_exists($dbPath)) {
    die("CMS Database not found.");
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Bepaal de huidige slug (zonder taal prefix)
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$locale = 'nl'; // fallback
if (preg_match('#^/(en|fy)(/.*)?$#', $uri, $matches)) {
    $locale = $matches[1];
    $slug = trim($matches[2] ?? '/', '/');
} else {
    $slug = trim($uri, '/');
}
if (empty($slug)) $slug = 'home'; // home is de standaard homepage slug

// Zoek pagina in de database
$stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ? AND status = 'published'");
$stmt->execute([$slug]);
$page = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$page) {
    // Pagina niet gevonden in database.
    // Geef 404 of val terug naar router's default gedrag.
    http_response_code(404);
    echo "<h1>404 Pagina niet gevonden</h1>";
    exit;
}

// Haal blokken op
$stmt = $pdo->prepare("SELECT * FROM page_blocks WHERE page_id = ? ORDER BY sort_order ASC");
$stmt->execute([$page['id']]);
$blocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($locale) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page['seo_title'] ?: 'Grut Designers') ?></title>
    <meta name="description" content="<?= htmlspecialchars($page['meta_description'] ?? '') ?>">
    <!-- Voeg CSS en JS toe -->
</head>
<body>

<?php
// Render elk blok via zijn bijbehorende component template
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
?>

</body>
</html>
