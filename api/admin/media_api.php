<?php

require_once __DIR__ . '/../../includes/db_helper.php';
require_once __DIR__ . '/../../includes/auth_helper.php';
require_once __DIR__ . '/../../includes/media_helper.php';

AuthEngine::require_login(); // Ensure only admins can do this

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
        $pdo = get_cms_connection();
$stmt = $pdo->prepare("UPDATE media_assets SET tags = ?, updated_at = CURRENT_TIMESTAMP WHERE asset_id = ?");
        $stmt->execute([$tags, $asset_id]);
        
        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Unknown action']);
