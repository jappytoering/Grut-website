<?php
/**
 * API: Upload Media (AJAX of Form)
 */
require_once __DIR__ . '/../../includes/auth_helper.php';
require_once __DIR__ . '/../../includes/media_helper.php';

if (!AuthEngine::is_logged_in()) {
    http_response_code(401);
    die(json_encode(['error' => 'Niet ingelogd']));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['file'])) {
    http_response_code(400);
    die(json_encode(['error' => 'Geen bestand geüpload']));
}

$file = $_FILES['file'];
$alt_text = $_POST['alt_text'] ?? '';

// Check errors
if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    die(json_encode(['error' => 'Upload fout: ' . $file['error']]));
}

try {
    $asset_id = MediaLibrary::store_upload($file, $alt_text);
    if ($asset_id) {
        $asset = MediaLibrary::get_asset($asset_id);
        echo json_encode(['success' => true, 'asset' => $asset]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Fout bij opslaan bestand (MediaLibrary)']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Fout: ' . $e->getMessage()]);
}
