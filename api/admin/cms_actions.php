<?php
require_once __DIR__ . '/../../includes/auth_helper.php';
require_once __DIR__ . '/../../includes/db_helper.php';

AuthEngine::require_login(); // Ensure only admins can do this
header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON input']);
    exit;
}

$dbPath = __DIR__ . '/../../storage/content.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$action = $data['action'];

try {
    if ($action === 'save_page') {
        $id = $data['id'] ?? null;
        $slug = $data['slug'] ?? '';
        $status = $data['status'] ?? 'draft';
        $seo_title = $data['seo_title'] ?? '';
        $meta_desc = $data['meta_description'] ?? '';
        
        if (empty($slug)) {
            throw new Exception("Slug mag niet leeg zijn.");
        }
        
        if (empty($id)) {
            $stmt = $pdo->prepare("INSERT INTO pages (slug, status, seo_title, meta_description, created_at) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");
            $stmt->execute([$slug, $status, $seo_title, $meta_desc]);
            $id = $pdo->lastInsertId();
        } else {
            $stmt = $pdo->prepare("UPDATE pages SET slug = ?, status = ?, seo_title = ?, meta_description = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$slug, $status, $seo_title, $meta_desc, $id]);
        }
        echo json_encode(['success' => true, 'id' => $id]);
        exit;
    }
    
    if ($action === 'add_block') {
        $page_id = $data['page_id'] ?? null;
        $block_type = $data['block_type'] ?? '';
        
        if (empty($page_id) || empty($block_type)) {
            throw new Exception("Page ID en Block Type zijn verplicht.");
        }
        
        // Find max sort_order
        $stmt = $pdo->prepare("SELECT MAX(sort_order) FROM page_blocks WHERE page_id = ?");
        $stmt->execute([$page_id]);
        $max_sort = (int) $stmt->fetchColumn();
        $new_sort = $max_sort + 1;
        
        $stmt = $pdo->prepare("INSERT INTO page_blocks (page_id, block_type, sort_order, content_json, created_at) VALUES (?, ?, ?, '{}', CURRENT_TIMESTAMP)");
        $stmt->execute([$page_id, $block_type, $new_sort]);
        
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        exit;
    }
    
    if ($action === 'update_block') {
        $id = $data['id'] ?? null;
        $content_json = $data['content_json'] ?? '{}';
        
        if (empty($id)) {
            throw new Exception("Block ID is verplicht.");
        }
        
        $stmt = $pdo->prepare("UPDATE page_blocks SET content_json = ? WHERE id = ?");
        $stmt->execute([$content_json, $id]);
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($action === 'delete_block') {
        $id = $data['id'] ?? null;
        if (empty($id)) throw new Exception("Block ID is verplicht.");
        $stmt = $pdo->prepare("DELETE FROM page_blocks WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'reorder_blocks') {
        $orders = $data['orders'] ?? [];
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("UPDATE page_blocks SET sort_order = ? WHERE id = ?");
        foreach ($orders as $order) {
            $stmt->execute([$order['sort_order'], $order['id']]);
        }
        $pdo->commit();
        echo json_encode(['success' => true]);
        exit;
    }
    
    throw new Exception("Onbekende actie: " . htmlspecialchars($action));
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
