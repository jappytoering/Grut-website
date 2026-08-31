<?php
/**
 * CLI Tool: Sync Content
 * Haal de laatste storage (database + media) op van de live omgeving.
 * Run via: php bin/sync_content.php pull
 */

$config = require __DIR__ . '/../includes/config.php';
$token = $config['sync_token'] ?? '';
$remoteUrl = 'https://test.grutdesigners.nl/api/sync.php'; // In de toekomst de echte live URL

if (!isset($argv[1]) || $argv[1] !== 'pull') {
    echo "Gebruik: php bin/sync_content.php pull\n";
    exit(1);
}

echo "⬇️  Synchroniseren vanaf $remoteUrl ...\n";

$tempZip = sys_get_temp_dir() . '/grut_download_' . time() . '.zip';

$ch = curl_init($remoteUrl . '?token=' . urlencode($token));
$fp = fopen($tempZip, 'w+');

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
// Voor lokale test: ignore SSL errors eventueel
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_exec($ch);

if (curl_errno($ch)) {
    echo "❌ Fout tijdens download: " . curl_error($ch) . "\n";
    fclose($fp);
    unlink($tempZip);
    exit(1);
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
fclose($fp);

if ($httpCode !== 200) {
    echo "❌ Server weigerde de verbinding (HTTP $httpCode).\n";
    echo "Controleer of het SYNC_TOKEN klopt.\n";
    unlink($tempZip);
    exit(1);
}

echo "✅ Download geslaagd. Zip uitpakken...\n";

$zip = new ZipArchive();
if ($zip->open($tempZip) === true) {
    $storageDir = __DIR__ . '/../storage';
    $zip->extractTo($storageDir);
    $zip->close();
    echo "✅ Storage (database & media) succesvol geüpdatet!\n";
} else {
    echo "❌ Kon gedownloade ZIP niet uitpakken.\n";
}

unlink($tempZip);
