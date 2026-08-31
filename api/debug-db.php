<?php
/**
 * API Endpoint: Debug DB (Only for testing)
 */
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db_helper.php';
$config = require __DIR__ . '/../config/contact.php';

if (!$config['debug']) {
    http_response_code(403);
    echo json_encode(['error' => 'Debug mode is disabled.']);
    exit;
}

try {
    $pdo = get_db_connection();
    $stmt = $pdo->query("SELECT * FROM submissions ORDER BY id DESC LIMIT 1");
    $last_entry = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($last_entry ?: ['message' => 'Geen inzendingen gevonden.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
