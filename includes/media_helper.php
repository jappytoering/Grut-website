<?php

require_once __DIR__ . '/db_helper.php';
/**
 * Media Helper voor Responsive Images (Centrale Beeldbank)
 */

function render_image($asset_id, $options = []) {
    $alt = $options['alt'] ?? '';
    $class = $options['class'] ?? '';
    $loading = $options['loading'] ?? 'lazy';
    $decoding = $options['decoding'] ?? 'async';
    $sizes = $options['sizes'] ?? '(max-width: 768px) 100vw, 1200px';
    $fetchpriority = $options['fetchpriority'] ?? null;
    
    try {
        $pdo = get_cms_connection();
        
        $stmt = $pdo->prepare("SELECT * FROM media_assets WHERE asset_id = :asset_id");
        $stmt->execute([':asset_id' => $asset_id]);
        $asset = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($asset) {
            $width = $asset['width'];
            $height = $asset['height'];
            $alt = $alt ?: $asset['alt_text'];
            $variants = json_decode($asset['variants_json'], true) ?: [];
            
            $src = "/storage/media/originals/" . ($asset['original_filename'] ?? $asset_id);
            
            // Build source sets
            $webp_srcset = [];
            $fallback_srcset = [];
            
            foreach ($variants as $size => $variantData) {
                if (isset($variantData['path']) && isset($variantData['width'])) {
                    $path = "/" . $variantData['path'];
                    $fallback_srcset[] = "{$path} {$variantData['width']}w";
                    
                    // Here we assume standard webp conversion paths if we implement them,
                    // but since variants are stored in variants_json, let's just use what's there.
                    // If the pipeline outputs .webp, we can generate a webp source.
                    if (str_ends_with($path, '.webp')) {
                        $webp_srcset[] = "{$path} {$variantData['width']}w";
                    }
                }
            }
            
            $htmlAttr = [
                'src' => htmlspecialchars($src),
                'alt' => htmlspecialchars($alt)
            ];
            
            if ($class) $htmlAttr['class'] = htmlspecialchars($class);
            if ($width) $htmlAttr['width'] = htmlspecialchars($width);
            if ($height) $htmlAttr['height'] = htmlspecialchars($height);
            if ($loading !== 'eager') {
                $htmlAttr['loading'] = htmlspecialchars($loading);
                $htmlAttr['decoding'] = htmlspecialchars($decoding);
            }
            if ($fetchpriority) $htmlAttr['fetchpriority'] = htmlspecialchars($fetchpriority);
            
            $imgTag = "<img ";
            foreach ($htmlAttr as $k => $v) {
                $imgTag .= "{$k}=\"{$v}\" ";
            }
            $imgTag .= "/>";
            
            if (!empty($fallback_srcset)) {
                $fallback_srcsetString = implode(', ', $fallback_srcset);
                $picture = "<picture>";
                
                if (!empty($webp_srcset)) {
                    $webp_srcsetString = implode(', ', $webp_srcset);
                    $picture .= "<source type=\"image/webp\" srcset=\"" . htmlspecialchars($webp_srcsetString) . "\" sizes=\"" . htmlspecialchars($sizes) . "\">";
                } else {
                    // Als er geen expliciete WebP variants zijn, kan de server ze transparant serveren (b.v. via CDN/LiteSpeed), 
                    // maar we genereren dan gewoon de fallback bronnen:
                    $picture .= "<source srcset=\"" . htmlspecialchars($fallback_srcsetString) . "\" sizes=\"" . htmlspecialchars($sizes) . "\">";
                }
                
                $picture .= $imgTag;
                $picture .= "</picture>";
                return $picture;
            }
            
            return $imgTag;
            
        } else {
            $src = "/storage/media/originals/{$asset_id}";
            return "<img src=\"" . htmlspecialchars($src) . "\" alt=\"" . htmlspecialchars($alt) . "\" loading=\"lazy\" />";
        }
    } catch (Exception $e) {
        $src = "/storage/media/originals/{$asset_id}";
        return "<img src=\"" . htmlspecialchars($src) . "\" alt=\"" . htmlspecialchars($alt) . "\" loading=\"lazy\" />";
    }
}

/**
 * Verwijdert een asset inclusief fysieke bestanden (origineel + varianten)
 */
function delete_asset($asset_id) {
    $baseMediaDir = __DIR__ . '/../';
    
    try {
        $pdo = get_cms_connection();
// Haal asset op
        $stmt = $pdo->prepare("SELECT * FROM media_assets WHERE asset_id = :asset_id");
        $stmt->execute([':asset_id' => $asset_id]);
        $asset = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$asset) {
            return false;
        }
        
        // Verwijder origineel bestand
        if (!empty($asset['original_filename'])) {
            $originalPath = $baseMediaDir . 'storage/media/originals/' . $asset['original_filename'];
            if (file_exists($originalPath)) {
                unlink($originalPath);
            }
        }
        
        // Verwijder varianten
        $variants = json_decode($asset['variants_json'], true);
        if (is_array($variants)) {
            foreach ($variants as $variant) {
                if (isset($variant['path'])) {
                    $variantPath = $baseMediaDir . ltrim($variant['path'], '/');
                    if (file_exists($variantPath)) {
                        unlink($variantPath);
                    }
                }
            }
        }
        
        // Verwijder DB record
        $delStmt = $pdo->prepare("DELETE FROM media_assets WHERE asset_id = :asset_id");
        return $delStmt->execute([':asset_id' => $asset_id]);
        
    } catch (Exception $e) {
        error_log("Fout bij verwijderen asset: " . $e->getMessage());
        return false;
    }
}

function store_upload($file, $alt_text = '') {
    $originalsDir = __DIR__ . '/../storage/media/originals/';
    
    if (!is_dir($originalsDir)) {
        mkdir($originalsDir, 0777, true);
    }
    
    $asset_id = substr(md5_file($file['tmp_name']), 0, 12);
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $asset_id . '.' . $ext;
    
    if (move_uploaded_file($file['tmp_name'], $originalsDir . $filename)) {
        $size = getimagesize($originalsDir . $filename);
        $width = $size[0] ?? 0;
        $height = $size[1] ?? 0;
        
        $pdo = get_cms_connection();
// Delete if already exists, then insert
        $pdo->prepare("DELETE FROM media_assets WHERE asset_id = ?")->execute([$asset_id]);
        
        $stmt = $pdo->prepare("INSERT INTO media_assets (asset_id, original_filename, width, height, alt_text, created_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $stmt->execute([$asset_id, $filename, $width, $height, $alt_text]);
        
        return $asset_id;
    }
    return false;
}

function get_asset($asset_id) {
    $pdo = get_cms_connection();
$stmt = $pdo->prepare("SELECT * FROM media_assets WHERE asset_id = ?");
    $stmt->execute([$asset_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_all_assets() {
    $pdo = get_cms_connection();
$stmt = $pdo->query("SELECT * FROM media_assets ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
