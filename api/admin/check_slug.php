<?php
require_once __DIR__ . '/../../includes/auth_helper.php';
require_once __DIR__ . '/../../includes/db_helper.php';

AuthEngine::require_login();
header('Content-Type: application/json');

$slug = $_GET['slug'] ?? '';
$exclude_id = isset($_GET['exclude_id']) ? (int)$_GET['exclude_id'] : 0;

if (empty($slug)) {
    echo json_encode(['success' => true, 'exists' => false]);
    exit;
}

$reserved_routes = ['admin', 'api', 'assets', 'storage', 'contact']; // Voeg indien nodig toe

if (in_array(strtolower($slug), $reserved_routes)) {
    echo json_encode(['success' => true, 'exists' => true, 'message' => 'Deze URL is gereserveerd voor het systeem.']);
    exit;
}

try {
    $pdo = get_cms_connection();
    
    if ($exclude_id > 0) {
        $stmt = $pdo->prepare("SELECT id FROM pages WHERE slug = ? AND id != ?");
        $stmt->execute([$slug, $exclude_id]);
    } else {
        $stmt = $pdo->prepare("SELECT id FROM pages WHERE slug = ?");
        $stmt->execute([$slug]);
    }
    
    $exists = $stmt->fetch() !== false;
    
    echo json_encode(['success' => true, 'exists' => $exists, 'message' => $exists ? 'Deze URL is al in gebruik door een andere pagina.' : '']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
