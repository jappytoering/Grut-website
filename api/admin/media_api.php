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

echo json_encode(['success' => false, 'error' => 'Unknown action']);
