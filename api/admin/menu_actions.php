<?php
require_once __DIR__ . '/../../includes/auth_helper.php';
require_once __DIR__ . '/../../includes/db_helper.php';

AuthEngine::require_login(); // Zorg dat alleen admins dit kunnen

// CSRF Check for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Ongeldig CSRF token']);
        exit;
    }
}


header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'error' => 'Ongeldige input']);
    exit;
}

try {
    if ($data['action'] === 'save_menus') {
        if (!isset($data['menus']) || !is_array($data['menus'])) {
            throw new Exception("Geen geldige menu data meegegeven.");
        }
        
        $pdo = get_db_connection();
        $pdo->beginTransaction();
        
        foreach ($data['menus'] as $menu_slug => $items) {
            // Check if menu exists, if not create it
            $stmt = $pdo->prepare("SELECT id FROM menus WHERE slug = ?");
            $stmt->execute([$menu_slug]);
            $menu_id = $stmt->fetchColumn();
            
            if (!$menu_id) {
                $stmtInsert = $pdo->prepare("INSERT INTO menus (slug, title) VALUES (?, ?)");
                $stmtInsert->execute([$menu_slug, ucfirst($menu_slug) . ' Menu']);
                $menu_id = $pdo->lastInsertId();
            }
            
            // Delete old items
            $stmtDelete = $pdo->prepare("DELETE FROM menu_items WHERE menu_id = ?");
            $stmtDelete->execute([$menu_id]);
            
            // Insert new items
            $stmtItem = $pdo->prepare("INSERT INTO menu_items (menu_id, label, url, target_blank, sort_order) VALUES (?, ?, ?, ?, ?)");
            $order = 0;
            foreach ($items as $item) {
                $label = $item['label'] ?? 'Link';
                $url = $item['url'] ?? '#';
                $target = !empty($item['target_blank']) ? 1 : 0;
                
                $stmtItem->execute([$menu_id, $label, $url, $target, $order]);
                $order += 10;
            }
        }
        
        $pdo->commit();
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    throw new Exception("Onbekende actie: " . htmlspecialchars($data['action']));
    
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
