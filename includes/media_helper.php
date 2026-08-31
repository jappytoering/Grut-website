<?php
/**
 * Media Helper voor Responsive Images (Centrale Beeldbank)
 */

function render_image($asset_id, $options = []) {
    $dbPath = __DIR__ . '/../storage/content.sqlite';
    
    // Default attributen
    $alt = $options['alt'] ?? '';
    $class = $options['class'] ?? '';
    $loading = $options['loading'] ?? 'lazy'; // Lazy by default
    $decoding = $options['decoding'] ?? 'async'; // Async by default
    $sizes = $options['sizes'] ?? '(max-width: 768px) 100vw, 1200px';
    $fetchpriority = $options['fetchpriority'] ?? null;
    
    // Probeer metadata uit database te halen
    try {
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT * FROM media_assets WHERE asset_id = :asset_id");
        $stmt->execute([':asset_id' => $asset_id]);
        $asset = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($asset) {
            $width = $asset['width'];
            $height = $asset['height'];
            $alt = $alt ?: $asset['alt_text'];
            
            $variants = json_decode($asset['variants_json'], true) ?: [];
            
            // Bouw de srcset string
            $srcset = [];
            foreach ($variants as $size => $variantData) {
                if (isset($variantData['path']) && isset($variantData['width'])) {
                    $srcset[] = "/{$variantData['path']} {$variantData['width']}w";
                }
            }
            $srcsetString = implode(', ', $srcset);
            
            // Als er geen variants zijn (of script heeft nog niet gelopen), fallback naar origineel
            if (empty($srcset)) {
                $src = "/storage/media/originals/" . ($asset['original_filename'] ?? $asset_id);
                $srcsetString = "{$src} {$width}w";
            } else {
                // Fallback src = de grootste / of 'large' variant
                $src = isset($variants['large']['path']) ? "/".$variants['large']['path'] : "/".$variants[array_key_first($variants)]['path'];
            }
            
        } else {
            // Fallback als asset niet in DB zit (voor development of als script faalt)
            $src = "/storage/media/originals/{$asset_id}"; // Aanname: de filename in de map
            $srcsetString = "";
            $width = "";
            $height = "";
        }
        
    } catch (Exception $e) {
        // Fallback bij DB error
        $src = "/storage/media/originals/{$asset_id}";
        $srcsetString = "";
        $width = "";
        $height = "";
    }
    
    // Genereer HTML attributen
    $htmlAttr = [
        'src' => htmlspecialchars($src),
        'alt' => htmlspecialchars($alt)
    ];
    
    if ($class) $htmlAttr['class'] = htmlspecialchars($class);
    if ($srcsetString) {
        $htmlAttr['srcset'] = htmlspecialchars($srcsetString);
        $htmlAttr['sizes'] = htmlspecialchars($sizes);
    }
    if ($width) $htmlAttr['width'] = htmlspecialchars($width);
    if ($height) $htmlAttr['height'] = htmlspecialchars($height);
    if ($loading !== 'eager') {
        $htmlAttr['loading'] = htmlspecialchars($loading);
        $htmlAttr['decoding'] = htmlspecialchars($decoding);
    }
    if ($fetchpriority) $htmlAttr['fetchpriority'] = htmlspecialchars($fetchpriority);
    
    // Compile output
    $output = "<img ";
    foreach ($htmlAttr as $k => $v) {
        $output .= "{$k}=\"{$v}\" ";
    }
    $output .= "/>";
    
    return $output;
}
