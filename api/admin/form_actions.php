<?php
require_once __DIR__ . '/../../includes/auth_helper.php';
AuthEngine::require_login(); // Alleen admins

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'error' => 'Ongeldige input']);
    exit;
}

$forms_file = __DIR__ . '/../../storage/forms.json';

try {
    if ($data['action'] === 'save_forms') {
        if (!isset($data['forms']) || !is_array($data['forms'])) {
            throw new Exception("Geen geldige form data meegegeven.");
        }
        
        $json = json_encode($data['forms'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if (file_put_contents($forms_file, $json) === false) {
            throw new Exception("Kan forms.json niet wegschrijven. Check permissies.");
        }
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    throw new Exception("Onbekende actie: " . htmlspecialchars($data['action']));
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
