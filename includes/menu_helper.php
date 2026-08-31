<?php
/**
 * Menu Helper
 * 
 * Beheert het renderen van dynamische menu's (uit storage/menus.json).
 */

function get_menu_items($menu_slug) {
    $menus_file = __DIR__ . '/../storage/menus.json';
    if (!file_exists($menus_file)) {
        return [];
    }
    
    $menus = json_decode(file_get_contents($menus_file), true);
    return $menus[$menu_slug] ?? [];
}

/**
 * Rendert een menu in HTML.
 *
 * @param string $menu_slug (e.g. 'main' of 'footer')
 * @param array $options (e.g. classnamen)
 */
function render_menu($menu_slug, $options = []) {
    $items = get_menu_items($menu_slug);
    
    if (empty($items)) {
        return "<!-- Menu '{$menu_slug}' is leeg of bestaat niet -->";
    }
    
    $ul_class = $options['ul_class'] ?? 'nav-list';
    $li_class = $options['li_class'] ?? 'nav-item';
    $a_class  = $options['a_class'] ?? 'nav-link';
    
    ob_start();
    ?>
    <ul class="<?= htmlspecialchars($ul_class) ?>">
        <?php foreach ($items as $item): ?>
            <li class="<?= htmlspecialchars($li_class) ?>">
                <a href="<?= htmlspecialchars($item['url']) ?>" 
                   class="<?= htmlspecialchars($a_class) ?>"
                   <?= !empty($item['target_blank']) ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                   <?= htmlspecialchars($item['label']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php
    return ob_get_clean();
}
