<?php
/**
 * CLI Tool: Asset Verwerking & Optimalisatie
 * 
 * Dit script leest de /storage/media/originals/ map uit,
 * converteert de bestanden naar .webp, genereert de variants,
 * en registreert ze in de content database (catalogus).
 */

echo "🚀 Media Optimalisatie Script gestart...\n\n";

$originalsDir = __DIR__ . '/../storage/media/originals/';
$optimizedDir = __DIR__ . '/../storage/media/optimized/';
$dbPath = __DIR__ . '/../storage/content.sqlite';

if (!is_dir($originalsDir) || !is_dir($optimizedDir)) {
    die("❌ Fout: De media mappen ontbreken. Start init-db.php eerst of maak ze aan.\n");
}

if (!extension_loaded('gd')) {
    die("❌ Fout: PHP GD extensie is niet geladen. Kan geen afbeeldingen bewerken.\n");
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("❌ Fout: Kan geen verbinding maken met database: " . $e->getMessage() . "\n");
}

// Config: Doel breedtes voor de variants
$breakpoints = [
    'thumb' => 400,
    'medium' => 800,
    'large' => 1600
];

$files = array_diff(scandir($originalsDir), ['.', '..', '.gitkeep']);
$processedCount = 0;

foreach ($files as $file) {
    $filePath = $originalsDir . $file;
    if (!is_file($filePath)) continue;
    
    // Alleen gangbare formaten
    $mime = mime_content_type($filePath);
    if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'])) {
        echo "⚠️ Overslaan (geen ondersteunde afb): $file\n";
        continue;
    }
    
    $asset_id = pathinfo($file, PATHINFO_FILENAME);
    // Kebab case force (basic)
    $asset_id = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $asset_id));
    
    echo "⚙️ Verwerken van: $file (als '$asset_id')\n";
    
    // Inladen bron
    switch ($mime) {
        case 'image/jpeg': $sourceImg = imagecreatefromjpeg($filePath); break;
        case 'image/png':  $sourceImg = imagecreatefrompng($filePath); break;
        case 'image/webp': $sourceImg = imagecreatefromwebp($filePath); break;
        default: continue 2;
    }
    
    if (!$sourceImg) {
        echo "❌ Fout bij inladen van $file\n";
        continue;
    }
    
    $origWidth = imagesx($sourceImg);
    $origHeight = imagesy($sourceImg);
    $variants_data = [];
    
    foreach ($breakpoints as $key => $targetWidth) {
        if ($origWidth < $targetWidth && $key !== 'thumb') {
            // Als de afbeelding al kleiner is dan het doel, skippen we de variant (behoudens thumb)
            continue;
        }
        
        $ratio = $targetWidth / $origWidth;
        $targetHeight = round($origHeight * $ratio);
        
        // Voor kleine afbeeldingen waar targetWidth > origWidth, we scale niet up
        if ($origWidth < $targetWidth) {
            $targetWidth = $origWidth;
            $targetHeight = $origHeight;
        }
        
        $destImg = imagecreatetruecolor($targetWidth, $targetHeight);
        
        // Transparantie behouden voor PNG/WebP
        imagealphablending($destImg, false);
        imagesavealpha($destImg, true);
        $transparent = imagecolorallocatealpha($destImg, 255, 255, 255, 127);
        imagefilledrectangle($destImg, 0, 0, $targetWidth, $targetHeight, $transparent);
        
        imagecopyresampled($destImg, $sourceImg, 0, 0, 0, 0, $targetWidth, $targetHeight, $origWidth, $origHeight);
        
        $variantFileName = "{$asset_id}-{$key}.webp";
        $variantPath = $optimizedDir . $variantFileName;
        
        // Opslaan als WebP (kwaliteit 80)
        imagewebp($destImg, $variantPath, 80);
        imagedestroy($destImg);
        
        $variants_data[$key] = [
            'path' => "storage/media/optimized/" . $variantFileName,
            'width' => $targetWidth,
            'height' => $targetHeight
        ];
    }
    
    imagedestroy($sourceImg);
    
    // Check if asset already exists in DB
    $stmt = $pdo->prepare("SELECT id FROM media_assets WHERE asset_id = :asset_id");
    $stmt->execute([':asset_id' => $asset_id]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        $update = $pdo->prepare("
            UPDATE media_assets 
            SET original_filename = :original_filename, width = :width, height = :height, variants_json = :variants_json, updated_at = CURRENT_TIMESTAMP
            WHERE asset_id = :asset_id
        ");
        $update->execute([
            ':asset_id' => $asset_id,
            ':original_filename' => $file,
            ':width' => $origWidth,
            ':height' => $origHeight,
            ':variants_json' => json_encode($variants_data, JSON_UNESCAPED_SLASHES)
        ]);
        echo "✅ Geüpdatet in catalogus.\n";
    } else {
        $insert = $pdo->prepare("
            INSERT INTO media_assets (asset_id, original_filename, title, alt_text, width, height, tags, variants_json)
            VALUES (:asset_id, :original_filename, :title, :alt_text, :width, :height, :tags, :variants_json)
        ");
        
        $title = ucfirst(str_replace('-', ' ', $asset_id));
        
        $insert->execute([
            ':asset_id' => $asset_id,
            ':original_filename' => $file,
            ':title' => $title,
            ':alt_text' => "Afbeelding van " . strtolower($title),
            ':width' => $origWidth,
            ':height' => $origHeight,
            ':tags' => 'upload',
            ':variants_json' => json_encode($variants_data, JSON_UNESCAPED_SLASHES)
        ]);
        echo "✅ Toegevoegd aan catalogus.\n";
    }
    
    $processedCount++;
}

echo "\n🎉 Klaar! $processedCount bestanden verwerkt en geoptimaliseerd.\n";
