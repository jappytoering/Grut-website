<?php
require_once __DIR__ . '/../../includes/auth_helper.php';
require_once __DIR__ . '/../../includes/media_helper.php';

AuthEngine::require_login(); // Ensure only admins can do this
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'list') {
    $assets = get_all_assets();
    
    // Sort by id descending
    usort($assets, function($a, $b) {
        return $b['id'] <=> $a['id'];
    });
    
    echo json_encode(['success' => true, 'data' => $assets]);
    exit;
}

if ($action === 'update_tags') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    $asset_id = $data['asset_id'] ?? null;
    $tags = $data['tags'] ?? '';
    
    if ($asset_id) {
        $dbPath = __DIR__ . '/../../storage/content.sqlite';
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("UPDATE media_assets SET tags = ?, updated_at = CURRENT_TIMESTAMP WHERE asset_id = ?");
        $stmt->execute([$tags, $asset_id]);
        
        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Unknown action']);
