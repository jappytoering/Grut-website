<?php
require_once __DIR__ . '/../includes/db_helper.php';

$pdo = get_cms_connection();
$originalsDir = __DIR__ . '/../storage/media/originals/';
if (!is_dir($originalsDir)) {
    mkdir($originalsDir, 0777, true);
}

function import_directory($dir) {
    global $pdo, $originalsDir;
    
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
    
    foreach ($iterator as $file) {
        if ($file->isDir()) continue;
        
        $filePath = $file->getPathname();
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) continue;
        
        $filename = $file->getFilename();
        
        $asset_id = substr(md5_file($filePath), 0, 12);
        $newFilename = $asset_id . '.' . $ext;
        
        if (copy($filePath, $originalsDir . $newFilename)) {
            $size = @getimagesize($originalsDir . $newFilename);
            $width = $size[0] ?? 0;
            $height = $size[1] ?? 0;
            
            $pdo->prepare("DELETE FROM media_assets WHERE asset_id = ?")->execute([$asset_id]);
            
            $stmt = $pdo->prepare("INSERT INTO media_assets (asset_id, original_filename, width, height, alt_text, created_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
            $stmt->execute([$asset_id, $filename, $width, $height, $filename]);
            
            echo "Imported $filename -> $asset_id\n";
        }
    }
}

import_directory(__DIR__ . '/../assets');
echo "Done.\n";
