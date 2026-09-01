<?php
require_once __DIR__ . '/../includes/db_helper.php';

echo "Start menu migratie...<br>\n";

try {
    $pdo = get_db_connection();
    
    // 1. Create tables
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS menus (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            slug TEXT UNIQUE NOT NULL,
            title TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS menu_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            menu_id INTEGER NOT NULL,
            label TEXT NOT NULL,
            url TEXT NOT NULL,
            target_blank INTEGER DEFAULT 0,
            sort_order INTEGER DEFAULT 0,
            FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
        );
    ");
    echo "Tabellen 'menus' en 'menu_items' gecontroleerd/aangemaakt.<br>\n";

    // 2. Read JSON
    $jsonFile = __DIR__ . '/../storage/menus.json';
    if (!file_exists($jsonFile)) {
        die("JSON bestand niet gevonden. Migratie niet nodig of al gedaan.");
    }
    
    $jsonContent = file_get_contents($jsonFile);
    $menus = json_decode($jsonContent, true);
    
    if (!$menus) {
        die("Fout bij het parsen van JSON.");
    }

    // 3. Migrate
    $pdo->beginTransaction();
    
    // Clear existing to avoid duplicates during dev testing
    $pdo->exec("DELETE FROM menu_items");
    $pdo->exec("DELETE FROM menus");

    foreach ($menus as $menu_slug => $items) {
        // Insert menu
        $stmtMenu = $pdo->prepare("INSERT INTO menus (slug, title) VALUES (?, ?)");
        // Maken er een leesbare titel van, bv 'main' -> 'Main Menu'
        $title = ucfirst($menu_slug) . ' Menu';
        $stmtMenu->execute([$menu_slug, $title]);
        $menu_id = $pdo->lastInsertId();
        
        // Insert items
        $stmtItem = $pdo->prepare("INSERT INTO menu_items (menu_id, label, url, target_blank, sort_order) VALUES (?, ?, ?, ?, ?)");
        
        $order = 0;
        foreach ($items as $item) {
            $label = $item['label'] ?? 'Link';
            $url = $item['url'] ?? '#';
            $target = !empty($item['target_blank']) ? 1 : 0;
            
            $stmtItem->execute([$menu_id, $label, $url, $target, $order]);
            $order += 10;
        }
        echo "Menu '{$menu_slug}' gemigreerd met " . count($items) . " items.<br>\n";
    }
    
    $pdo->commit();
    echo "Migratie succesvol afgerond!<br>\n";

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Fout tijdens migratie: " . htmlspecialchars($e->getMessage());
}
