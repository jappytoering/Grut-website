<?php

require_once __DIR__ . '/db_helper.php';

/**
 * Translation stub functions for components
 */
if (!function_exists('t')) {
    function t(string $key, string $default = ''): string {
        return $default;
    }
}
if (!function_exists('t_html')) {
    function t_html(string $key, string $default = ''): string {
        return $default;
    }
}

/**
 * Haal een pagina op basis van slug op.
 */
function get_page_by_slug(string $slug): ?array {
    $pdo = get_cms_connection();
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ?");
    $stmt->execute([$slug]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);
    return $page ?: null;
}

/**
 * Haal alle blocks van een pagina op.
 */
function get_page_blocks(int $pageId): array {
    $pdo = get_cms_connection();
    $stmt = $pdo->prepare("SELECT * FROM page_blocks WHERE page_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$pageId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Render alle blocks voor een specifieke pagina
 */
function render_page_blocks(int $pageId): string {
    $blocks = get_page_blocks($pageId);
    
    ob_start();
    foreach ($blocks as $block) {
        $type = $block['block_type'];
        $content = json_decode($block['content_json'] ?? '{}', true);
        
        // Let op: pad is relatief ten opzichte van de root, omdat dit helper script in /includes zit
        $template_file = __DIR__ . "/../components/{$type}.php";
        
        if (file_exists($template_file)) {
            require $template_file;
        } else {
            // Fallback default component
            $template_file = __DIR__ . "/../components/default.php";
            if (file_exists($template_file)) {
                require $template_file;
            } else {
                echo "<!-- Component '{$type}' not found -->";
            }
        }
    }
    return ob_get_clean();
}
