<?php
/**
 * API: Save Content Translation (AJAX)
 */
require_once __DIR__ . '/../../includes/auth_helper.php';
require_once __DIR__ . '/../../includes/content_helper.php';

// Controleer of gebruiker is ingelogd
if (!AuthEngine::is_logged_in()) {
    http_response_code(401);
    die(json_encode(['error' => 'Niet ingelogd']));
}

$input = json_decode(file_get_contents('php://input'), true);

$key_name = $input['key_name'] ?? '';
$locale = $input['locale'] ?? '';
$value = $input['value'] ?? '';

if (empty($key_name) || empty($locale)) {
    http_response_code(400);
    die(json_encode(['error' => 'Ongeldige input']));
}

$dbPath = __DIR__ . '/../../storage/content.sqlite';
try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Zoek key_id
    $stmt = $pdo->prepare("SELECT id FROM content_keys WHERE key_name = ?");
    $stmt->execute([$key_name]);
    $key_id = $stmt->fetchColumn();

    if (!$key_id) {
        http_response_code(404);
        die(json_encode(['error' => 'Content key niet gevonden']));
    }

    // Check of vertaling al bestaat
    $stmtCheck = $pdo->prepare("SELECT id FROM content_translations WHERE key_id = ? AND locale = ?");
    $stmtCheck->execute([$key_id, $locale]);
    
    if ($stmtCheck->fetchColumn()) {
        // Update
        $stmtUpdate = $pdo->prepare("UPDATE content_translations SET value = ?, updated_at = CURRENT_TIMESTAMP WHERE key_id = ? AND locale = ?");
        $stmtUpdate->execute([$value, $key_id, $locale]);
    } else {
        // Insert
        $stmtInsert = $pdo->prepare("INSERT INTO content_translations (key_id, locale, value) VALUES (?, ?, ?)");
        $stmtInsert->execute([$key_id, $locale, $value]);
    }

    // Wis de ContentEngine cache!
    ContentEngine::clear_cache();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code(500);
    die(json_encode(['error' => 'Database fout: ' . $e->getMessage()]));
}
