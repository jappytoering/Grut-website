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
    
    // Backwards compatibility for string options
    if (is_string($options)) {
        $options = ['a_class' => $options];
    }
    
    if (empty($items)) {
        return "<!-- Menu '{$menu_slug}' is leeg of bestaat niet -->";
    }
    
    $no_list = $options['no_list'] ?? false;
    $ul_class = $options['ul_class'] ?? 'nav-list';
    $li_class = $options['li_class'] ?? 'nav-item';
    $a_class  = $options['a_class'] ?? 'nav-link';
    
    ob_start();
    if (!$no_list): ?>
    <ul class="<?= htmlspecialchars($ul_class) ?>">
    <?php endif; ?>
        <?php foreach ($items as $item): ?>
            <?php if (!$no_list): ?>
            <li class="<?= htmlspecialchars($li_class) ?>">
            <?php endif; ?>
                <a href="<?= htmlspecialchars($item['url']) ?>" 
                   class="<?= htmlspecialchars($a_class) ?>"
                   <?= !empty($item['target_blank']) ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                   <?= htmlspecialchars($item['label']) ?>
                </a>
            <?php if (!$no_list): ?>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php if (!$no_list): ?>
    </ul>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}
