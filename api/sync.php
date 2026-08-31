<?php
/**
 * API Endpoint: Storage Sync
 * Verwacht een geldige SYNC_TOKEN header of GET parameter om 
 * de content.sqlite en media veilig te downloaden.
 */

$config = require __DIR__ . '/../includes/config.php';
$expected_token = $config['sync_token'] ?? null;

// Controleer auth
$token = $_GET['token'] ?? $_SERVER['HTTP_X_SYNC_TOKEN'] ?? '';
if (empty($expected_token) || $token !== $expected_token) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

$storageDir = __DIR__ . '/../storage';
$zipFile = sys_get_temp_dir() . '/grut_sync_' . time() . '.zip';

// Controleer of de ZipArchive class bestaat
if (!class_exists('ZipArchive')) {
    http_response_code(500);
    die(json_encode(['error' => 'ZipArchive PHP module ontbreekt']));
}

$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    http_response_code(500);
    die(json_encode(['error' => 'Kan zip bestand niet aanmaken']));
}

// Helper functie om bestanden toe te voegen
$addFile = function($path, $localName) use ($zip) {
    if (file_exists($path)) {
        $zip->addFile($path, $localName);
    }
};

// Voeg content database toe
$addFile($storageDir . '/content.sqlite', 'content.sqlite');

// Optioneel: Voeg media toe (als er een media map is)
$mediaDir = $storageDir . '/media';
if (is_dir($mediaDir)) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($mediaDir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = 'media/' . substr($filePath, strlen($mediaDir) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
}

$zip->close();

// Stuur ZIP naar client
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=grut_storage_sync.zip');
header('Content-Length: ' . filesize($zipFile));
readfile($zipFile);

// Verwijder temp zip
unlink($zipFile);
