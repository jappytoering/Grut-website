<?php
require_once __DIR__ . '/../../includes/auth_helper.php';
AuthEngine::require_login(); // Zorg dat alleen admins dit kunnen

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'error' => 'Ongeldige input']);
    exit;
}

$menus_file = __DIR__ . '/../../storage/menus.json';

try {
    if ($data['action'] === 'save_menus') {
        if (!isset($data['menus']) || !is_array($data['menus'])) {
            throw new Exception("Geen geldige menu data meegegeven.");
        }
        
        // Sla direct op
        $json = json_encode($data['menus'], JSON_PRETTY_PRINT);
        if (file_put_contents($menus_file, $json) === false) {
            throw new Exception("Kan menus.json niet wegschrijven. Check permissies.");
        }
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    throw new Exception("Onbekende actie: " . htmlspecialchars($data['action']));
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
